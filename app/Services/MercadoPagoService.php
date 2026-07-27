<?php

namespace App\Services;

use App\Models\Orden;
use App\Models\Paquete;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference\Preference;
use RuntimeException;

/**
 * Servicio que encapsula la comunicación con Mercado Pago.
 *
 * Por qué existe: para que los controllers no dependan directo del SDK
 * de MP. Si mañana migramos a Bricks, a otro gateway, o cambiamos el
 * SDK, solo tocamos esta clase.
 */
class MercadoPagoService
{
    private PreferenceClient $preferenceClient;
    private PaymentClient $paymentClient;

    public function __construct()
    {
        $accessToken = config('mercadopago.access_token');

        if (empty($accessToken)) {
            throw new RuntimeException(
                'Falta MP_TOKEN en el .env. Sin access token no se puede hablar con Mercado Pago.'
            );
        }

        MercadoPagoConfig::setAccessToken($accessToken);
        // SERVER (default) apunta a api.mercadopago.com. Usar LOCAL solo
        // en entornos de prueba con la SDK emulada (no necesario en prod).
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::SERVER);

        $this->preferenceClient = new PreferenceClient();
        $this->paymentClient    = new PaymentClient();
    }

    /**
     * Crea una preference de pago para un paquete y devuelve el init_point
     * (URL del Checkout Pro de MP) y el preference_id para guardarlo en la orden.
     *
     * El preference es solo "lo que el usuario va a comprar". El pago se
     * confirma después con el webhook.
     *
     * @return array{id: string, init_point: string, sandbox_init_point: string}
     */
    public function crearPreference(Paquete $paquete, Orden $orden): array
    {
        $idempotencyKey = (string) Str::uuid();

        $backUrlSuccess = $this->resolveCallback(config('mercadopago.back_urls.success'));

        $request = [
            'items' => [
                [
                    'id'          => (string) $paquete->id,
                    'title'       => 'Invitatorio — ' . $paquete->nombre,
                    'description' => Str::limit($paquete->descripcion, 250, ''),
                    'quantity'    => 1,
                    'currency_id' => config('mercadopago.currency'),
                    'unit_price'  => $orden->precio_decimal,
                    'picture_url' => $this->imagenDelPaquete($paquete),
                    'category_id' => 'services',
                ],
            ],
            'payer' => [
                'name'    => $orden->comprador_nombre,
                'email'   => $orden->comprador_email,
                'phone'   => $this->telefonoParaMP($orden->comprador_telefono),
            ],
            'back_urls' => [
                'success' => $backUrlSuccess,
                'pending' => $this->resolveCallback(config('mercadopago.back_urls.pending')),
                'failure' => $this->resolveCallback(config('mercadopago.back_urls.failure')),
            ],
            'notification_url'  => $this->resolveCallback(config('mercadopago.webhook_url')),
            'external_reference' => (string) $orden->id,
            'statement_descriptor' => 'INVITATORIO',
            'metadata' => [
                'orden_id'    => $orden->id,
                'paquete_id'  => $paquete->id,
                'paquete_slug'=> $paquete->slug,
            ],
            'expires' => true,
            'expiration_date_from' => now()->toIso8601String(),
            'expiration_date_to'   => now()->addHours((int) config('mercadopago.expires_in_hours', 24))->toIso8601String(),
        ];

        // auto_return: 'approved' requiere que back_url.success sea HTTPS.
        // MP en producción rechaza el payload completo si es HTTP, así que solo
        // lo activamos cuando la URL del sitio es HTTPS (producción / staging).
        if (str_starts_with($backUrlSuccess, 'https://')) {
            $request['auto_return'] = 'approved';
        }

        try {
            /** @var Preference $preference */
            $preference = $this->preferenceClient->create(
                $request,
                $this->withIdempotency($idempotencyKey)
            );
        } catch (MPApiException $e) {
            Log::error('MercadoPago: error creando preference', [
                'orden_id' => $orden->id,
                'status'   => $e->getApiResponse()?->getStatusCode(),
                'content'  => $e->getApiResponse()?->getContent(),
            ]);
            throw $e;
        }

        return [
            'id'                 => $preference->id,
            'init_point'         => $preference->init_point,
            'sandbox_init_point' => $preference->sandbox_init_point,
            'idempotency_key'    => $idempotencyKey,
        ];
    }

    /**
     * Recupera un pago de MP por id. Se usa desde el webhook y desde el
     * callback de "success" para refrescar el estado real (porque el back_url
     * del usuario NO es fuente de verdad: el cliente pudo cerrar la pestaña
     * antes del redirect, o mentir con el parámetro status=approved).
     */
    public function obtenerPago(int $paymentId): ?object
    {
        try {
            return $this->paymentClient->get($paymentId);
        } catch (MPApiException $e) {
            if ($e->getApiResponse()?->getStatusCode() === 404) {
                return null;
            }
            Log::error('MercadoPago: error consultando pago', [
                'payment_id' => $paymentId,
                'status'     => $e->getApiResponse()?->getStatusCode(),
                'content'    => $e->getApiResponse()?->getContent(),
            ]);
            throw $e;
        }
    }

    /**
     * Mapea un status string de MP a uno de los valores válidos de nuestra
     * columna `ordenes.estado`. Lo centralizamos acá para que el controller
     * no repita lógica.
     */
    public static function mapearEstado(string $mpStatus): string
    {
        return match ($mpStatus) {
            'approved'     => 'approved',
            'authorized'   => 'authorized',
            'in_process', 'pending', 'in_mediation' => 'in_process',
            'rejected'     => 'rejected',
            'cancelled'    => 'cancelled',
            'refunded'     => 'refunded',
            'charged_back' => 'refunded',
            default        => 'pending',
        };
    }

    private function withIdempotency(string $key): \MercadoPago\Client\Common\RequestOptions
    {
        $options = new \MercadoPago\Client\Common\RequestOptions();
        $options->setCustomHeaders(['X-Idempotency-Key: ' . $key]);
        return $options;
    }

    private function resolveCallback(mixed $value): string
    {
        return $value instanceof \Closure ? (string) $value() : (string) $value;
    }

    private function imagenDelPaquete(Paquete $paquete): ?string
    {
        // En el futuro podemos colgar una imagen por paquete. Por ahora
        // dejamos el logo general para que MP muestre algo reconocible.
        $logo = public_path('images/invitatorio_horizontal.png');
        return is_file($logo) ? asset('images/invitatorio_horizontal.png') : null;
    }

    private function telefonoParaMP(?string $telefono): ?array
    {
        if (! $telefono) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $telefono) ?? '';

        // Si empieza con 52 (México) y trae 10 dígitos, separamos área code 52.
        if (strlen($digits) === 12 && str_starts_with($digits, '52')) {
            return ['area_code' => '52', 'number' => substr($digits, 2)];
        }
        // 10 dígitos nacionales → área code "" (lo deduce MP).
        if (strlen($digits) === 10) {
            return ['area_code' => '', 'number' => $digits];
        }
        return ['number' => $digits];
    }
}

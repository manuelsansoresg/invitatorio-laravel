<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use App\Models\Orden;
use App\Models\Paquete;
use App\Services\MercadoPagoService;
use App\Services\SuscripcionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use MercadoPago\Exceptions\MPApiException;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly MercadoPagoService $mp,
        private readonly SuscripcionService $suscripciones,
    ) {
    }

    /**
     * GET /comprar/{paquete}
     * Form donde el cliente llena sus datos antes de mandarlo a MP.
     *
     * Query params aceptados:
     *   - coupon / cupon: código del cupón. Se valida y se pinta el descuento
     *     en el resumen si aplica.
     */
    public function show(Request $request, Paquete $paquete): View
    {
        abort_unless($paquete->activo, 404);

        $codigo = $this->leerCodigoCoupon($request);
        $resolucion = Cupon::resolverParaCheckout($codigo, $paquete);

        Log::info('Checkout::show', [
            'paquete_id'     => $paquete->id,
            'paquete_slug'   => $paquete->slug,
            'paquete_activo' => $paquete->activo,
            'coupon_input'   => $codigo,
            'coupon_ok'      => $resolucion['ok'],
            'coupon_mensaje' => $resolucion['mensaje'],
            'request_url'    => $request->fullUrl(),
        ]);

        return view('checkout.buy', [
            'paquete'        => $paquete,
            'formatos'       => $this->formatosParaSelect(),
            'coupon'         => $resolucion['cupon'],
            'couponOk'       => $resolucion['ok'],
            'couponMensaje'  => $resolucion['mensaje'],
            'couponCodigo'   => $resolucion['ok'] ? $resolucion['cupon']->codigo : null,
            'descuentoCentavos' => (int) $resolucion['descuento_centavos'],
            'totalFinalCentavos' => max(0, (int) $paquete->precio_centavos - (int) $resolucion['descuento_centavos']),
        ]);
    }

    /**
     * POST /comprar/{paquete}
     * Crea la orden (con cupón si hay), crea la preference en MP y redirige.
     */
    public function buy(Request $request, Paquete $paquete): RedirectResponse
    {
        abort_unless($paquete->activo, 404);

        $data = $request->validate([
            'comprador_nombre'   => ['required', 'string', 'min:2', 'max:120'],
            'comprador_email'    => ['required', 'email:rfc', 'max:160'],
            'comprador_telefono' => ['nullable', 'string', 'min:8', 'max:40'],
            'tipo_evento'        => ['nullable', Rule::in(array_keys($this->formatosParaSelect()))],
            'terminos'           => ['accepted'],
            'coupon'             => ['nullable', 'string', 'max:40'],
        ], [
            'comprador_nombre.required' => 'Necesitamos tu nombre para poderte contactar.',
            'comprador_email.required'  => 'El email es donde te enviaremos la confirmación.',
            'comprador_email.email'     => 'Ese email no se ve bien, revísalo por favor.',
            'terminos.accepted'         => 'Debes aceptar los términos para continuar.',
        ]);

        $codigo = $this->leerCodigoCoupon($request);
        $resolucion = Cupon::resolverParaCheckout($codigo, $paquete);

        // Si el cliente escribió un cupón y NO es válido, no fallamos el
        // checkout entero: rechazamos solo el cupón y dejamos que pague
        // el precio completo, pero le avisamos vía withErrors.
        $cupon = $resolucion['ok'] ? $resolucion['cupon'] : null;
        $descuentoCentavos = $cupon ? (int) $resolucion['descuento_centavos'] : 0;
        $totalFinalCentavos = max(0, (int) $paquete->precio_centavos - $descuentoCentavos);

        $orden = DB::transaction(function () use ($paquete, $data, $cupon, $descuentoCentavos, $totalFinalCentavos, $request) {
            return Orden::create([
                'paquete_id'              => $paquete->id,
                'paquete_nombre'          => $paquete->nombre,
                'paquete_precio_centavos' => $paquete->precio_centavos,
                'descuento_centavos'      => $descuentoCentavos,
                'total_final_centavos'    => $totalFinalCentavos,
                'cupon_id'                => $cupon?->id,
                'cupon_codigo'            => $cupon?->codigo,
                'comprador_nombre'        => trim($data['comprador_nombre']),
                'comprador_email'         => mb_strtolower(trim($data['comprador_email'])),
                'comprador_telefono'      => $data['comprador_telefono'] ?? null,
                'tipo_evento'             => $data['tipo_evento'] ?? null,
                'estado'                  => 'pending',
                'ip'                      => $request->ip(),
                'user_agent'              => substr((string) $request->userAgent(), 0, 255),
            ]);
        });

        try {
            $preference = $this->mp->crearPreference($paquete, $orden);
        } catch (MPApiException | Throwable $e) {
            Log::error('Checkout: no se pudo crear la preference', [
                'orden_id' => $orden->id,
                'error'    => $e->getMessage(),
            ]);
            $orden->delete();
            return back()
                ->withInput()
                ->withErrors([
                    'mp' => 'No pudimos conectarnos a Mercado Pago. Intenta de nuevo en un momento.',
                ]);
        }

        $orden->update(['mp_preference_id' => $preference['id']]);

        $checkoutUrl = config('mercadopago.is_sandbox')
            ? ($preference['sandbox_init_point'] ?: $preference['init_point'])
            : ($preference['init_point'] ?? null);

        if (! $checkoutUrl) {
            $orden->delete();
            return back()
                ->withInput()
                ->withErrors(['mp' => 'Mercado Pago no devolvió una URL de checkout. Intenta de nuevo.']);
        }

        return redirect()->away($checkoutUrl);
    }

    /**
     * GET /checkout/success
     * Página "¡Listo!". El parámetro payment_id lo usamos para revalidar
     * contra la API de MP — nunca confiamos solo en ?status=approved.
     */
    public function success(Request $request): View
    {
        $orden = $this->resolverOrdenDesdeCallback($request, ['approved', 'pending', 'in_process']);

        if ($orden && ! $orden->estaPagada() && $orden->mp_payment_id) {
            try {
                $pago = $this->mp->obtenerPago((int) $orden->mp_payment_id);
                if ($pago) {
                    $this->aplicarPagoAOrden($orden, $pago);
                }
            } catch (Throwable $e) {
                // Silencioso: el webhook resolverá.
            }
        }

        // Si ya quedó aprobada (por webhook o por revalidación), creamos
        // la suscripción si todavía no existe. Idempotente.
        if ($orden && $orden->estaPagada() && ! $orden->suscripcion()->exists()) {
            $this->suscripciones->crearSuscripcionPorCompra($orden);
        }

        return view('checkout.success', [
            'orden' => $orden,
        ]);
    }

    public function pending(Request $request): View
    {
        $orden = $this->resolverOrdenDesdeCallback($request, ['pending', 'in_process', 'authorized']);
        return view('checkout.pending', ['orden' => $orden]);
    }

    public function failure(Request $request): View
    {
        $orden = $this->resolverOrdenDesdeCallback($request, ['rejected', 'cancelled']);
        return view('checkout.failure', ['orden' => $orden]);
    }

    // ─────────────── helpers privados ───────────────

    /**
     * Acepta "coupon" o "cupon" como query param. El form manda "coupon"
     * para mantener consistencia técnica, pero aceptamos ambos.
     */
    private function leerCodigoCoupon(Request $request): ?string
    {
        $raw = $request->input('coupon') ?? $request->input('cupon');
        if (! filled($raw)) {
            return null;
        }
        return strtoupper(preg_replace('/\s+/', '', (string) $raw));
    }

    private function resolverOrdenDesdeCallback(Request $request, array $estadosValidos): ?Orden
    {
        $orden = null;

        if ($request->filled('external_reference')) {
            $orden = Orden::find((int) $request->query('external_reference'));
        }

        if (! $orden && $request->filled('payment_id')) {
            $orden = Orden::where('mp_payment_id', (string) $request->query('payment_id'))->first();
        }

        if (! $orden && $request->filled('collection_id')) {
            $orden = Orden::where('mp_payment_id', (string) $request->query('collection_id'))->first();
        }

        if (! $orden && $request->filled('payment_id') && $request->filled('external_reference')) {
            $orden = Orden::find((int) $request->query('external_reference'));
            if ($orden) {
                $orden->update(['mp_payment_id' => (string) $request->query('payment_id')]);
            }
        }

        return $orden;
    }

    public function aplicarPagoAOrden(Orden $orden, object $pago): void
    {
        $estadoMP = $pago->status ?? null;
        if (! $estadoMP) {
            return;
        }

        $data = [
            'mp_payment_id'    => (string) ($pago->id ?? $orden->mp_payment_id),
            'mp_payment_type'  => $pago->payment_type_id ?? null,
            'mp_status'        => $estadoMP,
            'mp_status_detail' => $pago->status_detail ?? null,
            'estado'           => MercadoPagoService::mapearEstado($estadoMP),
        ];

        if ($data['estado'] === 'approved' && ! $orden->paid_at) {
            $data['paid_at'] = now();
            // Si el cupón estaba aplicado pero no se había contado aún
            // (porque el pago falló antes), lo incrementamos ahora.
            if ($orden->cupon_id && $orden->cupon) {
                $orden->cupon()->increment('usos_actuales');
            }
        }

        $orden->update($data);
    }

    private function formatosParaSelect(): array
    {
        return [
            'boda'           => 'Boda',
            'xv'             => 'XV años',
            'cumpleanos'     => 'Cumpleaños',
            'bautizo'        => 'Bautizo',
            'baby_shower'    => 'Baby shower',
            'aniversario'    => 'Aniversario',
            'otro'           => 'Otro',
        ];
    }
}

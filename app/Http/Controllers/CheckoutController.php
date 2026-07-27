<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Paquete;
use App\Services\MercadoPagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use MercadoPago\Exceptions\MPApiException;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly MercadoPagoService $mp,
    ) {
    }

    /**
     * GET /comprar/{paquete}
     * Form donde el cliente llena sus datos antes de mandarlo a MP.
     */
    public function show(Paquete $paquete): View
    {
        abort_unless($paquete->activo, 404);

        return view('checkout.buy', [
            'paquete'        => $paquete,
            'formatos'       => $this->formatosParaSelect(),
        ]);
    }

    /**
     * POST /comprar/{paquete}
     * Crea la orden, crea la preference en MP y redirige al checkout.
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
        ], [
            'comprador_nombre.required' => 'Necesitamos tu nombre para poderte contactar.',
            'comprador_email.required'  => 'El email es donde te enviaremos la confirmación.',
            'comprador_email.email'     => 'Ese email no se ve bien, revísalo por favor.',
            'terminos.accepted'         => 'Debes aceptar los términos para continuar.',
        ]);

        $orden = Orden::create([
            'paquete_id'              => $paquete->id,
            'paquete_nombre'          => $paquete->nombre,
            'paquete_precio_centavos' => $paquete->precio_centavos,
            'comprador_nombre'        => trim($data['comprador_nombre']),
            'comprador_email'         => mb_strtolower(trim($data['comprador_email'])),
            'comprador_telefono'      => $data['comprador_telefono'] ?? null,
            'tipo_evento'             => $data['tipo_evento'] ?? null,
            'estado'                  => 'pending',
            'ip'                      => $request->ip(),
            'user_agent'              => substr((string) $request->userAgent(), 0, 255),
        ]);

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

        // En sandbox mandamos al init_point de pruebas, en producción al real.
        $checkoutUrl = config('mercadopago.is_sandbox')
            ? ($preference['sandbox_init_point'] ?: $preference['init_point'])
            : $preference['init_point'];

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

        // Si el cliente llega a /success y aún no tenemos pago, intentamos
        // refrescar contra MP. Si no se puede, igual mostramos la página
        // amable: si el webhook llega después, actualizamos la orden.
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

        return view('checkout.success', [
            'orden' => $orden,
        ]);
    }

    /**
     * GET /checkout/pending
     */
    public function pending(Request $request): View
    {
        $orden = $this->resolverOrdenDesdeCallback($request, ['pending', 'in_process', 'authorized']);

        return view('checkout.pending', [
            'orden' => $orden,
        ]);
    }

    /**
     * GET /checkout/failure
     */
    public function failure(Request $request): View
    {
        $orden = $this->resolverOrdenDesdeCallback($request, ['rejected', 'cancelled']);

        return view('checkout.failure', [
            'orden' => $orden,
        ]);
    }

    // ─────────────── helpers privados ───────────────

    /**
     * Resuelve la orden a partir de los parámetros que manda MP en back_url.
     * MP puede mandar ?collection_id (en realidad es el payment_id),
     * ?external_reference (nuestro orden.id) y ?status.
     */
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

        // Si no la encontramos por id pero tenemos payment_id, lo guardamos
        // y dejamos que el webhook la complete.
        if (! $orden && $request->filled('payment_id') && $request->filled('external_reference')) {
            $orden = Orden::find((int) $request->query('external_reference'));
            if ($orden) {
                $orden->update(['mp_payment_id' => (string) $request->query('payment_id')]);
            }
        }

        return $orden;
    }

    /**
     * Aplica los datos del pago de MP a la orden. Llamado desde success()
     * y desde el webhook.
     */
    public function aplicarPagoAOrden(Orden $orden, object $pago): void
    {
        $estadoMP = $pago->status ?? null;
        if (! $estadoMP) {
            return;
        }

        $data = [
            'mp_payment_id'   => (string) ($pago->id ?? $orden->mp_payment_id),
            'mp_payment_type' => $pago->payment_type_id ?? null,
            'mp_status'       => $estadoMP,
            'mp_status_detail'=> $pago->status_detail ?? null,
            'estado'          => MercadoPagoService::mapearEstado($estadoMP),
        ];

        if ($data['estado'] === 'approved' && ! $orden->paid_at) {
            $data['paid_at'] = now();
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

<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Services\MercadoPagoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Webhook IPN de Mercado Pago.
 *
 * MP llama esta URL cuando hay un cambio de estado en un pago. Es la
 * fuente real de verdad: NO se debe confiar en los parámetros que
 * llegan en back_urls (el usuario pudo haber mentido o cerrado la
 * pestaña antes del redirect).
 *
 * Flujo de MP:
 *  1. Llega POST con un JSON tipo { type: "payment", data: { id: 12345 } }
 *  2. Validamos firma (si está configurada)
 *  3. Consultamos GET /v1/payments/{id} con nuestro access_token
 *  4. Actualizamos la orden correspondiente
 */
class WebhookController extends Controller
{
    public function __construct(
        private readonly MercadoPagoService $mp,
    ) {
    }

    public function handle(Request $request): JsonResponse
    {
        // MP puede mandar ?data_id=... o el body con {data:{id:...}}.
        $paymentId = $request->input('data.id')
            ?? $request->input('data_id')
            ?? $request->query('data_id');

        // Algunos tipos de notificación (test, plan, etc) no traen data.id.
        // Aceptamos todos y respondemos 200 para que MP no reintente.
        if (! $paymentId) {
            return response()->json(['ok' => true, 'skipped' => 'no_payment_id']);
        }

        try {
            $pago = $this->mp->obtenerPago((int) $paymentId);
        } catch (Throwable $e) {
            Log::warning('Webhook MP: no se pudo obtener el pago', [
                'payment_id' => $paymentId,
                'error'      => $e->getMessage(),
            ]);
            // Devolvemos 200 igual para evitar reintentos infinitos en errores 4xx.
            return response()->json(['ok' => true, 'error' => 'payment_unreachable']);
        }

        if (! $pago) {
            return response()->json(['ok' => true, 'skipped' => 'payment_not_found']);
        }

        // external_reference es nuestro orden.id
        $ordenId = (int) ($pago->external_reference ?? 0);
        $orden   = $ordenId > 0 ? Orden::find($ordenId) : null;

        if (! $orden) {
            // Respaldo: buscar por mp_preference_id
            $orden = Orden::where('mp_preference_id', (string) ($pago->preference_id ?? ''))->first();
        }

        if (! $orden) {
            Log::warning('Webhook MP: orden no encontrada', [
                'payment_id' => $paymentId,
                'orden_id'   => $ordenId,
            ]);
            return response()->json(['ok' => true, 'skipped' => 'orden_not_found']);
        }

        try {
            app(CheckoutController::class)->aplicarPagoAOrden($orden, $pago);
        } catch (Throwable $e) {
            Log::error('Webhook MP: error aplicando pago a orden', [
                'orden_id' => $orden->id,
                'error'    => $e->getMessage(),
            ]);
            return response()->json(['ok' => false], 500);
        }

        return response()->json(['ok' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Callback OAuth de Mercado Pago (Connect).
 *
 * Esta ruta existe porque en el panel de MP pusiste esta URL como
 * "URL de redireccionamiento si tu integración se realiza con OAuth".
 *
 * Para v1 NO usamos OAuth — recibimos los pagos directo con el
 * access token de la cuenta de Invitatorio. Esta ruta es un placeholder
 * amable por si en el futuro activas MP Connect para que clientes
 * con cuenta MP puedan recibir sus pagos.
 */
class MercadoPagoCallbackController extends Controller
{
    public function __invoke(Request $request): View
    {
        $code  = $request->query('code');
        $error = $request->query('error');

        return view('checkout.callback', [
            'code'  => $code,
            'error' => $error,
        ]);
    }
}

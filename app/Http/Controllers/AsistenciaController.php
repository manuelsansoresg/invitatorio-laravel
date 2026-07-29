<?php

namespace App\Http\Controllers;

use App\Models\Invitado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller para el link único de cada invitado (/c/{token}).
 *
 * El link YA NO abre una vista de confirmación propia. Redirige a la
 * invitación pública (que ya tiene su modal de confirmar) pasando
 * el token del invitado como query param. Cuando el invitado confirma
 * desde el modal, el ConfirmacionController::store asocia la
 * confirmación con el invitado de la lista.
 *
 * El método `show` se conserva para que un link viejo o externo
 * (compartido antes de este cambio) siga funcionando — pero ahora
 * redirige en lugar de renderizar una vista separada.
 */
class AsistenciaController extends Controller
{
    /**
     * GET /c/{token}
     *
     * Redirige a la invitación pública. Pasa ?invitado_token= para
     * que el ConfirmacionController pueda asociar la confirmación
     * con el invitado de la lista del cliente.
     */
    public function show(string $token): RedirectResponse
    {
        $invitado = Invitado::where('token', $token)->first();

        if (! $invitado) {
            abort(404, 'Link inválido o expirado.');
        }

        return redirect()->to(
            url('/invitacion/' . $invitado->invitacion->ruta) . '?invitado_token=' . $token
        );
    }

    /**
     * Mantengo la firma del método "gracias" por si algún template
     * viejo lo referencia, pero ya no se usa en el flow principal.
     */
    public function gracias(string $token): View
    {
        abort(404);
    }
}

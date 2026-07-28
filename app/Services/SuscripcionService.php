<?php

namespace App\Services;

use App\Models\Invitacion;
use App\Models\Orden;
use App\Models\Paquete;
use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Servicio que encapsula toda la lógica de Suscripciones.
 *
 * Por qué existe: para que ni el WebhookController, ni el CheckoutController,
 * ni los controllers del admin tengan que duplicar las reglas de
 * "cómo se crea una suscripción al aprobarse un pago", "cómo se
 * consume un cupo al publicar una invitación", etc.
 *
 * Reglas clave:
 *  - Al aprobarse el pago de una orden, se crea UNA suscripción
 *    automática (motivo=compra) con max=max_invitaciones del paquete.
 *  - El admin puede crear suscripciones manuales (motivo=manual/regalo)
 *    sin orden asociada.
 *  - Al PUBLICAR una invitación, se descuenta 1 cupo de la suscripción
 *    que la "paga" (suscripcion_id de la invitación). Si no se puede,
 *    se rechaza la publicación.
 *  - Al BORRAR una invitación en borrador, NO se devuelve cupo (los
 *    borradores nunca gastaron).
 *  - Al ARCHIVAR una invitación publicada, NO se devuelve cupo (esa
 *    invitación ya consumió un cupo histórico).
 */
class SuscripcionService
{
    /**
     * Crea la suscripción automática al aprobarse un pago.
     * Llamado desde WebhookController y desde CheckoutController::success
     * (en caso de que el webhook llegue tarde).
     *
     * Idempotente: si la orden ya tiene suscripción, no crea otra.
     */
    public function crearSuscripcionPorCompra(Orden $orden): ?Suscripcion
    {
        if ($orden->suscripcion()->exists()) {
            return $orden->suscripcion;
        }

        $paquete = $orden->paquete;
        if (! $paquete) {
            return null;
        }

        // El comprador de la orden NO necesariamente es un usuario
        // registrado. Si la orden trae email y ese email está en users,
        // vinculamos. Si no, NO creamos suscripción (el admin la puede
        // asignar manual cuando el usuario se registre).
        $user = User::query()->where('email', $orden->comprador_email)->first();
        if (! $user) {
            return null;
        }

        return DB::transaction(function () use ($orden, $paquete, $user) {
            return Suscripcion::create([
                'user_id'             => $user->id,
                'paquete_id'          => $paquete->id,
                'orden_id'            => $orden->id,
                'motivo'              => Suscripcion::MOTIVO_COMPRA,
                'max_invitaciones'    => (int) $paquete->max_invitaciones,
                'invitaciones_usadas' => 0,
                'fecha_inicio'        => now(),
                'fecha_fin'           => null,
                'cancelada'           => false,
                'notas_admin'         => "Auto-creada al aprobarse el pago de la orden #{$orden->id}.",
            ]);
        });
    }

    /**
     * Crea una suscripción manual (cortesía, prueba, premio).
     * Llamado desde Admin\SuscripcionController.
     */
    public function crearSuscripcionManual(User $user, Paquete $paquete, array $opciones = []): Suscripcion
    {
        return DB::transaction(function () use ($user, $paquete, $opciones) {
            return Suscripcion::create([
                'user_id'             => $user->id,
                'paquete_id'          => $paquete->id,
                'orden_id'            => null,
                'motivo'              => $opciones['motivo'] ?? Suscripcion::MOTIVO_MANUAL,
                'max_invitaciones'    => (int) ($opciones['max_invitaciones'] ?? $paquete->max_invitaciones),
                'invitaciones_usadas' => 0,
                'fecha_inicio'        => $opciones['fecha_inicio'] ?? now(),
                'fecha_fin'           => $opciones['fecha_fin'] ?? null,
                'cancelada'           => false,
                'notas_admin'         => $opciones['notas_admin'] ?? null,
            ]);
        });
    }

    /**
     * Reserva un cupo de la suscripción y devuelve OK o un mensaje
     * de error. Llamado al PUBLICAR una invitación (no al crearla).
     */
    public function consumirCupoParaPublicar(Suscripcion $suscripcion): array
    {
        return DB::transaction(function () use ($suscripcion) {
            $suscripcion->refresh();

            if ($suscripcion->cancelada) {
                return ['ok' => false, 'mensaje' => 'La suscripción fue cancelada por el administrador.'];
            }
            if ($suscripcion->fecha_fin && Carbon::now()->gt($suscripcion->fecha_fin)) {
                return ['ok' => false, 'mensaje' => 'La suscripción está vencida.'];
            }
            if (! $suscripcion->tieneCupoDisponible()) {
                return ['ok' => false, 'mensaje' => 'La suscripción ya no tiene cupo disponible.'];
            }

            $suscripcion->increment('invitaciones_usadas');
            return ['ok' => true, 'mensaje' => null];
        });
    }

    /**
     * Llamado al PUBLICAR una invitación. Calcula la fecha_caducidad
     * y persiste en la invitación. Devuelve la fecha de caducidad.
     */
    public function publicar(Invitacion $invitacion, Suscripcion $suscripcion): Carbon
    {
        $fechaPublicacion = Carbon::now();
        $diasCaducidad = (int) $suscripcion->paquete->dias_caducidad;
        $fechaCaducidad = $fechaPublicacion->copy()->addDays($diasCaducidad);

        $invitacion->update([
            'estado'           => 'publicada',
            'publicada_at'     => $fechaPublicacion,
            'fecha_caducidad'  => $fechaCaducidad,
            'suscripcion_id'   => $suscripcion->id,
        ]);

        return $fechaCaducidad;
    }
}

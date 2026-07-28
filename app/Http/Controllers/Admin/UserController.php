<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orden;
use App\Models\Paquete;
use App\Models\Suscripcion;
use App\Models\Template;
use App\Models\User;
use App\Services\SuscripcionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Admin · Gestión de Usuarios.
 *
 * El admin puede:
 *  - Ver todos los usuarios
 *  - Editar nombre, email, rol, estado (activo/inactivo)
 *  - Cambiar la contraseña de cualquier usuario
 *  - Asignar / desasignar templates a un usuario (pivot user_templates)
 *  - Ver la suscripción activa del usuario y el historial de órdenes
 *  - Crear suscripciones manuales (cortesía, premio) en un paso aparte
 */
class UserController extends Controller
{
    public function __construct(
        private readonly SuscripcionService $suscripciones,
    ) {
    }

    public function index(): View
    {
        $users = User::query()
            ->withCount(['invitaciones', 'suscripciones'])
            ->with(['suscripciones' => fn ($q) => $q->latest('id')->limit(1)])
            ->latest('id')
            ->get();

        return view('admin.users.index', [
            'users'  => $users,
            'roles'  => User::roles(),
        ]);
    }

    public function edit(User $user): View
    {
        $user->load(['templates', 'suscripciones' => fn ($q) => $q->latest('id'), 'ordenes' => fn ($q) => $q->latest('id')->limit(10)]);

        return view('admin.users.edit', [
            'user'        => $user,
            'roles'       => User::roles(),
            'templates'   => Template::query()->orderBy('formato')->orderBy('orden')->orderBy('nombre')->get(),
            'paquetes'    => Paquete::query()->orderBy('formato')->orderBy('orden')->get(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'   => ['required', Rule::in(User::roles())],
            'activo' => ['nullable', 'boolean'],
        ], [
            'email.unique' => 'Ya hay otro usuario con ese email.',
        ]);

        $user->update([
            'name'   => $data['name'],
            'email'  => $data['email'],
            'role'   => $data['role'],
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('status', 'Usuario actualizado.');
    }

    /**
     * Cambia la contraseña del usuario. El admin la puede resetear
     * sin necesidad de la actual.
     */
    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'password'              => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.confirmed' => 'La confirmación no coincide.',
        ]);

        $user->update(['password' => Hash::make($data['password'])]);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('status', 'Contraseña actualizada. El usuario deberá usar la nueva al próximo login.');
    }

    /**
     * Sincroniza los templates habilitados para este usuario.
     *
     * Recibe `template_activo[<id>]` = '1' para los que deben estar
     * activos. Los que no vienen o vienen en 0 se desactivan (no se
     * borran, para no perder histórico de invitaciones creadas con
     * ese template).
     */
    public function updateTemplates(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'template_activo'   => ['nullable', 'array'],
            'template_activo.*' => ['nullable', 'boolean'],
        ]);

        $estadoPorTemplate = $data['template_activo'] ?? [];

        // Tomamos TODOS los templates que existen y marcamos activo
        // según lo que mandó el form. Si no mandó nada, ninguno.
        $todosLosTemplates = Template::query()->pluck('id')->all();

        DB::transaction(function () use ($user, $todosLosTemplates, $estadoPorTemplate) {
            foreach ($todosLosTemplates as $templateId) {
                $activo = (bool) ($estadoPorTemplate[$templateId] ?? false);
                $user->templates()->syncWithoutDetaching([
                    $templateId => [
                        'activo'      => $activo,
                        'asignado_en' => now(),
                    ],
                ]);
                // syncWithoutDetaching solo actualiza/agrega. Para
                // forzar que un template que ya estaba asignado pero
                // NO vino en el form quede activo=false, necesitamos
                // un update directo:
                $user->templates()->updateExistingPivot($templateId, [
                    'activo' => $activo,
                ]);
            }
        });

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('status', 'Templates actualizados. Los que quedaron desactivados ya no los ve el cliente.');
    }

    /**
     * Crea una suscripción manual (cortesía / premio / prueba) al usuario.
     * Llamado desde el formulario de la vista edit.
     */
    public function storeSuscripcion(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'paquete_id'         => ['required', 'integer', Rule::exists('paquetes', 'id')],
            'max_invitaciones'   => ['nullable', 'integer', 'min:1', 'max:9999'],
            'fecha_fin'          => ['nullable', 'date'],
            'motivo'             => ['required', Rule::in([Suscripcion::MOTIVO_MANUAL, Suscripcion::MOTIVO_REGALO])],
            'notas_admin'        => ['nullable', 'string', 'max:500'],
        ]);

        $paquete = Paquete::findOrFail($data['paquete_id']);

        $this->suscripciones->crearSuscripcionManual($user, $paquete, [
            'max_invitaciones' => $data['max_invitaciones'] ?? null,
            'fecha_fin'        => $data['fecha_fin'] ?? null,
            'motivo'           => $data['motivo'],
            'notas_admin'      => $data['notas_admin'] ?? null,
        ]);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('status', "Suscripción manual creada ({$paquete->nombre}).");
    }

    /**
     * Cancela una suscripción existente. No la borra (por histórico),
     * solo la marca como cancelada.
     */
    public function cancelSuscripcion(Request $request, User $user, Suscripcion $suscripcion): RedirectResponse
    {
        abort_unless($suscripcion->user_id === $user->id, 404);

        $suscripcion->update([
            'cancelada'   => true,
            'notas_admin' => trim(($suscripcion->notas_admin ?? '') . "\n[" . now()->format('Y-m-d H:i') . "] Cancelada por admin."),
        ]);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('status', "Suscripción #{$suscripcion->id} cancelada.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Invitacion;
use App\Models\Orden;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $invitaciones = Invitacion::query()
            ->with('cliente')
            ->withCount('confirmaciones')
            ->latest()
            ->get();

        $users = User::query()
            ->latest()
            ->get();

        $clientes = User::query()
            ->where('role', User::ROLE_CLIENT)
            ->orderBy('name')
            ->get();

        // Órdenes pagadas: usadas para la sección "Invitaciones compradas"
        // y para calcular las ganancias. Cargamos la suscripción y todas las
        // invitaciones que el cliente creó con esa suscripción + su template.
        $ordenesPagadas = Orden::query()
            ->where('estado', 'approved')
            ->with(['suscripcion' => fn ($q) => $q->with(['invitaciones' => fn ($q2) => $q2->with('template')->latest('id'), 'usuario'])])
            ->latest('paid_at')
            ->get();

        $ganancias = [
            'total_historico' => (int) Orden::where('estado', 'approved')->sum('paquete_precio_centavos'),
            'este_mes'        => (int) Orden::where('estado', 'approved')
                ->where('paid_at', '>=', now()->startOfMonth())
                ->sum('paquete_precio_centavos'),
            'este_anio'       => (int) Orden::where('estado', 'approved')
                ->where('paid_at', '>=', now()->startOfYear())
                ->sum('paquete_precio_centavos'),
            'ordenes_total'   => (int) Orden::where('estado', 'approved')->count(),
            'por_paquete'     => Orden::where('estado', 'approved')
                ->selectRaw('paquete_nombre, COUNT(*) as count, SUM(paquete_precio_centavos) as total')
                ->groupBy('paquete_nombre')
                ->orderByDesc('total')
                ->get(),
        ];

        return view('admin.dashboard', [
            'clientes'       => $clientes,
            'ganancias'      => $ganancias,
            'invitaciones'   => $invitaciones,
            'ordenesPagadas' => $ordenesPagadas,
            'roles'          => User::roles(),
            'users'          => $users,
        ]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(User::roles())],
        ]);

        User::create($validated);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function updateInvitationClient(Request $request, Invitacion $invitacion): RedirectResponse
    {
        $validated = validator($request->all(), [
            'user_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', User::ROLE_CLIENT),
            ],
        ], [
            'user_id.exists' => 'El cliente seleccionado no está disponible.',
        ])->validateWithBag('clientAssignment');

        $invitacion->update([
            'user_id' => $validated['user_id'] ?? null,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Cliente asignado correctamente.');
    }

    public function cloneInvitation(Request $request, Invitacion $invitacion): RedirectResponse
    {
        $validated = validator($request->all(), [
            'nombre_completo' => ['required', 'string', 'max:160', 'regex:/^\\S+(?:\\s+\\S+)+$/u'],
        ], [
            'nombre_completo.required' => 'Escribe el nombre completo de la nueva invitación.',
            'nombre_completo.regex' => 'Escribe por lo menos nombre y apellido.',
            'nombre_completo.max' => 'El nombre completo no puede superar 160 caracteres.',
        ])->validateWithBag('cloneInvitation');

        $fullName = preg_replace('/\\s+/u', ' ', trim($validated['nombre_completo']));
        $nameParts = preg_split('/\\s+/u', $fullName) ?: [];
        $firstName = array_shift($nameParts);
        $lastName = array_shift($nameParts);
        $secondLastName = filled($nameParts) ? implode(' ', $nameParts) : null;
        $route = $this->uniqueCloneRoute($invitacion, $fullName);

        $clone = DB::transaction(function () use ($invitacion, $firstName, $lastName, $secondLastName, $route): Invitacion {
            $invitacion->loadMissing(['blocks', 'gallery']);

            $clone = $invitacion->replicate();
            $clone->forceFill([
                'nombre' => $firstName,
                'apellido_paterno' => $lastName,
                'apellido_materno' => $secondLastName,
                'ruta' => $route,
                'user_id' => null,
                'cliente_email' => null,
                'estado' => 'borrador',
                'publicada_at' => null,
            ]);

            foreach (['musica_path', 'imagen_portada_path', 'archivo_final_path'] as $pathField) {
                $clone->{$pathField} = $this->cloneOwnedAsset($invitacion->{$pathField}, $invitacion->ruta, $route);
            }

            $clone->save();

            foreach ($invitacion->blocks as $block) {
                $blockClone = $block->replicate();
                $blockClone->config_json = $this->cloneConfigAssets($block->config_json, $invitacion->ruta, $route);
                $clone->blocks()->save($blockClone);
            }

            foreach ($invitacion->gallery as $galleryItem) {
                $galleryClone = $galleryItem->replicate();
                $galleryClone->imagen_path = $this->cloneOwnedAsset($galleryItem->imagen_path, $invitacion->ruta, $route);
                $clone->gallery()->save($galleryClone);
            }

            return $clone;
        });

        return redirect()
            ->route('admin.invitaciones.edit', $clone)
            ->with('status', 'Invitación clonada. Ya puedes personalizarla sin afectar la original.');
    }

    public function destroyInvitation(Request $request, Invitacion $invitacion): RedirectResponse
    {
        validator($request->all(), [
            'confirm_route' => ['required', Rule::in([$invitacion->ruta])],
        ], [
            'confirm_route.required' => 'Escribe la ruta de la invitación para confirmar.',
            'confirm_route.in' => 'La ruta escrita no coincide con la invitación.',
        ])->validateWithBag('deleteInvitation');

        $route = $invitacion->ruta;
        $name = $invitacion->nombre_completo;

        DB::transaction(fn () => $invitacion->delete());
        Storage::disk('public_uploads')->deleteDirectory('uploads/invitaciones/'.trim($route, '/'));
        Storage::disk('public')->deleteDirectory('invitaciones/'.trim($route, '/'));

        return redirect()
            ->route('admin.dashboard')
            ->with('status', "La invitación de {$name} fue eliminada correctamente.");
    }

    /**
     * Toggle del flag `disponible` en una invitación.
     * Cuando está OFF, /invitacion/{ruta} devuelve 404.
     */
    public function toggleInvitacionDisponible(Invitacion $invitacion): RedirectResponse
    {
        $invitacion->disponible = ! $invitacion->disponible;
        $invitacion->save();

        $msg = $invitacion->disponible
            ? "«{$invitacion->nombre_completo}» ahora está disponible públicamente."
            : "«{$invitacion->nombre_completo}» ya no está disponible públicamente.";

        return back()->with('status', $msg);
    }

    /**
     * Toggle del flag `es_template` en una invitación.
     * Cuando es ON + disponible ON, aparece en el catálogo de
     * templates para asignar a usuarios nuevos.
     */
    public function toggleInvitacionEsTemplate(Invitacion $invitacion): RedirectResponse
    {
        $invitacion->es_template = ! $invitacion->es_template;
        $invitacion->save();

        $msg = $invitacion->es_template
            ? "«{$invitacion->nombre_completo}» ahora es un template del catálogo."
            : "«{$invitacion->nombre_completo}» ya no es un template del catálogo.";

        return back()->with('status', $msg);
    }

    private function uniqueCloneRoute(Invitacion $source, string $fullName): string
    {
        $nameSlug = Str::slug($fullName) ?: 'invitacion';
        $base = str_starts_with($source->ruta, 'xv-') ? 'xv-'.$nameSlug : $nameSlug;
        $route = Str::limit($base, 112, '');
        $suffix = 2;

        while (Invitacion::query()->where('ruta', $route)->exists()) {
            $suffixText = '-'.$suffix++;
            $route = Str::limit($base, 120 - strlen($suffixText), '').$suffixText;
        }

        return $route;
    }

    private function cloneConfigAssets(mixed $value, string $sourceRoute, string $targetRoute): mixed
    {
        if (is_array($value)) {
            return array_map(
                fn (mixed $item) => $this->cloneConfigAssets($item, $sourceRoute, $targetRoute),
                $value,
            );
        }

        return is_string($value)
            ? $this->cloneOwnedAsset($value, $sourceRoute, $targetRoute)
            : $value;
    }

    private function cloneOwnedAsset(?string $path, string $sourceRoute, string $targetRoute): ?string
    {
        if (! is_string($path) || $path === '') {
            return $path;
        }

        $sourceRoute = trim($sourceRoute, '/');
        $targetRoute = trim($targetRoute, '/');
        $publicPrefix = 'uploads/invitaciones/'.$sourceRoute.'/';

        if (str_starts_with($path, $publicPrefix)) {
            $targetPath = 'uploads/invitaciones/'.$targetRoute.'/'.substr($path, strlen($publicPrefix));

            if (! Storage::disk('public_uploads')->exists($path)) {
                return $path;
            }

            Storage::disk('public_uploads')->copy($path, $targetPath);

            return $targetPath;
        }

        $legacyPrefix = 'storage/invitaciones/'.$sourceRoute.'/';

        if (! str_starts_with($path, $legacyPrefix)) {
            return $path;
        }

        $sourceDiskPath = substr($path, strlen('storage/'));
        $targetPath = 'uploads/invitaciones/'.$targetRoute.'/'.substr($path, strlen($legacyPrefix));

        if (! Storage::disk('public')->exists($sourceDiskPath)) {
            return $path;
        }

        Storage::disk('public_uploads')->put(
            $targetPath,
            Storage::disk('public')->get($sourceDiskPath),
        );

        return $targetPath;
    }

}

<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Invitacion;
use App\Models\Template;
use App\Services\SuscripcionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Panel del cliente · gestión de sus invitaciones.
 *
 * Flujo:
 *   1. Cliente ve /panel/invitaciones → lista de las suyas.
 *   2. Click "Nueva" → /panel/invitaciones/nueva → ve templates
 *      que el admin le habilitó.
 *   3. Elige template → POST /panel/invitaciones → crea invitación
 *      en borrador, con template_id y suscripcion_id de su suscripción
 *      activa.
 *   4. Si formato=web: redirige al InvitationEditor (ruta
 *      /admin/invitaciones/{id}/editar).
 *      Si formato=imagen/video: redirige al formulario simple
 *      /panel/invitaciones/{id}/datos.
 *   5. Cuando está conforme → "Publicar" consume cupo de la
 *      suscripción, marca como publicada y setea fecha_caducidad.
 */
class InvitacionController extends Controller
{
    public function __construct(
        private readonly SuscripcionService $suscripciones,
    ) {
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $invitaciones = Invitacion::query()
            ->where('user_id', $user->id)
            ->with('template')
            ->latest('id')
            ->get();

        $suscripcion = $user->suscripcionActiva();

        return view('panel.invitaciones.index', [
            'invitaciones' => $invitaciones,
            'suscripcion'  => $suscripcion,
        ]);
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $suscripcion = $user->suscripcionActiva();

        $templates = Template::query()
            ->visiblesPara($user->id)
            ->get();

        return view('panel.invitaciones.create', [
            'templates'   => $templates,
            'suscripcion' => $suscripcion,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $suscripcion = $user->suscripcionActiva();

        if (! $suscripcion) {
            return back()->withErrors(['template_id' => 'No tienes una suscripción activa. Contacta al administrador.']);
        }

        $data = $request->validate([
            'template_id' => [
                'required', 'integer',
                Rule::exists('templates', 'id')->where('activo', true),
            ],
        ]);

        // Verificar que el template está habilitado para este user
        $templateHabilitado = Template::query()
            ->visiblesPara($user->id)
            ->whereKey($data['template_id'])
            ->exists();

        if (! $templateHabilitado) {
            return back()->withErrors(['template_id' => 'Ese template no está disponible para tu cuenta.']);
        }

        $template = Template::findOrFail($data['template_id']);

        // Generar slug único para la invitación
        $ruta = $this->generarRutaUnica($user->name, $user->id);

        $config = $template->config_json ?? [];

        $invitacion = Invitacion::create([
            'user_id'           => $user->id,
            'suscripcion_id'    => $suscripcion->id,
            'template_id'       => $template->id,
            'template_key'      => $template->slug,
            'ruta'              => $ruta,
            'nombre'            => $user->name,
            'estado'            => 'borrador',
            'titulo'            => $config['titulo_default'] ?? null,
            'color_primario'    => $config['color_primario'] ?? '#5A3087',
            'color_secundario'  => $config['color_secundario'] ?? '#F4EFF8',
            'color_acento'      => $config['color_acento'] ?? '#C9A05A',
        ]);

        // Redirigir según formato del template
        return redirect()->route($this->rutaSiguiente($template), ['invitacion' => $invitacion->id])
            ->with('status', 'Invitación creada. Ya puedes personalizarla.');
    }

    public function datos(Request $request, Invitacion $invitacion): View|RedirectResponse
    {
        $this->asegurar($request, $invitacion);
        abort_unless(in_array($invitacion->template?->formato, ['imagen', 'video'], true), 404);

        return view('panel.invitaciones.datos', [
            'invitacion' => $invitacion,
        ]);
    }

    public function datosStore(Request $request, Invitacion $invitacion): RedirectResponse
    {
        $this->asegurar($request, $invitacion);
        abort_unless(in_array($invitacion->template?->formato, ['imagen', 'video'], true), 404);

        $data = $request->validate([
            'nombre'                 => ['required', 'string', 'max:80'],
            'apellido_paterno'       => ['nullable', 'string', 'max:80'],
            'apellido_materno'       => ['nullable', 'string', 'max:80'],
            'tipo_evento'            => ['nullable', 'string', 'max:60'],
            'titulo'                 => ['nullable', 'string', 'max:160'],
            'subtitulo'              => ['nullable', 'string', 'max:160'],
            'fecha_evento'           => ['nullable', 'date'],
            'hora_evento'            => ['nullable', 'date_format:H:i'],
            'lugar_nombre'           => ['nullable', 'string', 'max:160'],
            'lugar_direccion'        => ['nullable', 'string', 'max:200'],
            'mensaje_principal'      => ['nullable', 'string', 'max:1000'],
            'dress_code'             => ['nullable', 'string', 'max:80'],
            'whatsapp_numero'        => ['nullable', 'string', 'max:40'],
            'whatsapp_mensaje'       => ['nullable', 'string', 'max:160'],
            'notas_cliente'          => ['nullable', 'string', 'max:1000'],
        ]);

        $invitacion->update($data);

        return redirect()
            ->route('panel.invitaciones.index')
            ->with('status', 'Datos guardados. El administrador ya tiene lo que necesita para producir tu invitación.');
    }

    /**
     * Publica la invitación. Valida cupo, marca como publicada y
     * setea fecha_caducidad. Llamado desde la lista de invitaciones.
     */
    public function publish(Request $request, Invitacion $invitacion): RedirectResponse
    {
        $this->asegurar($request, $invitacion);

        $user = $request->user();
        $suscripcion = $user->suscripcionActiva();

        if (! $suscripcion) {
            return back()->withErrors(['publicar' => 'No tienes una suscripción activa.']);
        }

        $resultado = $this->suscripciones->consumirCupoParaPublicar($suscripcion);
        if (! $resultado['ok']) {
            return back()->withErrors(['publicar' => $resultado['mensaje']]);
        }

        $this->suscripciones->publicar($invitacion, $suscripcion);

        return redirect()
            ->route('panel.invitaciones.index')
            ->with('status', "¡Publicada! Tu invitación vence el {$invitacion->fecha_caducidad->format('d/m/Y')}.");
    }

    public function destroy(Request $request, Invitacion $invitacion): RedirectResponse
    {
        $this->asegurar($request, $invitacion);
        abort_if($invitacion->estaPublicada(), 403, 'No puedes borrar una invitación ya publicada.');

        $invitacion->delete();

        return redirect()
            ->route('panel.invitaciones.index')
            ->with('status', 'Invitación eliminada.');
    }

    // ─────────────── helpers ───────────────

    private function asegurar(Request $request, Invitacion $invitacion): void
    {
        abort_unless(
            $request->user()->isAdmin() || $invitacion->user_id === $request->user()->id,
            403
        );
    }

    private function generarRutaUnica(string $base, int $userId): string
    {
        $slug = Str::slug($base) ?: 'invitacion-' . $userId;
        $slug = Str::limit($slug, 80, '');
        $ruta = "{$userId}-{$slug}";
        $suffix = 2;
        while (Invitacion::where('ruta', $ruta)->exists()) {
            $ruta = Str::limit("{$userId}-{$slug}", 80 - strlen((string) $suffix), '') . '-' . $suffix++;
        }
        return $ruta;
    }

    private function rutaSiguiente(Template $template): string
    {
        return match ($template->formato) {
            'imagen', 'video' => 'panel.invitaciones.datos',
            default           => 'admin.invitaciones.edit',
        };
    }
}

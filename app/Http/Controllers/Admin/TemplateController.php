<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Admin · CRUD de Templates.
 *
 * Decisión de producto: NO HAY DELETE. Los templates son parte
 * del catálogo del sistema y no se pueden borrar (la migración de
 * invitaciones lo prohíbe con restrictOnDelete). Solo se puede
 * activar/desactivar. Si quieres "retirar" un template, desactívalo.
 */
class TemplateController extends Controller
{
    public function index(): View
    {
        $templates = Template::query()
            ->withCount('usuarios')
            ->orderBy('formato')
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();

        return view('admin.templates.index', [
            'templates' => $templates,
        ]);
    }

    public function create(): View
    {
        return view('admin.templates.create', [
            'template'  => new Template(['activo' => true, 'formato' => 'web', 'orden' => 0]),
            'formatos'  => $this->formatosPermitidos(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validar($request);

        $template = Template::create($data);

        return redirect()
            ->route('admin.templates.edit', $template)
            ->with('status', 'Template creado correctamente.');
    }

    public function edit(Template $template): View
    {
        return view('admin.templates.edit', [
            'template' => $template,
            'formatos' => $this->formatosPermitidos(),
        ]);
    }

    public function update(Request $request, Template $template): RedirectResponse
    {
        $data = $this->validar($request, $template);

        $template->update($data);

        return redirect()
            ->route('admin.templates.edit', $template)
            ->with('status', 'Template actualizado correctamente.');
    }

    /**
     * Toggle rápido de activo desde la lista. No es delete, es
     * "lo escondo del catálogo" / "lo vuelvo a mostrar".
     */
    public function toggle(Template $template): RedirectResponse
    {
        $template->update(['activo' => ! $template->activo]);

        return redirect()
            ->route('admin.templates.index')
            ->with('status', $template->activo
                ? "Template \"{$template->nombre}\" reactivado."
                : "Template \"{$template->nombre}\" desactivado (ya no aparece a los clientes).");
    }

    // ─────────────── helpers ───────────────

    private function validar(Request $request, ?Template $template = null): array
    {
        $slugUnique = Rule::unique('templates', 'slug')->ignore($template?->id);

        return $request->validate([
            'slug'                 => ['required', 'string', 'max:80', 'regex:/^[a-z0-9-]+$/', $slugUnique],
            'formato'              => ['required', Rule::in(array_keys($this->formatosPermitidos()))],
            'nombre'               => ['required', 'string', 'max:80'],
            'descripcion'          => ['nullable', 'string', 'max:255'],
            'imagen_preview_path'  => ['nullable', 'string', 'max:255'],
            'config_json'          => ['nullable', 'string', 'max:5000'],
            'orden'                => ['nullable', 'integer', 'min:0', 'max:9999'],
            'activo'               => ['nullable', 'boolean'],
        ], [
            'slug.regex'    => 'El slug solo puede tener minúsculas, números y guiones.',
            'slug.unique'   => 'Ya existe un template con ese slug.',
        ]) + [
            'config_json' => $this->normalizarConfig($request->input('config_json')),
            'activo'      => $request->boolean('activo'),
            'orden'       => (int) ($request->input('orden') ?? 0),
        ];
    }

    private function normalizarConfig(mixed $raw): ?array
    {
        if (! filled($raw)) {
            return null;
        }
        $decoded = json_decode((string) $raw, true);
        if (! is_array($decoded)) {
            return null;
        }
        return $decoded;
    }

    private function formatosPermitidos(): array
    {
        return [
            'web'     => 'Web (autoeditable)',
            'imagen'  => 'Imagen (datos → admin produce)',
            'video'   => 'Video (datos → admin produce)',
            'general' => 'General',
        ];
    }
}

<?php

use App\Http\Controllers\ConfirmacionController;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sitemap.xml', function () {
    $lastModified = Carbon::createFromTimestamp(max(
        filemtime(resource_path('views/welcome.blade.php')),
        filemtime(resource_path('views/politicas-de-privacidad.blade.php')),
        filemtime(public_path('images/hero-desktop.png'))
    ))->toAtomString();

    $urls = [
        [
            'loc' => url('/'),
            'lastmod' => $lastModified,
            'changefreq' => 'weekly',
            'priority' => '1.0',
            'image' => asset('images/hero-desktop.png'),
        ],
        [
            'loc' => url('/politicas-de-privacidad'),
            'lastmod' => $lastModified,
            'changefreq' => 'yearly',
            'priority' => '0.3',
        ],
    ];

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->withoutMiddleware([
    ValidateCsrfToken::class,
    PreventRequestForgery::class,
    StartSession::class,
    ShareErrorsFromSession::class,
])->name('sitemap');

// Página legal mínima: privacidad. Es una página estática y ayuda al SEO
// porque la mayoría de los formularios (RSVP, WhatsApp) mencionan datos personales.
Route::view('/politicas-de-privacidad', 'politicas-de-privacidad')
    ->name('politicas');

// Plantillas de invitación (preview). Más adelante se resuelven
// por slug desde un controlador + modelo Invitacion.
Route::view('/invitacion/xv-valentina', 'invitaciones.xv.valeria');

// Confirmación de asistencia — formulario del popup en la invitación.
Route::post('/confirmar-asistencia', [ConfirmacionController::class, 'store'])
    ->name('confirmacion.store');

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
    $lastModified = Carbon::now()->toAtomString();
    $urls = [
        [
            'loc' => url('/'),
            'lastmod' => $lastModified,
            'changefreq' => 'weekly',
            'priority' => '1.0',
        ],
        [
            'loc' => url('/invitacion/xv-valentina'),
            'lastmod' => $lastModified,
            'changefreq' => 'monthly',
            'priority' => '0.6',
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

// Plantillas de invitación (preview). Más adelante se resuelven
// por slug desde un controlador + modelo Invitacion.
Route::view('/invitacion/xv-valentina', 'invitaciones.xv.valeria');

// Confirmación de asistencia — formulario del popup en la invitación.
Route::post('/confirmar-asistencia', [ConfirmacionController::class, 'store'])
    ->name('confirmacion.store');

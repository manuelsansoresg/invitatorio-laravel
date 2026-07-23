<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfirmacionController;
use App\Http\Controllers\ConfirmadosController;
use App\Http\Controllers\InvitacionController;
use App\Livewire\InvitationEditor;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/usuarios', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/invitaciones/{invitacion}/editar', InvitationEditor::class)->name('invitaciones.edit');
    Route::post('/invitaciones/{invitacion}/clonar', [AdminController::class, 'cloneInvitation'])->name('invitaciones.clone');
    Route::patch('/invitaciones/{invitacion}/cliente', [AdminController::class, 'updateInvitationClient'])->name('invitaciones.cliente.update');
    Route::delete('/invitaciones/{invitacion}', [AdminController::class, 'destroyInvitation'])->name('invitaciones.destroy');
});

Route::middleware('auth')->prefix('panel')->name('panel.')->group(function () {
    Route::get('/confirmados', [ConfirmadosController::class, 'index'])->name('confirmados.index');
    Route::get('/confirmados/pdf', [ConfirmadosController::class, 'exportPdf'])->name('confirmados.pdf');
    Route::delete('/confirmados/seleccionados', [ConfirmadosController::class, 'destroySelected'])->name('confirmados.destroy-selected');
    Route::delete('/confirmados/{confirmacion}', [ConfirmadosController::class, 'destroy'])->name('confirmados.destroy');
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

// Invitaciones digitales resueltas por slug desde la tabla invitaciones.
Route::get('/invitacion/{invitacion}', [InvitacionController::class, 'show'])
    ->name('invitaciones.show');

// Confirmación de asistencia — formulario del popup en la invitación.
Route::post('/confirmar-asistencia', [ConfirmacionController::class, 'store'])
    ->name('confirmacion.store');

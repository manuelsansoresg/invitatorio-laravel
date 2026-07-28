<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ConfirmacionController;
use App\Http\Controllers\ConfirmadosController;
use App\Http\Controllers\DiagnoseController;
use App\Http\Controllers\InvitacionController;
use App\Http\Controllers\MercadoPagoCallbackController;
use App\Http\Controllers\Panel\InvitadoController;
use App\Http\Controllers\WebhookController;
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

    /*
    |--------------------------------------------------------------------------
    | Toggle de features por paquete (temporal)
    |--------------------------------------------------------------------------
    | Endpoint auxiliar para activar/desactivar flags de paquetes
    | sin esperar al admin de paquetes completo. Se puede borrar
    | cuando admin/paquetes tenga su propio form.
    */
    Route::post('/paquetes/{paquete}/toggle-gestionar-invitados', [AdminController::class, 'togglePaqueteGestionarInvitados'])
        ->name('paquetes.toggle-gestionar-invitados');
});

Route::middleware('auth')->prefix('panel')->name('panel.')->group(function () {
    Route::get('/confirmados', [ConfirmadosController::class, 'index'])->name('confirmados.index');
    Route::get('/confirmados/pdf', [ConfirmadosController::class, 'exportPdf'])->name('confirmados.pdf');
    Route::delete('/confirmados/seleccionados', [ConfirmadosController::class, 'destroySelected'])->name('confirmados.destroy-selected');
    Route::delete('/confirmados/{confirmacion}', [ConfirmadosController::class, 'destroy'])->name('confirmados.destroy');

    /*
    |--------------------------------------------------------------------------
    | Lista de invitados con link único por invitado
    |--------------------------------------------------------------------------
    | CRUD clásico + bulk add + regenerar token. Anidado bajo la invitación
    | para que la autorización sea por ownership de la invitación, no del
    | invitado suelto.
    */
    Route::get('/invitaciones/{invitacion}/invitados', [InvitadoController::class, 'index'])
        ->name('invitados.index');
    Route::get('/invitaciones/{invitacion}/invitados/crear', [InvitadoController::class, 'create'])
        ->name('invitados.create');
    Route::post('/invitaciones/{invitacion}/invitados', [InvitadoController::class, 'store'])
        ->name('invitados.store');
    Route::post('/invitaciones/{invitacion}/invitados/lista', [InvitadoController::class, 'storeBulk'])
        ->name('invitados.store-bulk');
    Route::get('/invitaciones/{invitacion}/invitados/{invitado}/editar', [InvitadoController::class, 'edit'])
        ->name('invitados.edit');
    Route::put('/invitaciones/{invitacion}/invitados/{invitado}', [InvitadoController::class, 'update'])
        ->name('invitados.update');
    Route::delete('/invitaciones/{invitacion}/invitados/{invitado}', [InvitadoController::class, 'destroy'])
        ->name('invitados.destroy');
    Route::post('/invitaciones/{invitacion}/invitados/{invitado}/regenerar-token', [InvitadoController::class, 'regenerarToken'])
        ->name('invitados.regenerar-token');
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

/*
|--------------------------------------------------------------------------
| Link público de invitado (token único)
|--------------------------------------------------------------------------
| /c/{token} → el invitado ve su nombre y confirma cuántos van.
| No requiere auth. Sin él nadie puede adivinar la URL.
*/
Route::get('/c/{token}', [AsistenciaController::class, 'show'])
    ->name('confirmar.show');
Route::post('/c/{token}', [AsistenciaController::class, 'confirmar'])
    ->name('confirmar.confirmar');
Route::get('/c/{token}/gracias', [AsistenciaController::class, 'gracias'])
    ->name('confirmar.gracias');

/*
|--------------------------------------------------------------------------
| Checkout — compra de paquetes con Mercado Pago
|--------------------------------------------------------------------------
|
| Flujo:
|   /comprar/{paquete}          → form con datos del comprador
|   POST /comprar/{paquete}     → crea la orden + preference → redirige a MP
|   /checkout/success           → MP redirige acá si todo salió bien
|   /checkout/pending           → si el pago quedó en revisión
|   /checkout/failure           → si el usuario canceló o fue rechazado
|
| Nota: el webhook NO vive acá, está en /api/mercadopago/webhook para que
| MP lo pueda llamar sin la sesión de la app.
*/
Route::get('/comprar/{paquete:slug}', [CheckoutController::class, 'show'])
    ->name('checkout.show');
Route::post('/comprar/{paquete:slug}', [CheckoutController::class, 'buy'])
    ->name('checkout.buy');
Route::get('/checkout/success', [CheckoutController::class, 'success'])
    ->name('checkout.success');
Route::get('/checkout/pending', [CheckoutController::class, 'pending'])
    ->name('checkout.pending');
Route::get('/checkout/failure', [CheckoutController::class, 'failure'])
    ->name('checkout.failure');

/*
|--------------------------------------------------------------------------
| Webhook IPN de Mercado Pago
|--------------------------------------------------------------------------
| MP llama esta URL en segundo plano cuando hay un cambio de estado en un
| pago. Se mantiene fuera del grupo web con sesión porque MP no necesita
| cookies, solo confirmar y responder 200.
*/
Route::post('/api/mercadopago/webhook', [WebhookController::class, 'handle'])
    ->withoutMiddleware([
        VerifyCsrfToken::class,
        PreventRequestForgery::class,
    ])
    ->name('mercadopago.webhook');

/*
|--------------------------------------------------------------------------
| Callback OAuth (Mercado Pago Connect)
|--------------------------------------------------------------------------
| URL declarada en el panel de MP. Hoy no usamos OAuth, pero dejamos la
| ruta para que la URL no devuelva 404 si por error alguien la abre.
*/
Route::get('/api/mercadopago/callback', MercadoPagoCallbackController::class)
    ->name('mercadopago.callback');

/*
|--------------------------------------------------------------------------
| Diagnóstico (TEMPORAL — eliminar después de resolver problemas)
|--------------------------------------------------------------------------
| Útil para verificar el estado de BD y rutas en producción sin acceso SSH.
| Devuelve JSON con info de paquetes, conexión a BD, rutas registradas, etc.
*/
Route::get('/api/diagnose', [DiagnoseController::class, 'show'])
    ->name('diagnose.show');

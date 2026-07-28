<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CuponController;
use App\Http\Controllers\Admin\PaqueteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ConfirmacionController;
use App\Http\Controllers\ConfirmadosController;
use App\Http\Controllers\DiagnoseController;
use App\Http\Controllers\InvitacionController;
use App\Http\Controllers\MercadoPagoCallbackController;
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

    // Paquetes: CRUD completo
    Route::get('/paquetes', [PaqueteController::class, 'index'])->name('paquetes.index');
    Route::get('/paquetes/nuevo', [PaqueteController::class, 'create'])->name('paquetes.create');
    Route::post('/paquetes', [PaqueteController::class, 'store'])->name('paquetes.store');
    Route::get('/paquetes/{paquete}/editar', [PaqueteController::class, 'edit'])->name('paquetes.edit');
    Route::put('/paquetes/{paquete}', [PaqueteController::class, 'update'])->name('paquetes.update');
    Route::delete('/paquetes/{paquete}', [PaqueteController::class, 'destroy'])->name('paquetes.destroy');

    // Cupones: CRUD completo (parámetro {cupon} para que no sea {cupone})
    Route::get('/cupones', [CuponController::class, 'index'])->name('cupones.index');
    Route::get('/cupones/nuevo', [CuponController::class, 'create'])->name('cupones.create');
    Route::post('/cupones', [CuponController::class, 'store'])->name('cupones.store');
    Route::get('/cupones/{cupon}/editar', [CuponController::class, 'edit'])->name('cupones.edit');
    Route::put('/cupones/{cupon}', [CuponController::class, 'update'])->name('cupones.update');
    Route::delete('/cupones/{cupon}', [CuponController::class, 'destroy'])->name('cupones.destroy');
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

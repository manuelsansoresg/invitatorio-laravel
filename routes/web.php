<?php

use App\Http\Controllers\ConfirmacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Plantillas de invitación (preview). Más adelante se resuelven
// por slug desde un controlador + modelo Invitacion.
Route::view('/invitacion/xv-valentina', 'invitaciones.xv.valeria');

// Confirmación de asistencia — formulario del popup en la invitación.
Route::post('/confirmar-asistencia', [ConfirmacionController::class, 'store'])
    ->name('confirmacion.store');

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Plantillas de invitación (preview). Más adelante se resuelven
// por slug desde un controlador + modelo Invitacion.
Route::view('/invitacion/xv-valeria', 'invitaciones.xv.valeria');

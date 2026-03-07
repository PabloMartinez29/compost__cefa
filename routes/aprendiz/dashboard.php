<?php

// Rutas Aprendiz — Dashboard

use App\Http\Controllers\AprendizController;

Route::get('/aprendiz/dashboard', [AprendizController::class, 'index'])->name('aprendiz.dashboard');

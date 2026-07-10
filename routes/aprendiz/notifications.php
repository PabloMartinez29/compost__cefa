<?php

// Rutas Aprendiz — Notificaciones (historial + marcar leída)

use App\Http\Controllers\AprendizController;

Route::get('aprendiz/notifications/history', [AprendizController::class, 'notificationsHistory'])->name('aprendiz.notifications.history');
Route::post('aprendiz/notifications/{notification}/mark-read', [AprendizController::class, 'markNotificationAsRead'])->name('aprendiz.notifications.mark-read');

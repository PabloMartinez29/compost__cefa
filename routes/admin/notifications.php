<?php

// Rutas Admin — Notificaciones (historial, aprobar, rechazar, marcar leída)

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\NotificationController;

Route::get('admin/notifications/history', [AdminController::class, 'notificationsHistory'])->name('admin.notifications.history');
Route::post('admin/notifications/{notification}/approve', [AdminController::class, 'approveNotification'])->name('admin.notifications.approve');
Route::post('admin/notifications/{notification}/reject', [AdminController::class, 'rejectNotification'])->name('admin.notifications.reject');
Route::post('admin/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');

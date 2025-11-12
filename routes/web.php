<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\AprendizController;
use App\Http\Middleware\SetLocale; 

use App\Http\Controllers\FertilizerController;
use App\Http\Controllers\Admin\OrganicController;
use App\Http\Controllers\Aprendiz\OrganicController as AprendizOrganicController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Aprendiz\WarehouseController as AprendizWarehouseController;
use App\Http\Controllers\Aprendiz\CompostingController;
use App\Http\Controllers\Admin\CompostingController as AdminCompostingController;
use App\Http\Controllers\Aprendiz\TrackingController;
use App\Http\Controllers\Admin\TrackingController as AdminTrackingController;

Route::get('/', function () {
    return view('welcome');
})->middleware(SetLocale::class);

// Ruta general del dashboard que redirige según el rol
Route::get('/dashboard', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard.admin');
        } else {
            return redirect()->route('aprendiz.dashboard');
        }
    }
    return redirect()->route('login');
})->middleware(['auth']);

//Ruta de admin
Route::middleware(['auth','role:admin'])->group(function(){

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('dashboard.admin');
    
    // Monitoring Routes
    Route::get('admin/monitoring', [\App\Http\Controllers\Admin\MonitoringController::class, 'index'])->name('admin.monitoring.index');
    Route::get('admin/monitoring/download/pdf', [\App\Http\Controllers\Admin\MonitoringController::class, 'downloadMonitoringPDF'])->name('admin.monitoring.download.pdf');
    Route::get('admin/monitoring/download/excel', [\App\Http\Controllers\Admin\MonitoringController::class, 'downloadMonitoringExcel'])->name('admin.monitoring.download.excel');
    
    // Organic Waste Management Routes
    Route::resource('admin/organic', OrganicController::class)->names([
        'index' => 'admin.organic.index',
        'create' => 'admin.organic.create',
        'store' => 'admin.organic.store',
        'show' => 'admin.organic.show',
        'edit' => 'admin.organic.edit',
        'update' => 'admin.organic.update',
        'destroy' => 'admin.organic.destroy',
    ]);

    // Warehouse Classification Routes
    Route::get('admin/warehouse', [WarehouseController::class, 'index'])->name('admin.warehouse.index');
    Route::get('admin/warehouse/{warehouse}', [WarehouseController::class, 'show'])->name('admin.warehouse.show');
    Route::get('admin/warehouse/inventory/{type}', [WarehouseController::class, 'inventory'])->name('admin.warehouse.inventory');
    
    // Notification Routes
    Route::get('admin/notifications/history', [AdminController::class, 'notificationsHistory'])->name('admin.notifications.history');
    Route::post('admin/notifications/{notification}/approve', [AdminController::class, 'approveNotification'])->name('admin.notifications.approve');
    Route::post('admin/notifications/{notification}/reject', [AdminController::class, 'rejectNotification'])->name('admin.notifications.reject');
    
    // Composting Routes for Admin
    Route::resource('admin/composting', AdminCompostingController::class)->names([
        'index' => 'admin.composting.index',
        'create' => 'admin.composting.create',
        'store' => 'admin.composting.store',
        'show' => 'admin.composting.show',
        'edit' => 'admin.composting.edit',
        'update' => 'admin.composting.update',
        'destroy' => 'admin.composting.destroy',
    ]);

    // Tracking Routes for Admin
    Route::resource('admin/tracking', AdminTrackingController::class)->names([
        'index' => 'admin.tracking.index',
        'create' => 'admin.tracking.create',
        'store' => 'admin.tracking.store',
        'show' => 'admin.tracking.show',
        'edit' => 'admin.tracking.edit',
        'update' => 'admin.tracking.update',
        'destroy' => 'admin.tracking.destroy',
    ]);

    // Additional tracking routes for admin
    Route::get('admin/tracking/composting/{composting}', [AdminTrackingController::class, 'getByComposting'])->name('admin.tracking.by-composting');
    
    // User Management Routes
    Route::resource('admin/users', \App\Http\Controllers\Admin\UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
    
    // Ruta específica para obtener datos de usuario en JSON
    Route::get('admin/users/{user}/data', [\App\Http\Controllers\Admin\UserController::class, 'getUserData'])->name('admin.users.data');
    
    // Rutas para descargar PDFs
    Route::get('admin/users/download/all-pdf', [\App\Http\Controllers\Admin\UserController::class, 'downloadAllUsersPDF'])->name('admin.users.download.all-pdf');
    Route::get('admin/users/{user}/download/pdf', [\App\Http\Controllers\Admin\UserController::class, 'downloadUserPDF'])->name('admin.users.download.pdf');
    
    // Rutas para descargar PDFs de residuos orgánicos
    Route::get('admin/organic/download/all-pdf', [OrganicController::class, 'downloadAllOrganicsPDF'])->name('admin.organic.download.all-pdf');
    Route::get('admin/organic/{organic}/download/pdf', [OrganicController::class, 'downloadOrganicPDF'])->name('admin.organic.download.pdf');
    
    // Rutas para descargar PDFs de abonos
    Route::get('admin/fertilizer/download/all-pdf', [\App\Http\Controllers\Admin\FertilizerController::class, 'downloadAllFertilizersPDF'])->name('admin.fertilizer.download.all-pdf');
    Route::get('admin/fertilizer/{fertilizer}/download/pdf', [\App\Http\Controllers\Admin\FertilizerController::class, 'downloadFertilizerPDF'])->name('admin.fertilizer.download.pdf');
    
    // Ruta de prueba para PDF
    Route::get('admin/test-pdf', function() {
        return view('admin.users.pdf.simple-test');
    });
    
    // Ruta de prueba para PDF con DomPDF
    Route::get('admin/test-dompdf', function() {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.users.pdf.simple-test')
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('test_dompdf_' . date('Y-m-d') . '.pdf');
    });
});


//Ruta de aprendiz
Route::middleware(['auth', 'role:aprendiz'])->group(function(){
    Route::get('/aprendiz/dashboard', [AprendizController::class, 'index'])->name('aprendiz.dashboard');
    
    // Organic Waste Management Routes for Apprentices
    Route::resource('aprendiz/organic', AprendizOrganicController::class)->names([
        'index' => 'aprendiz.organic.index',
        'create' => 'aprendiz.organic.create',
        'store' => 'aprendiz.organic.store',
        'show' => 'aprendiz.organic.show',
        'edit' => 'aprendiz.organic.edit',
        'update' => 'aprendiz.organic.update',
        'destroy' => 'aprendiz.organic.destroy',
    ]);
    
    // Additional routes for apprentice permissions
    Route::post('aprendiz/organic/{organic}/request-delete', [AprendizOrganicController::class, 'requestDeletePermission'])->name('aprendiz.organic.request-delete');
    Route::post('aprendiz/organic/{organic}/request-edit', [AprendizOrganicController::class, 'requestEditPermission'])->name('aprendiz.organic.request-edit');
    
    // Rutas para descargar PDFs de residuos orgánicos para aprendiz
    Route::get('aprendiz/organic/download/all-pdf', [AprendizOrganicController::class, 'downloadAllOrganicsPDF'])->name('aprendiz.organic.download.all-pdf');
    Route::get('aprendiz/organic/{organic}/download/pdf', [AprendizOrganicController::class, 'downloadOrganicPDF'])->name('aprendiz.organic.download.pdf');
    
    // Notification routes for apprentice
    Route::get('aprendiz/notifications/history', [AprendizController::class, 'notificationsHistory'])->name('aprendiz.notifications.history');
    Route::post('aprendiz/notifications/{notification}/mark-read', [AprendizController::class, 'markNotificationAsRead'])->name('aprendiz.notifications.mark-read');

    // Warehouse Classification Routes for Apprentices
    Route::get('aprendiz/warehouse', [AprendizWarehouseController::class, 'index'])->name('aprendiz.warehouse.index');
    Route::get('aprendiz/warehouse/{type}', [AprendizWarehouseController::class, 'inventory'])->name('aprendiz.warehouse.inventory');
    
          // Composting Routes for Apprentices
          Route::resource('aprendiz/composting', CompostingController::class)->names([
              'index' => 'aprendiz.composting.index',
              'create' => 'aprendiz.composting.create',
              'store' => 'aprendiz.composting.store',
              'show' => 'aprendiz.composting.show',
              'edit' => 'aprendiz.composting.edit',
              'update' => 'aprendiz.composting.update',
              'destroy' => 'aprendiz.composting.destroy',
          ]);
          
          // Permission request routes for composting
          Route::post('aprendiz/composting/{composting}/request-edit', [CompostingController::class, 'requestEditPermission'])->name('aprendiz.composting.request-edit');
          Route::post('aprendiz/composting/{composting}/request-delete', [CompostingController::class, 'requestDeletePermission'])->name('aprendiz.composting.request-delete');
          Route::get('aprendiz/composting/{composting}/check-delete-status', [CompostingController::class, 'checkDeletePermissionStatus'])->name('aprendiz.composting.check-delete-status');
          
          // Tracking Routes for Apprentices
          Route::resource('aprendiz/tracking', TrackingController::class)->names([
              'index' => 'aprendiz.tracking.index',
              'create' => 'aprendiz.tracking.create',
              'store' => 'aprendiz.tracking.store',
              'show' => 'aprendiz.tracking.show',
              'edit' => 'aprendiz.tracking.edit',
              'update' => 'aprendiz.tracking.update',
              'destroy' => 'aprendiz.tracking.destroy',
          ]);
          
          // Additional tracking routes
          Route::get('aprendiz/tracking/composting/{composting}', [TrackingController::class, 'getByComposting'])->name('aprendiz.tracking.by-composting');
          
          // Fertilizer Routes for Apprentices
          Route::resource('aprendiz/fertilizer', \App\Http\Controllers\Aprendiz\FertilizerController::class)->names([
              'index' => 'aprendiz.fertilizer.index',
              'create' => 'aprendiz.fertilizer.create',
              'store' => 'aprendiz.fertilizer.store',
              'show' => 'aprendiz.fertilizer.show',
              'edit' => 'aprendiz.fertilizer.edit',
              'update' => 'aprendiz.fertilizer.update',
              'destroy' => 'aprendiz.fertilizer.destroy',
          ]);
          
          // Rutas para descargar PDFs de abonos para aprendiz
          Route::get('aprendiz/fertilizer/download/all-pdf', [\App\Http\Controllers\Aprendiz\FertilizerController::class, 'downloadAllFertilizersPDF'])->name('aprendiz.fertilizer.download.all-pdf');
          Route::get('aprendiz/fertilizer/{fertilizer}/download/pdf', [\App\Http\Controllers\Aprendiz\FertilizerController::class, 'downloadFertilizerPDF'])->name('aprendiz.fertilizer.download.pdf');
});

//rutas de abono (fertilizer)
Route::resource('admin/fertilizer', \App\Http\Controllers\Admin\FertilizerController::class)->names([
    'index' => 'admin.fertilizer.index',
    'create' => 'admin.fertilizer.create',
    'store' => 'admin.fertilizer.store',
    'show' => 'admin.fertilizer.show',
    'edit' => 'admin.fertilizer.edit',
    'update' => 'admin.fertilizer.update',
    'destroy' => 'admin.fertilizer.destroy',
]);

require __DIR__.'/auth.php';



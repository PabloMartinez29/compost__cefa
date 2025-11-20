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
use App\Http\Controllers\Admin\MachineryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\UsageControlController;
use App\Http\Controllers\Aprendiz\MachineryController as AprendizMachineryController;
use App\Http\Controllers\Aprendiz\SupplierController as AprendizSupplierController;
use App\Http\Controllers\Aprendiz\MaintenanceController as AprendizMaintenanceController;
use App\Http\Controllers\Aprendiz\UsageControlController as AprendizUsageControlController;

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
    Route::post('admin/notifications/{notification}/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');
    
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
    
    // PDF Routes for Composting (Admin)
    Route::get('admin/composting/download/all-pdf', [AdminCompostingController::class, 'downloadAllCompostingsPDF'])->name('admin.composting.download.all-pdf');
    Route::get('admin/composting/{composting}/download/pdf', [AdminCompostingController::class, 'downloadCompostingPDF'])->name('admin.composting.download.pdf');

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
    
    // PDF Routes for Tracking (Admin)
    Route::get('admin/tracking/download/all-pdf', [AdminTrackingController::class, 'downloadAllTrackingsPDF'])->name('admin.tracking.download.all-pdf');
    Route::get('admin/tracking/{tracking}/download/pdf', [AdminTrackingController::class, 'downloadTrackingPDF'])->name('admin.tracking.download.pdf');
    
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
    
    // Machinery Routes - Identificación y Especificaciones
    Route::resource('admin/machinery/machineries', MachineryController::class)->names([
        'index' => 'admin.machinery.index',
        'create' => 'admin.machinery.create',
        'store' => 'admin.machinery.store',
        'show' => 'admin.machinery.show',
        'edit' => 'admin.machinery.edit',
        'update' => 'admin.machinery.update',
        'destroy' => 'admin.machinery.destroy',
    ]);
    
    // PDF Routes for Machinery
    Route::get('admin/machinery/machineries/download/all-pdf', [MachineryController::class, 'downloadAllMachineriesPDF'])->name('admin.machinery.download.all-pdf');
    Route::get('admin/machinery/machineries/{machinery}/download/pdf', [MachineryController::class, 'downloadMachineryPDF'])->name('admin.machinery.download.pdf');
    
    // Supplier Routes - Deben ir ANTES de las rutas con parámetros {machinery} para evitar conflictos
    Route::get('admin/machinery/supplier', [SupplierController::class, 'index'])->name('admin.machinery.supplier.index');
    Route::get('admin/machinery/supplier/create', [SupplierController::class, 'create'])->name('admin.machinery.supplier.create');
    Route::post('admin/machinery/supplier', [SupplierController::class, 'store'])->name('admin.machinery.supplier.store');
    Route::get('admin/machinery/supplier/{supplier}', [SupplierController::class, 'show'])->name('admin.machinery.supplier.show');
    Route::get('admin/machinery/supplier/{supplier}/edit', [SupplierController::class, 'edit'])->name('admin.machinery.supplier.edit');
    Route::put('admin/machinery/supplier/{supplier}', [SupplierController::class, 'update'])->name('admin.machinery.supplier.update');
    Route::delete('admin/machinery/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('admin.machinery.supplier.destroy');
    
    // PDF Routes for Suppliers
    Route::get('admin/machinery/supplier/download/all-pdf', [SupplierController::class, 'downloadAllSuppliersPDF'])->name('admin.machinery.supplier.download.all-pdf');
    Route::get('admin/machinery/supplier/{supplier}/download/pdf', [SupplierController::class, 'downloadSupplierPDF'])->name('admin.machinery.supplier.download.pdf');
    
    // Maintenance Routes - Deben ir DESPUÉS de las rutas de supplier
    Route::get('admin/machinery/maintenance', [MaintenanceController::class, 'index'])->name('admin.machinery.maintenance.index');
    Route::get('admin/machinery/maintenance/create', [MaintenanceController::class, 'create'])->name('admin.machinery.maintenance.create');
    Route::post('admin/machinery/maintenance', [MaintenanceController::class, 'store'])->name('admin.machinery.maintenance.store');
    Route::get('admin/machinery/maintenance/{maintenance}', [MaintenanceController::class, 'show'])->name('admin.machinery.maintenance.show');
    Route::get('admin/machinery/maintenance/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('admin.machinery.maintenance.edit');
    Route::put('admin/machinery/maintenance/{maintenance}', [MaintenanceController::class, 'update'])->name('admin.machinery.maintenance.update');
    Route::delete('admin/machinery/maintenance/{maintenance}', [MaintenanceController::class, 'destroy'])->name('admin.machinery.maintenance.destroy');
    
    // PDF Routes for Maintenances
    Route::get('admin/machinery/maintenance/download/all-pdf', [MaintenanceController::class, 'downloadAllMaintenancesPDF'])->name('admin.machinery.maintenance.download.all-pdf');
    Route::get('admin/machinery/maintenance/{maintenance}/download/pdf', [MaintenanceController::class, 'downloadMaintenancePDF'])->name('admin.machinery.maintenance.download.pdf');
    
    // Usage Control Routes
    Route::get('admin/machinery/usage-control', [UsageControlController::class, 'index'])->name('admin.machinery.usage-control.index');
    Route::get('admin/machinery/usage-control/create', [UsageControlController::class, 'create'])->name('admin.machinery.usage-control.create');
    Route::post('admin/machinery/usage-control', [UsageControlController::class, 'store'])->name('admin.machinery.usage-control.store');
    Route::get('admin/machinery/usage-control/{usageControl}', [UsageControlController::class, 'show'])->name('admin.machinery.usage-control.show');
    Route::get('admin/machinery/usage-control/{usageControl}/edit', [UsageControlController::class, 'edit'])->name('admin.machinery.usage-control.edit');
    Route::put('admin/machinery/usage-control/{usageControl}', [UsageControlController::class, 'update'])->name('admin.machinery.usage-control.update');
    Route::delete('admin/machinery/usage-control/{usageControl}', [UsageControlController::class, 'destroy'])->name('admin.machinery.usage-control.destroy');
    
    // PDF Routes for Usage Controls
    Route::get('admin/machinery/usage-control/download/all-pdf', [UsageControlController::class, 'downloadAllUsageControlsPDF'])->name('admin.machinery.usage-control.download.all-pdf');
    Route::get('admin/machinery/usage-control/{usageControl}/download/pdf', [UsageControlController::class, 'downloadUsageControlPDF'])->name('admin.machinery.usage-control.download.pdf');
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
          
          // PDF Routes for Composting (Apprentices)
          Route::get('aprendiz/composting/download/all-pdf', [CompostingController::class, 'downloadAllCompostingsPDF'])->name('aprendiz.composting.download.all-pdf');
          Route::get('aprendiz/composting/{composting}/download/pdf', [CompostingController::class, 'downloadCompostingPDF'])->name('aprendiz.composting.download.pdf');
          
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
          
          // PDF Routes for Tracking (Apprentices)
          Route::get('aprendiz/tracking/download/all-pdf', [TrackingController::class, 'downloadAllTrackingsPDF'])->name('aprendiz.tracking.download.all-pdf');
          Route::get('aprendiz/tracking/{tracking}/download/pdf', [TrackingController::class, 'downloadTrackingPDF'])->name('aprendiz.tracking.download.pdf');
          
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
          
          // Machinery Routes for Apprentices - Identificación y Especificaciones
          Route::resource('aprendiz/machinery/machineries', AprendizMachineryController::class)->names([
              'index' => 'aprendiz.machinery.index',
              'create' => 'aprendiz.machinery.create',
              'store' => 'aprendiz.machinery.store',
              'show' => 'aprendiz.machinery.show',
              'edit' => 'aprendiz.machinery.edit',
              'update' => 'aprendiz.machinery.update',
              'destroy' => 'aprendiz.machinery.destroy',
          ]);
          
          // Permission request routes for machinery
          Route::post('aprendiz/machinery/machineries/{machinery}/request-delete', [AprendizMachineryController::class, 'requestDeletePermission'])->name('aprendiz.machinery.request-delete');
          Route::get('aprendiz/machinery/machineries/{machinery}/check-delete-status', [AprendizMachineryController::class, 'checkDeletePermissionStatus'])->name('aprendiz.machinery.check-delete-status');
          
          // PDF Routes for Machinery (Apprentices)
          Route::get('aprendiz/machinery/machineries/download/all-pdf', [AprendizMachineryController::class, 'downloadAllMachineriesPDF'])->name('aprendiz.machinery.download.all-pdf');
          Route::get('aprendiz/machinery/machineries/{machinery}/download/pdf', [AprendizMachineryController::class, 'downloadMachineryPDF'])->name('aprendiz.machinery.download.pdf');
          
          // Supplier Routes for Apprentices
          Route::get('aprendiz/machinery/supplier', [AprendizSupplierController::class, 'index'])->name('aprendiz.machinery.supplier.index');
          Route::get('aprendiz/machinery/supplier/create', [AprendizSupplierController::class, 'create'])->name('aprendiz.machinery.supplier.create');
          Route::post('aprendiz/machinery/supplier', [AprendizSupplierController::class, 'store'])->name('aprendiz.machinery.supplier.store');
          Route::get('aprendiz/machinery/supplier/{supplier}', [AprendizSupplierController::class, 'show'])->name('aprendiz.machinery.supplier.show');
          Route::get('aprendiz/machinery/supplier/{supplier}/edit', [AprendizSupplierController::class, 'edit'])->name('aprendiz.machinery.supplier.edit');
          Route::put('aprendiz/machinery/supplier/{supplier}', [AprendizSupplierController::class, 'update'])->name('aprendiz.machinery.supplier.update');
          Route::delete('aprendiz/machinery/supplier/{supplier}', [AprendizSupplierController::class, 'destroy'])->name('aprendiz.machinery.supplier.destroy');
          
          // Permission request routes for suppliers
          Route::post('aprendiz/machinery/supplier/{supplier}/request-delete', [AprendizSupplierController::class, 'requestDeletePermission'])->name('aprendiz.machinery.supplier.request-delete');
          Route::get('aprendiz/machinery/supplier/{supplier}/check-delete-status', [AprendizSupplierController::class, 'checkDeletePermissionStatus'])->name('aprendiz.machinery.supplier.check-delete-status');
          
          // PDF Routes for Suppliers (Apprentices)
          Route::get('aprendiz/machinery/supplier/download/all-pdf', [AprendizSupplierController::class, 'downloadAllSuppliersPDF'])->name('aprendiz.machinery.supplier.download.all-pdf');
          Route::get('aprendiz/machinery/supplier/{supplier}/download/pdf', [AprendizSupplierController::class, 'downloadSupplierPDF'])->name('aprendiz.machinery.supplier.download.pdf');
          
          // Maintenance Routes for Apprentices
          Route::get('aprendiz/machinery/maintenance', [AprendizMaintenanceController::class, 'index'])->name('aprendiz.machinery.maintenance.index');
          Route::get('aprendiz/machinery/maintenance/create', [AprendizMaintenanceController::class, 'create'])->name('aprendiz.machinery.maintenance.create');
          Route::post('aprendiz/machinery/maintenance', [AprendizMaintenanceController::class, 'store'])->name('aprendiz.machinery.maintenance.store');
          Route::get('aprendiz/machinery/maintenance/{maintenance}', [AprendizMaintenanceController::class, 'show'])->name('aprendiz.machinery.maintenance.show');
          Route::get('aprendiz/machinery/maintenance/{maintenance}/edit', [AprendizMaintenanceController::class, 'edit'])->name('aprendiz.machinery.maintenance.edit');
          Route::put('aprendiz/machinery/maintenance/{maintenance}', [AprendizMaintenanceController::class, 'update'])->name('aprendiz.machinery.maintenance.update');
          Route::delete('aprendiz/machinery/maintenance/{maintenance}', [AprendizMaintenanceController::class, 'destroy'])->name('aprendiz.machinery.maintenance.destroy');
          
          // Permission request routes for maintenances
          Route::post('aprendiz/machinery/maintenance/{maintenance}/request-delete', [AprendizMaintenanceController::class, 'requestDeletePermission'])->name('aprendiz.machinery.maintenance.request-delete');
          Route::get('aprendiz/machinery/maintenance/{maintenance}/check-delete-status', [AprendizMaintenanceController::class, 'checkDeletePermissionStatus'])->name('aprendiz.machinery.maintenance.check-delete-status');
          
          // PDF Routes for Maintenances (Apprentices)
          Route::get('aprendiz/machinery/maintenance/download/all-pdf', [AprendizMaintenanceController::class, 'downloadAllMaintenancesPDF'])->name('aprendiz.machinery.maintenance.download.all-pdf');
          Route::get('aprendiz/machinery/maintenance/{maintenance}/download/pdf', [AprendizMaintenanceController::class, 'downloadMaintenancePDF'])->name('aprendiz.machinery.maintenance.download.pdf');
          
          // Usage Control Routes for Apprentices
          Route::get('aprendiz/machinery/usage-control', [AprendizUsageControlController::class, 'index'])->name('aprendiz.machinery.usage-control.index');
          Route::get('aprendiz/machinery/usage-control/create', [AprendizUsageControlController::class, 'create'])->name('aprendiz.machinery.usage-control.create');
          Route::post('aprendiz/machinery/usage-control', [AprendizUsageControlController::class, 'store'])->name('aprendiz.machinery.usage-control.store');
          Route::get('aprendiz/machinery/usage-control/{usageControl}', [AprendizUsageControlController::class, 'show'])->name('aprendiz.machinery.usage-control.show');
          Route::get('aprendiz/machinery/usage-control/{usageControl}/edit', [AprendizUsageControlController::class, 'edit'])->name('aprendiz.machinery.usage-control.edit');
          Route::put('aprendiz/machinery/usage-control/{usageControl}', [AprendizUsageControlController::class, 'update'])->name('aprendiz.machinery.usage-control.update');
          Route::delete('aprendiz/machinery/usage-control/{usageControl}', [AprendizUsageControlController::class, 'destroy'])->name('aprendiz.machinery.usage-control.destroy');
          
          // Permission request routes for usage controls
          Route::post('aprendiz/machinery/usage-control/{usageControl}/request-delete', [AprendizUsageControlController::class, 'requestDeletePermission'])->name('aprendiz.machinery.usage-control.request-delete');
          Route::get('aprendiz/machinery/usage-control/{usageControl}/check-delete-status', [AprendizUsageControlController::class, 'checkDeletePermissionStatus'])->name('aprendiz.machinery.usage-control.check-delete-status');
          
          // PDF Routes for Usage Controls (Apprentices)
          Route::get('aprendiz/machinery/usage-control/download/all-pdf', [AprendizUsageControlController::class, 'downloadAllUsageControlsPDF'])->name('aprendiz.machinery.usage-control.download.all-pdf');
          Route::get('aprendiz/machinery/usage-control/{usageControl}/download/pdf', [AprendizUsageControlController::class, 'downloadUsageControlPDF'])->name('aprendiz.machinery.usage-control.download.pdf');
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



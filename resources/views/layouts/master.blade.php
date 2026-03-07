<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#16a34a">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Compostaje</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo-compost-cefa.webp') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'soft-green': {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        'soft-gray': {
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            200: '#e5e7eb',
                            300: '#d1d5db',
                            400: '#9ca3af',
                            500: '#6b7280',
                            600: '#4b5563',
                            700: '#374151',
                            800: '#1f2937',
                            900: '#111827',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-transition { transition: all 0.3s ease-in-out; }
        .content-transition { transition: all 0.3s ease-in-out; }
        .hover-lift { transition: transform 0.2s ease-in-out; }
        .hover-lift:hover { transform: translateY(-2px); }
        
        /* Animaciones para el submenú */
        .submenu-container {
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .submenu-hidden {
            max-height: 0;
            opacity: 0;
            transform: translateY(-10px);
        }
        
        .submenu-visible {
            max-height: 300px;
            opacity: 1;
            transform: translateY(0);
        }
        
        .submenu-item {
            transition: all 0.2s ease-in-out;
        }
        
        .submenu-container.submenu-hidden .submenu-item {
            transform: translateX(-10px);
            opacity: 0;
            display: none;
        }
        
        .submenu-container.submenu-visible .submenu-item {
            transform: translateX(0);
            opacity: 1;
            display: block;
        }
        
        .submenu-item.animate-in {
            transform: translateX(0);
            opacity: 1;
        }
        
        .arrow-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Estilos para el scrollbar del sidebar */
        nav::-webkit-scrollbar {
            width: 6px;
        }
        
        nav::-webkit-scrollbar-track {
            background: transparent;
        }
        
        nav::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 3px;
        }
        
        nav::-webkit-scrollbar-thumb:hover {
            background-color: #9ca3af;
        }

        /* Firefox scrollbar */
        nav {
            scrollbar-width: thin;
            scrollbar-color: #d1d5db transparent;
        }

        [x-cloak] { display: none !important; }
        
        /* Responsive Tables */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            scrollbar-width: thin;
        }
        
        .table-responsive table {
            min-width: 600px;
        }
        
        @media (max-width: 640px) {
            .table-responsive table {
                min-width: 100%;
                font-size: 0.875rem;
            }
            
            .table-responsive th,
            .table-responsive td {
                padding: 0.5rem;
                white-space: nowrap;
            }
        }
        
        /* Responsive Cards */
        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr !important;
            }
            
            .grid-cols-2 {
                grid-template-columns: 1fr !important;
            }
            
            .grid-cols-3 {
                grid-template-columns: 1fr !important;
            }
            
            .grid-cols-4 {
                grid-template-columns: 1fr !important;
            }
        }
        
        @media (min-width: 641px) and (max-width: 1024px) {
            .grid-cols-4 {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
        
        /* Responsive Header */
        @media (max-width: 640px) {
            header {
                padding: 0.5rem 1rem;
            }
            
            header h1 {
                font-size: 1.25rem;
            }
            
            header .text-2xl {
                font-size: 1.125rem;
            }
        }
        
        /* Responsive Content Padding */
        @media (max-width: 640px) {
            main {
                padding: 0.75rem !important;
            }
        }
        
        /* Responsive Buttons */
        @media (max-width: 640px) {
            .btn-group {
                flex-direction: column;
                width: 100%;
            }
            
            .btn-group button,
            .btn-group a {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>

<body class="bg-soft-gray-50 font-sans" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden" x-cloak></div>

        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-64 sm:w-72 bg-white shadow-lg transition duration-300 transform lg:translate-x-0 lg:static lg:inset-0 flex flex-col h-screen overflow-hidden">
            <!-- Logo/Brand (si no existe img, se muestra fallback COMPOST CEFA) -->
            <div class="h-32 flex items-center justify-center border-b border-soft-gray-200 px-4 flex-shrink-0">
                <img src="{{ asset('img/logo-compost-cefa.webp') }}" alt="COMPOST CEFA" class="h-28 w-auto max-w-full object-contain logo-img" onerror="this.classList.add('!hidden'); var fb = this.nextElementSibling; if(fb) { fb.classList.remove('hidden'); fb.style.display = 'flex'; }">
                <div class="h-28 hidden items-center justify-center gap-2 text-soft-green-700 font-bold text-lg logo-fallback" style="display: none;">
                    <i class="fas fa-seedling text-2xl"></i>
                    <span>COMPOST CEFA</span>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-6 px-4 flex-1 overflow-y-auto overflow-x-hidden" style="scrollbar-width: thin; scrollbar-color: #d1d5db transparent;">
                <div class="space-y-2">
                    @php
                        $currentRoute = Route::currentRouteName();
                        $isDashboard = $currentRoute === 'dashboard.admin';
                        $isUsers = str_starts_with($currentRoute, 'admin.users');
                        $isMonitoring = str_starts_with($currentRoute, 'admin.monitoring');
                        $isOrganic = str_starts_with($currentRoute, 'admin.organic');
                        $isWarehouse = str_starts_with($currentRoute, 'admin.warehouse');
                        $isComposting = str_starts_with($currentRoute, 'admin.composting');
                        $isTracking = str_starts_with($currentRoute, 'admin.tracking');
                        $isFertilizer = str_starts_with($currentRoute, 'admin.fertilizer');
                        $isMachinery = str_starts_with($currentRoute, 'admin.machinery');
                    @endphp
                    
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard.admin') }}" class="flex items-center space-x-3 px-4 py-3 {{ $isDashboard ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-xl transition-all duration-200 group">
                        <i class="fas fa-globe w-5 text-center {{ $isDashboard ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    
                    <!-- Gestión de Usuarios -->
                    <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ $isUsers ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-xl transition-all duration-200 group">
                        <i class="fas fa-users w-5 text-center {{ $isUsers ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                        <span class="font-medium">Gestión de Usuarios</span>
                    </a>
                    
                    <!-- Monitoreo -->
                    <a href="{{ route('admin.monitoring.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ $isMonitoring ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-xl transition-all duration-200 group">
                        <i class="fas fa-chart-line w-5 text-center {{ $isMonitoring ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                        <span class="font-medium">Monitoreo</span>
                    </a>
                    
                    <!-- Residuos Orgánicos -->
                    <div class="relative">
                        <button onclick="toggleSubmenu('organicSubmenu', 'organicArrow')" 
                            class="w-full flex items-center justify-between px-4 py-3 {{ $isOrganic ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-xl transition-all duration-200 group">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-recycle w-5 text-center {{ $isOrganic ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span class="font-medium">Residuos</span>
                            </div>
                            <i id="organicArrow" class="fas fa-chevron-down text-soft-gray-400 text-xs arrow-transition {{ $isOrganic ? 'rotate-180' : '' }}"></i>
                        </button>

                        <!-- Submenú con animaciones -->
                        <div id="organicSubmenu" class="submenu-container {{ $isOrganic ? 'submenu-visible' : 'submenu-hidden' }} ml-10 mt-2 space-y-2">
                            <a href="{{ route('admin.organic.index') }}" 
                               class="submenu-item flex items-center space-x-3 px-4 py-2 {{ $currentRoute === 'admin.organic.index' ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg font-medium">
                                <i class="fas fa-list w-4 text-center {{ $currentRoute === 'admin.organic.index' ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span>Ver Registros</span>
                            </a>
                            <a href="{{ route('admin.organic.create') }}" 
                               class="submenu-item flex items-center space-x-3 px-4 py-2 {{ $currentRoute === 'admin.organic.create' ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg font-medium">
                                <i class="fas fa-plus w-4 text-center {{ $currentRoute === 'admin.organic.create' ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span>Registrar Nuevo</span>
                            </a>
                        </div>
                    </div>

                    <!-- Bodega de Clasificación -->
                    <div class="relative">
                        <button onclick="toggleSubmenu('warehouseSubmenu', 'warehouseArrow')" 
                            class="w-full flex items-center justify-between px-4 py-3 {{ $isWarehouse ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-xl transition-all duration-200 group">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-warehouse w-5 text-center {{ $isWarehouse ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span class="font-medium">Bodega</span>
                            </div>
                            <i id="warehouseArrow" class="fas fa-chevron-down text-soft-gray-400 text-xs arrow-transition {{ $isWarehouse ? 'rotate-180' : '' }}"></i>
                        </button>

                        <!-- Submenú con animaciones -->
                        <div id="warehouseSubmenu" class="submenu-container {{ $isWarehouse ? 'submenu-visible' : 'submenu-hidden' }} ml-10 mt-2 space-y-2">
                            <a href="{{ route('admin.warehouse.index') }}"
                               class="submenu-item flex items-center space-x-3 px-4 py-2 {{ $currentRoute === 'admin.warehouse.index' ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg font-medium">
                                <i class="fas fa-boxes w-4 text-center {{ $currentRoute === 'admin.warehouse.index' ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span>Inventario</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Creación de Pilas -->
                    <div class="relative">
                        <button onclick="toggleSubmenu('composting-submenu', 'composting-arrow')" class="w-full flex items-center justify-between px-4 py-3 {{ ($isComposting || $isTracking) ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-xl transition-all duration-200 group">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-mountain w-5 text-center {{ ($isComposting || $isTracking) ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span class="font-medium">Creación de Pilas</span>
                            </div>
                            <i class="fas fa-chevron-down text-soft-gray-400 text-xs arrow-transition {{ ($isComposting || $isTracking) ? 'rotate-180' : '' }}" id="composting-arrow"></i>
                        </button>
                        
                        <div id="composting-submenu" class="submenu-container {{ ($isComposting || $isTracking) ? 'submenu-visible' : 'submenu-hidden' }} ml-8 mt-2 space-y-1">
                            <!-- Pila -->
                            <div class="relative">
                                <button onclick="toggleSubmenu('pile-submenu', 'pile-arrow')" class="w-full flex items-center justify-between px-4 py-2 {{ $isComposting ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg transition-all duration-200 group font-medium">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-leaf w-4 text-center {{ $isComposting ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                        <span class="font-medium">Pila</span>
                                    </div>
                                    <i class="fas fa-chevron-down text-soft-gray-400 text-xs arrow-transition {{ $isComposting ? 'rotate-180' : '' }}" id="pile-arrow"></i>
                                </button>
                                
                                <div id="pile-submenu" class="submenu-container {{ $isComposting ? 'submenu-visible' : 'submenu-hidden' }} ml-6 mt-1 space-y-1">
                                    <a href="{{ route('admin.composting.create') }}" class="submenu-item flex items-center space-x-3 px-3 py-2 {{ $currentRoute === 'admin.composting.create' ? 'bg-green-50 text-green-700' : 'text-soft-gray-500 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg transition-all duration-200 group">
                                        <i class="fas fa-plus w-4 text-center {{ $currentRoute === 'admin.composting.create' ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                        <span class="text-sm font-medium">Registrar Pila</span>
                                    </a>
                                    <a href="{{ route('admin.composting.index') }}" class="submenu-item flex items-center space-x-3 px-3 py-2 {{ $currentRoute === 'admin.composting.index' ? 'bg-green-50 text-green-700' : 'text-soft-gray-500 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg transition-all duration-200 group">
                                        <i class="fas fa-list w-4 text-center {{ $currentRoute === 'admin.composting.index' ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                        <span class="text-sm font-medium">Ver Registros</span>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Seguimiento de Pila -->
                            <div class="relative">
                                <button onclick="toggleSubmenu('tracking-submenu', 'tracking-arrow')" class="w-full flex items-center justify-between px-4 py-2 {{ $isTracking ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg transition-all duration-200 group font-medium">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-chart-line w-4 text-center {{ $isTracking ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                        <span class="font-medium">Seguimiento</span>
                                    </div>
                                    <i class="fas fa-chevron-down text-soft-gray-400 text-xs arrow-transition {{ $isTracking ? 'rotate-180' : '' }}" id="tracking-arrow"></i>
                                </button>
                                
                                <div id="tracking-submenu" class="submenu-container {{ $isTracking ? 'submenu-visible' : 'submenu-hidden' }} ml-6 mt-1 space-y-1">
                                    <a href="{{ route('admin.tracking.create') }}" class="submenu-item flex items-center space-x-3 px-3 py-2 {{ $currentRoute === 'admin.tracking.create' ? 'bg-green-50 text-green-700' : 'text-soft-gray-500 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg transition-all duration-200 group">
                                        <i class="fas fa-plus w-4 text-center {{ $currentRoute === 'admin.tracking.create' ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                        <span class="text-sm font-medium">Nuevo Seguimiento</span>
                                    </a>
                                    <a href="{{ route('admin.tracking.index') }}" class="submenu-item flex items-center space-x-3 px-3 py-2 {{ $currentRoute === 'admin.tracking.index' ? 'bg-green-50 text-green-700' : 'text-soft-gray-500 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg transition-all duration-200 group">
                                        <i class="fas fa-list w-4 text-center {{ $currentRoute === 'admin.tracking.index' ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                        <span class="text-sm font-medium">Ver Seguimientos</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Maquinaria -->
                    <div class="relative">
                        <button onclick="toggleSubmenu('machinery-submenu', 'machinery-arrow')" class="w-full flex items-center justify-between px-4 py-3 {{ $isMachinery ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-xl transition-all duration-200 group">
                            <div class="flex items-center space-x-3">
                        <i class="fas fa-cogs w-5 text-center {{ $isMachinery ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                        <span class="font-medium">Maquinaria</span>
                            </div>
                            <i class="fas fa-chevron-down text-soft-gray-400 text-xs arrow-transition {{ $isMachinery ? 'rotate-180' : '' }}" id="machinery-arrow"></i>
                        </button>
                        
                        <div id="machinery-submenu" class="submenu-container {{ $isMachinery ? 'submenu-visible' : 'submenu-hidden' }} ml-10 mt-2 space-y-2">
                            <!-- Identificación y Especificaciones del Equipo -->
                            <a href="{{ route('admin.machinery.index') }}" 
                               class="submenu-item {{ $isMachinery ? 'animate-in' : '' }} flex items-start space-x-3 px-4 py-2 {{ $currentRoute === 'admin.machinery.index' || $currentRoute === 'admin.machinery.create' || $currentRoute === 'admin.machinery.show' || $currentRoute === 'admin.machinery.edit' ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg transition-all duration-200 group font-medium">
                                <i class="fas fa-tools w-4 text-center flex-shrink-0 mt-0.5 {{ $currentRoute === 'admin.machinery.index' || $currentRoute === 'admin.machinery.create' || $currentRoute === 'admin.machinery.show' || $currentRoute === 'admin.machinery.edit' ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span class="leading-tight flex-1 min-w-0" style="text-align: justify; text-align-last: left;">Identificación y Especificaciones</span>
                            </a>
                            
                            <!-- Datos del Proveedor -->
                            <a href="{{ route('admin.machinery.supplier.index') }}" 
                               class="submenu-item {{ $isMachinery ? 'animate-in' : '' }} flex items-start space-x-3 px-4 py-2 {{ str_starts_with($currentRoute, 'admin.machinery.supplier') ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg transition-all duration-200 group font-medium">
                                <i class="fas fa-truck w-4 text-center flex-shrink-0 mt-0.5 {{ str_starts_with($currentRoute, 'admin.machinery.supplier') ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span class="leading-tight flex-1 min-w-0">Datos del Proveedor</span>
                            </a>
                            
                            <!-- Control de Actividades -->
                            <a href="{{ route('admin.machinery.maintenance.index') }}" 
                               class="submenu-item {{ $isMachinery ? 'animate-in' : '' }} flex items-start space-x-3 px-4 py-2 {{ str_starts_with($currentRoute, 'admin.machinery.maintenance') ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg transition-all duration-200 group font-medium">
                                <i class="fas fa-wrench w-4 text-center flex-shrink-0 mt-0.5 {{ str_starts_with($currentRoute, 'admin.machinery.maintenance') ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span class="leading-tight flex-1 min-w-0" style="text-align: justify; text-align-last: left;">Control de Actividades</span>
                            </a>
                            
                            <!-- Control de Uso del Equipo -->
                            <a href="{{ route('admin.machinery.usage-control.index') }}" 
                               class="submenu-item {{ $isMachinery ? 'animate-in' : '' }} flex items-start space-x-3 px-4 py-2 {{ str_starts_with($currentRoute, 'admin.machinery.usage-control') ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg transition-all duration-200 group font-medium">
                                <i class="fas fa-clipboard-check w-4 text-center flex-shrink-0 mt-0.5 {{ str_starts_with($currentRoute, 'admin.machinery.usage-control') ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span class="leading-tight flex-1 min-w-0" style="text-align: justify; text-align-last: left;">Control de Uso del Equipo</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Abono -->
                    <div class="relative">
                        <button onclick="toggleSubmenu('abonoSubmenu', 'abonoArrow')" 
                            class="w-full flex items-center justify-between px-4 py-3 {{ $isFertilizer ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-xl transition-all duration-200 group">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-seedling w-5 text-center {{ $isFertilizer ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span class="font-medium">Abono</span>
                            </div>
                            <i id="abonoArrow" class="fas fa-chevron-down text-soft-gray-400 text-xs arrow-transition {{ $isFertilizer ? 'rotate-180' : '' }}"></i>
                        </button>

                        <!-- Submenú con animaciones -->
                        <div id="abonoSubmenu" class="submenu-container {{ $isFertilizer ? 'submenu-visible' : 'submenu-hidden' }} ml-10 mt-2 space-y-2">
                            <a href="{{ route('admin.fertilizer.create') }}" 
                               class="submenu-item flex items-center space-x-3 px-4 py-2 {{ $currentRoute === 'admin.fertilizer.create' ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg font-medium">
                                <i class="fas fa-edit w-4 text-center {{ $currentRoute === 'admin.fertilizer.create' ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span>Registro</span>
                            </a>
                            <a href="{{ route('admin.fertilizer.index') }}" 
                               class="submenu-item flex items-center space-x-3 px-4 py-2 {{ $currentRoute === 'admin.fertilizer.index' ? 'bg-green-50 text-green-700' : 'text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700' }} rounded-lg font-medium">
                                <i class="fas fa-list w-4 text-center {{ $currentRoute === 'admin.fertilizer.index' ? 'text-green-600' : 'group-hover:text-soft-green-600' }}"></i>
                                <span>Listas</span>
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="h-14 sm:h-16 bg-green-100 shadow-sm border-b border-soft-gray-200 flex items-center justify-between px-3 sm:px-4 lg:px-6">
                <div class="flex items-center space-x-2 sm:space-x-4 flex-1 min-w-0">
                    <!-- Hamburger menu button -->
                    <button @click="sidebarOpen = true" class="text-soft-gray-600 focus:outline-none lg:hidden flex-shrink-0 p-2">
                        <i class="fas fa-bars text-lg sm:text-xl"></i>
                    </button>
                    
                    <h2 class="text-base sm:text-lg lg:text-xl font-semibold text-soft-gray-800 truncate">Panel de Administración</h2>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-1 sm:space-x-2 lg:space-x-4 flex-shrink-0">
                    <!-- Notifications Bell -->
                    <div class="relative">
                        @php
                            \App\Models\Machinery::ensureFrequencyBasedRemindersForUser(auth()->user());
                            $showMaintenanceReminderAlert = \App\Models\Notification::where('user_id', auth()->id())
                                ->where('type', 'maintenance_reminder')
                                ->whereNull('read_at')
                                ->exists();
                        @endphp
                        <button onclick="toggleNotifications()" 
                            class="relative p-1.5 sm:p-2 text-soft-gray-600 hover:text-soft-green-600 hover:bg-soft-gray-100 rounded-lg transition-all duration-200">
                            <i class="fas fa-bell text-base sm:text-lg"></i>
                            <!-- Notification Badge -->
                            @php
                                $pendingNotifications = \App\Models\Notification::where('user_id', auth()->id())
                                    ->whereIn('type', ['delete_request', 'maintenance_reminder'])
                                    ->where('status', 'pending')
                                    ->whereNull('read_at')
                                    ->count();
                            @endphp
                            @if($pendingNotifications > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                                    {{ $pendingNotifications > 9 ? '9+' : $pendingNotifications }}
                                </span>
                            @endif
                        </button>
                        
                        <!-- Notifications Dropdown: en móvil fixed para verse completo en pantalla, en escritorio absolute bajo la campana -->
                        <div id="notificationsMenu" class="hidden fixed left-2 right-2 top-16 sm:absolute sm:left-auto sm:right-0 sm:top-auto sm:mt-2 sm:w-80 bg-white rounded-lg shadow-lg border border-soft-gray-200 py-2 z-50 max-h-[70vh] sm:max-h-96 overflow-y-auto min-w-0">
                            <div class="px-3 sm:px-4 py-2 border-b border-soft-gray-100 flex flex-wrap items-center justify-between gap-2">
                                <h3 class="text-sm font-semibold text-soft-gray-800">Notificaciones</h3>
                                <a href="{{ route('admin.notifications.history') }}" 
                                   class="text-xs text-soft-green-600 hover:text-soft-green-700 font-medium whitespace-nowrap">
                                    Ver historial
                                </a>
                            </div>
                            
                            @php
                                $notifications = \App\Models\Notification::where('user_id', auth()->id())
                                    ->whereIn('type', ['delete_request', 'maintenance_reminder'])
                                    ->where('status', 'pending')
                                    ->whereNull('read_at')
                                    ->with(['fromUser', 'organic', 'composting', 'machinery', 'maintenance'])
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp
                            
                            @forelse($notifications as $notification)
                                @if($notification->type === 'maintenance_reminder')
                                    <!-- Notificación de Mantenimiento -->
                                    <div class="px-3 sm:px-4 py-3 hover:bg-soft-gray-50 border-b border-soft-gray-100 last:border-b-0">
                                        <div class="flex items-start gap-2 sm:space-x-3 min-w-0">
                                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-tools text-orange-600 text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-soft-gray-800 break-words">Recordatorio de Mantenimiento</p>
                                                <p class="text-xs text-soft-gray-600 mt-1 break-words">{{ $notification->machinery->name ?? 'Maquinaria no encontrada' }}</p>
                                                <p class="text-xs text-soft-gray-500 mt-1 break-words">{{ $notification->message }}</p>
                                                <p class="text-xs text-soft-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    <button onclick="markNotificationAsRead({{ $notification->id }})" 
                                                        class="px-2 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 transition-colors">
                                                        Marcar como leída
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($notification->type === 'delete_request')
                                    <!-- Notificación de Solicitud de Eliminación -->
                                    <div class="px-3 sm:px-4 py-3 hover:bg-soft-gray-50 border-b border-soft-gray-100 last:border-b-0">
                                        <div class="flex items-start gap-2 sm:space-x-3 min-w-0">
                                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-trash text-yellow-600 text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-soft-gray-800 break-words">{{ $notification->fromUser->name ?? 'Usuario desconocido' }}</p>
                                                <p class="text-xs text-soft-gray-600 mt-1 break-words">
                                                    @if($notification->composting_id)
                                                        Solicita eliminar pila de compostaje #{{ $notification->composting->formatted_pile_num ?? 'N/A' }}
                                                    @elseif($notification->organic_id)
                                                        Solicita eliminar registro #{{ str_pad($notification->organic_id, 3, '0', STR_PAD_LEFT) }}
                                                    @elseif($notification->machinery_id)
                                                        Solicita eliminar maquinaria: {{ $notification->machinery->name ?? 'N/A' }}
                                                    @elseif($notification->usage_control_id)
                                                        Solicita eliminar control de uso del equipo #{{ str_pad($notification->usage_control_id, 3, '0', STR_PAD_LEFT) }}
                                                    @elseif($notification->maintenance_id)
                                                        Solicita eliminar control de actividades #{{ str_pad($notification->maintenance_id, 3, '0', STR_PAD_LEFT) }}
                                                    @elseif($notification->supplier_id)
                                                        Solicita eliminar proveedor #{{ str_pad($notification->supplier_id, 3, '0', STR_PAD_LEFT) }}
                                                    @else
                                                        {{ $notification->message }}
                                                    @endif
                                                </p>
                                                <p class="text-xs text-soft-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    <button onclick="approveDeleteRequest({{ $notification->id }})" 
                                                        class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 transition-colors">
                                                        Aprobar
                                                    </button>
                                                    <button onclick="rejectDeleteRequest({{ $notification->id }})" 
                                                        class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition-colors">
                                                        Rechazar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="px-3 sm:px-4 py-6 text-center">
                                    <i class="fas fa-bell-slash text-soft-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-soft-gray-500">No hay notificaciones pendientes</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Help Button -->
                    <div class="relative">
                        <button onclick="toggleHelpMenu()" 
                            class="relative p-1.5 sm:p-2 text-soft-gray-600 hover:text-soft-green-600 hover:bg-soft-gray-100 rounded-lg transition-all duration-200">
                            <i class="fas fa-question-circle text-base sm:text-lg"></i>
                        </button>
                        
                        <div id="helpMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-soft-gray-200 py-2 z-50">
                            <div class="px-4 py-2 border-b border-soft-gray-100">
                                <h3 class="text-sm font-semibold text-soft-gray-800"><i class="fas fa-book mr-1"></i> Manuales</h3>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('manual.view', 'administrador') }}" target="_blank" rel="noopener"
                                   class="flex items-center px-4 py-2.5 text-sm text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700 transition-colors duration-200">
                                    <i class="fas fa-file-pdf text-red-500 w-5 mr-3"></i>
                                    Manual de Administrador
                                </a>
                                <a href="{{ route('manual.view.tecnico') }}" target="_blank" rel="noopener"
                                   class="flex items-center px-4 py-2.5 text-sm text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700 transition-colors duration-200">
                                    <i class="fas fa-file-pdf text-red-500 w-5 mr-3"></i>
                                    Manual Técnico
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <button onclick="toggleSubmenu('userMenu', 'userArrow')" 
                            class="flex items-center space-x-1 sm:space-x-2 lg:space-x-3 hover:bg-soft-gray-100 rounded-lg px-1.5 sm:px-2 lg:px-3 py-1.5 sm:py-2 transition-all duration-200">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-gradient-to-br from-soft-green-500 to-soft-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-white text-xs sm:text-sm"></i>
                            </div>
                            <div class="text-right">
                                <p class="text-xs sm:text-sm font-medium text-soft-gray-800 truncate max-w-[100px] sm:max-w-none">{{ Auth::user()?->name ?? 'Usuario' }}</p>
                                <p class="text-xs text-soft-gray-500">Administrador</p>
                            </div>
                            <i id="userArrow" class="fas fa-chevron-down text-soft-gray-400 text-xs transition-transform duration-200"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-soft-gray-200 py-2 z-50">
                            <!-- User Email -->
                            <div class="px-4 py-2 border-b border-soft-gray-100">
                                <p class="text-xs text-soft-gray-500">{{ Auth::user()?->email ?? 'N/A' }}</p>
                            </div>
                            
                            <!-- Menu Items -->
                            <div class="py-1">

                                <a href="{{ url('/') }}" class="flex items-center px-4 py-2 text-sm text-soft-gray-700 hover:bg-soft-gray-50 transition-colors duration-200">
                                    <i class="fas fa-home w-4 text-soft-gray-400 mr-3"></i>
                                    Welcome
                                </a>
                            </div>
                            
                            <!-- Divider -->
                            <div class="border-t border-soft-gray-100 my-1"></div>
                            
                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt w-4 mr-3"></i>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-soft-gray-50 p-3 sm:p-4 lg:p-6">
                <div class="w-full max-w-full overflow-x-hidden">
                    @yield('content')
                </div>
            </main>
        </div>                              
    </div>


    <!-- jQuery (requerido por DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    
    <script>
        function toggleSubmenu(id, arrowId) {
            const submenu = document.getElementById(id);
            const arrow = document.getElementById(arrowId);

            if (!submenu) {
                console.error('Submenu not found:', id);
                return;
            }
            if (!arrow) {
                console.error('Arrow not found:', arrowId);
                return;
            }

            // Para los menús con animaciones (Abono, Organic Waste, Warehouse, Composting y Machinery)
            if (id === 'abonoSubmenu' || id === 'organicSubmenu' || id === 'warehouseSubmenu' || id === 'composting-submenu' || id === 'pile-submenu' || id === 'tracking-submenu' || id === 'machinery-submenu' || id === 'machinery-submenu') {
                const isHidden = submenu.classList.contains('submenu-hidden');
                const submenuItems = submenu.querySelectorAll('.submenu-item');
                
                // Si se está abriendo un submenú dentro de "Creación de Pilas", cerrar el otro
                if (id === 'pile-submenu' && isHidden) {
                    // Cerrar "Seguimiento" si está abierto
                    const trackingSubmenu = document.getElementById('tracking-submenu');
                    const trackingArrow = document.getElementById('tracking-arrow');
                    if (trackingSubmenu && !trackingSubmenu.classList.contains('submenu-hidden')) {
                        trackingSubmenu.classList.remove('submenu-visible');
                        trackingSubmenu.classList.add('submenu-hidden');
                        if (trackingArrow) trackingArrow.classList.remove('rotate-180');
                        trackingSubmenu.querySelectorAll('.submenu-item').forEach(item => {
                            item.classList.remove('animate-in');
                        });
                    }
                } else if (id === 'tracking-submenu' && isHidden) {
                    // Cerrar "Pila" si está abierto
                    const pileSubmenu = document.getElementById('pile-submenu');
                    const pileArrow = document.getElementById('pile-arrow');
                    if (pileSubmenu && !pileSubmenu.classList.contains('submenu-hidden')) {
                        pileSubmenu.classList.remove('submenu-visible');
                        pileSubmenu.classList.add('submenu-hidden');
                        if (pileArrow) pileArrow.classList.remove('rotate-180');
                        pileSubmenu.querySelectorAll('.submenu-item').forEach(item => {
                            item.classList.remove('animate-in');
                        });
                    }
                }
                
                if (isHidden) {
                    // Mostrar el submenú
                    submenu.classList.remove('submenu-hidden');
                    submenu.classList.add('submenu-visible');
                    arrow.classList.add('rotate-180');
                    
                    // Animar cada elemento del submenú con delay
                    submenuItems.forEach((item, index) => {
                        setTimeout(() => {
                            item.classList.add('animate-in');
                        }, index * 100); // 100ms de delay entre cada elemento
                    });
                } else {
                    // Ocultar el submenú
                    submenu.classList.remove('submenu-visible');
                    submenu.classList.add('submenu-hidden');
                    arrow.classList.remove('rotate-180');
                    
                    // Remover las animaciones
                    submenuItems.forEach(item => {
                        item.classList.remove('animate-in');
                    });
                }
            } else {
                // Para otros menús (como el menú de usuario)
                submenu.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');
            }
        }

        // Abrir automáticamente los submenús activos al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            @if($isOrganic)
                toggleSubmenu('organicSubmenu', 'organicArrow');
            @endif
            
            @if($isWarehouse)
                toggleSubmenu('warehouseSubmenu', 'warehouseArrow');
            @endif
            
            @if($isComposting || $isTracking)
                toggleSubmenu('composting-submenu', 'composting-arrow');
            @endif
            
            @if($isComposting)
                toggleSubmenu('pile-submenu', 'pile-arrow');
            @endif
            
            @if($isTracking)
                toggleSubmenu('tracking-submenu', 'tracking-arrow');
            @endif
            
            @if($isFertilizer)
                toggleSubmenu('abonoSubmenu', 'abonoArrow');
            @endif
            
            @if($isMachinery)
                toggleSubmenu('machinery-submenu', 'machinery-arrow');
            @endif
        });

        // Notifications functions
        function toggleNotifications() {
            const menu = document.getElementById('notificationsMenu');
            const userMenu = document.getElementById('userMenu');
            const helpMenu = document.getElementById('helpMenu');
            
            if (!userMenu.classList.contains('hidden')) userMenu.classList.add('hidden');
            if (!helpMenu.classList.contains('hidden')) helpMenu.classList.add('hidden');
            
            menu.classList.toggle('hidden');
        }

        function toggleHelpMenu() {
            const helpMenu = document.getElementById('helpMenu');
            const notificationsMenu = document.getElementById('notificationsMenu');
            const userMenu = document.getElementById('userMenu');
            
            if (!notificationsMenu.classList.contains('hidden')) notificationsMenu.classList.add('hidden');
            if (!userMenu.classList.contains('hidden')) userMenu.classList.add('hidden');
            
            helpMenu.classList.toggle('hidden');
        }

        function approveDeleteRequest(notificationId) {
            const csrf = document.querySelector('meta[name="csrf-token"]');
            const token = csrf ? csrf.getAttribute('content') : '';
            fetch(`/admin/notifications/${notificationId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({ _token: token })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(d => Promise.reject(d));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Aprobado!',
                        text: 'Solicitud aprobada. Recarga la página para ver el conteo.',
                        icon: 'success',
                        confirmButtonColor: '#22c55e'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({ title: 'Error', text: data.message || 'No se pudo aprobar', icon: 'error' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: error.message || 'Ocurrió un error al procesar la solicitud',
                    icon: 'error'
                });
            });
        }

        function markNotificationAsRead(notificationId) {
            fetch(`/admin/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al marcar la notificación como leída',
                    icon: 'error'
                });
            });
        }

        function rejectDeleteRequest(notificationId) {
            fetch(`/admin/notifications/${notificationId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Rechazado',
                        text: 'Solicitud de eliminación rechazada',
                        icon: 'info',
                        confirmButtonColor: '#6b7280'
                    }).then(() => {
                        location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la solicitud',
                    icon: 'error'
                });
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const notificationsMenu = document.getElementById('notificationsMenu');
            const helpMenu = document.getElementById('helpMenu');
            const notificationButton = event.target.closest('[onclick="toggleNotifications()"]');
            const helpButton = event.target.closest('[onclick="toggleHelpMenu()"]');
            
            if (!notificationButton && !notificationsMenu.contains(event.target)) {
                notificationsMenu.classList.add('hidden');
            }
            if (!helpButton && !helpMenu.contains(event.target)) {
                helpMenu.classList.add('hidden');
            }
        });

        @if(!empty($showMaintenanceReminderAlert))
        document.addEventListener('DOMContentLoaded', function() {
            function showMaintenanceReminder() {
                Swal.fire({
                    title: 'Recordatorio de Mantenimiento',
                    text: 'Tiene recordatorios de mantenimiento sin leer. Revise sus notificaciones.',
                    icon: 'warning',
                    timer: 15000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="fas fa-bell mr-1"></i> Ver Notificaciones',
                    confirmButtonColor: '#f59e0b'
                }).then((result) => {
                    if (result.isConfirmed) {
                        toggleNotifications();
                    }
                });
            }
            showMaintenanceReminder();
            setInterval(showMaintenanceReminder, 15000);
        });
        @endif

        @if(session('unauthorized_access'))
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '¡Acceso No Autorizado!',
                text: 'No tienes permisos para acceder a esa sección.',
                icon: 'error',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#ef4444'
            });
        });
        @endif
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Compostaje - Aprendiz</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-transition { transition: all 0.3s ease-in-out; }
        .content-transition { transition: all 0.3s ease-in-out; }
        .hover-lift { transition: transform 0.2s ease-in-out; }
        .hover-lift:hover { transform: translateY(-2px); }
    </style>
</head>


<body class="bg-soft-gray-50 font-sans">
    <div class="flex h-screen">
        
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg sidebar-transition">
            <!-- Logo/Brand -->
            <div class="h-32 flex items-center justify-center border-b border-soft-gray-200 px-4">
                <img src="{{ asset('img/logo-compost-cefa.png') }}" alt="COMPOST CEFA" class="h-28 w-auto max-w-full object-contain">
            </div>
            
            <!-- Navigation -->
            <nav class="mt-6 px-4">
                <div class="space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('aprendiz.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 bg-green-50 text-green-700 rounded-xl transition-all duration-200 group">
                        <i class="fas fa-globe w-5 text-center text-green-600"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    
                    <!-- Residuos Orgánicos -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center space-x-3 px-4 py-3 text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700 rounded-xl transition-all duration-200 group">
                            <i class="fas fa-recycle w-5 text-center group-hover:text-soft-green-600"></i>
                            <span class="font-medium">Residuos</span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200 ml-auto" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <!-- Submenu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="ml-8 mt-2 space-y-1">
                            <a href="{{ route('aprendiz.organic.index') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-soft-gray-600 hover:bg-soft-green-50 hover:text-soft-green-700 rounded-lg transition-all duration-200 group">
                                <i class="fas fa-list w-4 text-center group-hover:text-soft-green-600"></i>
                                <span>Ver Registros</span>
                            </a>
                            <a href="{{ route('aprendiz.organic.create') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-soft-gray-600 hover:bg-soft-green-50 hover:text-soft-green-700 rounded-lg transition-all duration-200 group">
                                <i class="fas fa-plus w-4 text-center group-hover:text-soft-green-600"></i>
                                <span>Registrar Nuevo</span>
                            </a>
                        </div>
                    </div>

                    <!-- Bodega de Clasificación -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center space-x-3 px-4 py-3 text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700 rounded-xl transition-all duration-200 group">
                            <i class="fas fa-warehouse w-5 text-center group-hover:text-soft-green-600"></i>
                            <span class="font-medium">Bodega</span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200 ml-auto" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <!-- Submenu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="ml-8 mt-2 space-y-1">
            <a href="{{ route('aprendiz.warehouse.index') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-soft-gray-600 hover:bg-soft-green-50 hover:text-soft-green-700 rounded-lg transition-all duration-200 group">
                <i class="fas fa-boxes w-4 text-center group-hover:text-soft-green-600"></i>
                <span>Inventario</span>
            </a>
                        </div>
                    </div>
                    
                    <!-- Creación de Pilas -->
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700 rounded-xl transition-all duration-200 group">
                        <i class="fas fa-mountain w-5 text-center group-hover:text-soft-green-600"></i>
                        <span class="font-medium">Creación de Pilas</span>
                    </a>
                    
                    <!-- Maquinaria -->
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700 rounded-xl transition-all duration-200 group">
                        <i class="fas fa-cogs w-5 text-center group-hover:text-soft-green-600"></i>
                        <span class="font-medium">Maquinaria</span>
                    </a>
                    
                    <!-- Abono -->
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 text-soft-gray-700 hover:bg-soft-green-50 hover:text-soft-green-700 rounded-xl transition-all duration-200 group">
                        <i class="fas fa-seedling w-5 text-center group-hover:text-soft-green-600"></i>
                        <span class="font-medium">Abono</span>
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="h-16 bg-green-100 shadow-sm border-b border-soft-gray-200 flex items-center justify-between px-6">
                <div class="flex items-center space-x-4">
                    <h2 class="text-xl font-semibold text-soft-gray-800">Panel de Aprendiz</h2>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications Bell -->
                    <div class="relative" x-data="{ notificationsOpen: false }">
                        <button @click="notificationsOpen = !notificationsOpen" 
                            class="relative p-2 text-soft-gray-600 hover:text-soft-green-600 hover:bg-soft-gray-100 rounded-lg transition-all duration-200">
                            <i class="fas fa-bell text-lg"></i>
                            <!-- Notification Badge -->
                            @php
                                $pendingNotifications = \App\Models\Notification::where('from_user_id', auth()->id())
                                    ->where('type', 'delete_request')
                                    ->whereIn('status', ['approved', 'rejected'])
                                    ->whereNull('read_at')
                                    ->count();
                            @endphp
                            @if($pendingNotifications > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                                    {{ $pendingNotifications > 9 ? '9+' : $pendingNotifications }}
                                </span>
                            @endif
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div x-show="notificationsOpen" 
                             @click.away="notificationsOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-soft-gray-200 py-2 z-50 max-h-96 overflow-y-auto">
                            <div class="px-4 py-2 border-b border-soft-gray-100 flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-soft-gray-800">Respuestas a Solicitudes</h3>
                                <a href="{{ route('aprendiz.notifications.history') }}" 
                                   class="text-xs text-soft-green-600 hover:text-soft-green-700 font-medium">
                                    Ver historial
                                </a>
                            </div>
                            
                            @php
                                $notifications = \App\Models\Notification::where('from_user_id', auth()->id())
                                    ->where('type', 'delete_request')
                                    ->whereIn('status', ['approved', 'rejected'])
                                    ->whereNull('read_at')
                                    ->with(['organic'])
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp
                            
                            @forelse($notifications as $notification)
                                <div class="px-4 py-3 hover:bg-soft-gray-50 border-b border-soft-gray-100 last:border-b-0">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 {{ $notification->status === 'approved' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas {{ $notification->status === 'approved' ? 'fa-check text-green-600' : 'fa-times text-red-600' }} text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-soft-gray-800">
                                                Solicitud {{ $notification->status === 'approved' ? 'Aprobada' : 'Rechazada' }}
                                            </p>
                                            <p class="text-xs text-soft-gray-600 mt-1">
                                                Registro #{{ str_pad($notification->organic_id, 3, '0', STR_PAD_LEFT) }}
                                            </p>
                                            <p class="text-xs text-soft-gray-500 mt-1">
                                                {{ $notification->updated_at->diffForHumans() }}
                                            </p>
                                            <div class="flex space-x-2 mt-2">
                                                <button onclick="markAsRead({{ $notification->id }})" 
                                                    class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition-colors">
                                                    Marcar como leída
                                                </button>
                                                @if($notification->status === 'approved')
                                                    <a href="{{ route('aprendiz.organic.index') }}" 
                                                       class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 transition-colors">
                                                        Ver registros
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-center">
                                    <i class="fas fa-bell-slash text-soft-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-soft-gray-500">No hay notificaciones nuevas</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 hover:bg-soft-gray-100 rounded-lg px-3 py-2 transition-all duration-200">
                            <div class="w-8 h-8 bg-gradient-to-br from-soft-green-400 to-soft-green-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-soft-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-soft-gray-500">Aprendiz</p>
                            </div>
                            <i class="fas fa-chevron-down text-soft-gray-400 text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-soft-gray-200 py-2 z-50">
                            
                            <!-- User Info -->
                            <div class="px-4 py-2 border-b border-soft-gray-100">
                                <p class="text-sm font-medium text-soft-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-soft-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                            
                            <!-- Menu Items -->
                            <div class="py-1">
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-soft-gray-700 hover:bg-soft-gray-50 transition-colors duration-200">
                                    <i class="fas fa-user-cog w-4 text-soft-gray-400 mr-3"></i>
                                    Perfil
                                </a>
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
            <main class="flex-1 overflow-y-auto bg-soft-gray-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function markAsRead(notificationId) {
            fetch(`/aprendiz/notifications/${notificationId}/mark-read`, {
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
                        title: 'Marcado como leído',
                        text: 'La notificación ha sido marcada como leída',
                        icon: 'success',
                        confirmButtonColor: '#22c55e',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al marcar la notificación',
                    icon: 'error'
                });
            });
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('img/logo-compost-cefa.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('img/logo-compost-cefa.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('img/logo-compost-cefa.png') }}">
        
        <title>COMPOST CEFA - Sistema de Registro</title>

        <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'compost': {
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
                        }
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'blink': 'blink 1s step-end infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        blink: {
                            'from, to': { borderColor: 'transparent' },
                            '50%': { borderColor: '#16a34a' },
                        }
                    }
                }
            }
        }
    </script>
            <style>
        .typewriter-text {
            border-right: 4px solid #16a34a;
            animation: blink 1s step-end infinite;
        }
        
        @keyframes blink {
            from, to { border-color: transparent; }
            50% { border-color: #16a34a; }
        }
            </style>
    </head>
<body class="font-inter bg-white min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-compost-100 to-compost-200 border-b border-compost-200 fixed top-0 left-0 right-0 z-50 shadow-lg transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-seedling text-white text-xl"></i>
                    </div>
                    <div>
                        <span class="text-2xl font-black text-compost-700">COMPOST</span>
                        <span class="text-xl font-bold text-compost-600 block -mt-1">CEFA</span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#about" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105">Acerca de</a>
                    <a href="#modules" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105">Módulos</a>
                    <a href="#features" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105">Características</a>
                    <a href="{{ route('developers') }}" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105">Desarrolladores</a>
            @if (Route::has('login'))
                    @auth
                        <!-- User Info Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-3 bg-white/80 backdrop-blur-sm rounded-lg px-4 py-2 hover:bg-white transition-all duration-200 shadow-sm">
                                <div class="w-8 h-8 bg-gradient-to-br from-compost-600 to-compost-700 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-compost-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-compost-600">{{ ucfirst(Auth::user()->role) }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-compost-600 text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
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
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-compost-200 py-2 z-50">
                                
                                <!-- User Info -->
                                <div class="px-4 py-2 border-b border-compost-100">
                                    <p class="text-sm font-medium text-compost-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-compost-600">{{ Auth::user()->email }}</p>
                                </div>
                                
                                <!-- Menu Items -->
                                <div class="py-1">
                                    <a href="{{ url('/dashboard') }}" class="flex items-center px-4 py-2 text-sm text-compost-700 hover:bg-compost-50 transition-colors duration-200">
                                        <i class="fas fa-tachometer-alt w-4 text-compost-600 mr-3"></i>
                                        Dashboard
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-compost-700 hover:bg-compost-50 transition-colors duration-200">
                                        <i class="fas fa-user-cog w-4 text-compost-600 mr-3"></i>
                                        Perfil
                                    </a>
                                </div>
                                
                                <!-- Divider -->
                                <div class="border-t border-compost-100 my-1"></div>
                                
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
                    @else
                            <a href="{{ route('login') }}" class="bg-gradient-to-r from-compost-600 to-compost-700 text-white px-6 py-3 rounded-xl font-bold hover:from-compost-700 hover:to-compost-800 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                            </a>
                        @endauth
                        @endif
                </div>
                </nav>
        </div>
        </header>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 relative overflow-hidden min-h-screen flex items-center">
        <!-- Video Background -->
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-0">
            <source src="{{ asset('videos/hero-background.mp4') }}" type="video/mp4">
            <!-- Fallback si el video no carga -->
        </video>
        
        <!-- Overlay con opacidad para legibilidad del texto -->
        <div class="absolute inset-0 bg-black/80 z-10"></div>
        
        <!-- Contenido sobre el video -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
            <div class="text-center">
                <!-- Main Title with Typewriter Effect -->
                <div class="mb-8">
                    <h1 class="text-6xl md:text-8xl lg:text-9xl font-black text-compost-600 mb-4 typewriter-text" id="typewriter-title">
                        COMPOST
                    </h1>
                    <h2 class="text-4xl md:text-6xl lg:text-7xl font-black text-compost-800 mb-8" id="typewriter-subtitle">
                        CEFA
                    </h2>
                </div>

                <!-- Subtitle with Definition -->
                <div class="max-w-4xl mx-auto mb-12">
                    <p class="text-2xl md:text-3xl text-white font-medium leading-relaxed drop-shadow-md" id="typewriter-description">
                        Sistema de Registro de Creación de Pilas de Compostaje y Manipulación de Maquinaria
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="group bg-gradient-to-r from-compost-600 to-compost-700 text-white px-8 py-4 rounded-xl font-bold text-lg hover:from-compost-700 hover:to-compost-800 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-tachometer-alt mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                                Acceder al Sistema
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group bg-gradient-to-r from-compost-600 to-compost-700 text-white px-8 py-4 rounded-xl font-bold text-lg hover:from-compost-700 hover:to-compost-800 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-sign-in-alt mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="group border-2 border-compost-600 text-compost-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-compost-600 hover:text-white transform hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-user-plus mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>

        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-4 h-4 bg-compost-400 rounded-full animate-bounce opacity-60 z-30"></div>
        <div class="absolute top-40 right-20 w-6 h-6 bg-compost-300 rounded-full animate-bounce opacity-40 z-30" style="animation-delay: 0.5s;"></div>
        <div class="absolute bottom-20 left-20 w-3 h-3 bg-compost-500 rounded-full animate-bounce opacity-70 z-30" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-40 right-10 w-5 h-5 bg-compost-400 rounded-full animate-bounce opacity-50 z-30" style="animation-delay: 1.5s;"></div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-compost-800 mb-6">¿Qué es COMPOST CEFA?</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-compost-600 to-compost-500 mx-auto mb-8"></div>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Es un sistema integral diseñado para el registro y control de la creación de pilas de compostaje 
                    y la manipulación de maquinaria en centros de acopio, optimizando todo el proceso desde la recepción 
                    de residuos orgánicos hasta la producción de abono.
                </p>
            </div>

            <!-- Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-gradient-to-br from-compost-50 to-compost-100 p-8 rounded-2xl text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-compost-600 to-compost-700 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-mountain text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-compost-800 mb-4">Creación de Pilas</h3>
                    <p class="text-gray-600">Registro detallado y control del proceso de creación y gestión de pilas de compostaje.</p>
                </div>

                <div class="bg-gradient-to-br from-compost-50 to-compost-100 p-8 rounded-2xl text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-compost-600 to-compost-700 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-tractor text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-compost-800 mb-4">Manipulación de Maquinaria</h3>
                    <p class="text-gray-600">Control y registro de la manipulación de equipos y maquinaria del centro de acopio.</p>
                </div>

                <div class="bg-gradient-to-br from-compost-50 to-compost-100 p-8 rounded-2xl text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-compost-600 to-compost-700 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-seedling text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-compost-800 mb-4">Producción de Abono</h3>
                    <p class="text-gray-600">Monitoreo y control del proceso de compostaje para obtener abono de alta calidad.</p>
                </div>

                <div class="bg-gradient-to-br from-compost-50 to-compost-100 p-8 rounded-2xl text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-compost-600 to-compost-700 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-recycle text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-compost-800 mb-4">Registro de Residuos Orgánicos</h3>
                    <p class="text-gray-600">Control completo del registro, clasificación y procesamiento de residuos orgánicos.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Modules Section -->
    <section id="modules" class="py-20 bg-gradient-to-br from-compost-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-compost-800 mb-6">Módulos del Sistema</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-compost-600 to-compost-500 mx-auto mb-8"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Componentes especializados para el registro y control de pilas de compostaje y maquinaria
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Module 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 border border-compost-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-compost-800 mb-4">Gestión de Pasantes</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Administración completa de pasantes, asignación de tareas y seguimiento de actividades.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Registro de pasantes</li>
                        <li>• Asignación de tareas</li>
                        <li>• Seguimiento de progreso</li>
                    </ul>
                </div>
                
                <!-- Module 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 border border-compost-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-mountain text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-compost-800 mb-4">Registro de Pilas</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Control completo del registro y creación de pilas de compostaje.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Creación de pilas</li>
                        <li>• Seguimiento de estado</li>
                        <li>• Control de maduración</li>
                    </ul>
                </div>
                
                <!-- Module 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 border border-compost-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-tractor text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-compost-800 mb-4">Manipulación de Maquinaria</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Control y registro de la manipulación de equipos del centro de acopio.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Registro de uso</li>
                        <li>• Mantenimiento preventivo</li>
                        <li>• Estado de equipos</li>
                    </ul>
                </div>
                
                <!-- Module 4 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 border border-compost-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-compost-800 mb-4">Monitoreo</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Supervisión continua de temperatura, humedad y estado de las pilas.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Temperatura en tiempo real</li>
                        <li>• Control de humedad</li>
                        <li>• Estado de maduración</li>
                    </ul>
                </div>
                
                <!-- Module 5 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 border border-compost-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-chart-bar text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-compost-800 mb-4">Reportes</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Generación de informes detallados sobre producción y eficiencia.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Reportes de producción</li>
                        <li>• Estadísticas operativas</li>
                        <li>• Análisis de eficiencia</li>
                    </ul>
                </div>
                
                <!-- Module 6 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 border border-compost-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-warehouse text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-compost-800 mb-4">Control de Inventario</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Gestión completa del stock de residuos, abono y materiales.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Entrada de residuos</li>
                        <li>• Control de stock</li>
                        <li>• Salida de abono</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"></div>
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-compost-800 mb-6">Características Principales</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-compost-600 to-compost-500 mx-auto mb-8"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Funcionalidades esenciales para el control eficiente del proceso de compostaje
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-5xl mx-auto">
                <!-- Feature Left -->
                <div class="space-y-8">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <i class="fas fa-chart-line text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-compost-800 mb-2">Monitoreo Continuo</h3>
                            <p class="text-gray-600">Seguimiento en tiempo real de temperatura, humedad y estado de las pilas de compostaje.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <i class="fas fa-shield-alt text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-compost-800 mb-2">Seguridad Avanzada</h3>
                            <p class="text-gray-600">Sistema de autenticación robusto con roles diferenciados para administradores y pasantes.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Feature Right -->
                <div class="space-y-8">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-compost-800 mb-2">Gestión de Usuarios</h3>
                            <p class="text-gray-600">Control completo de accesos y permisos para administradores y pasantes del centro.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <i class="fas fa-clipboard-list text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-compost-800 mb-2">Registro Detallado</h3>
                            <p class="text-gray-600">Documentación completa de todas las actividades y procesos del centro de acopio.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-compost-800 to-compost-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Map -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-lg font-bold text-white mb-4">Ubicación</h3>
                    <div class="rounded-lg overflow-hidden shadow-lg" style="height: 300px;">
                        <iframe 
                            src="https://www.google.com/maps?q=2.61361,-75.36111&hl=es&z=15&output=embed" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-bold text-white mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="#about" class="text-compost-200 hover:text-white transition-colors duration-300">Acerca de</a></li>
                        <li><a href="#modules" class="text-compost-200 hover:text-white transition-colors duration-300">Módulos</a></li>
                        <li><a href="#features" class="text-compost-200 hover:text-white transition-colors duration-300">Características</a></li>
                        <li><a href="{{ route('developers') }}" class="text-compost-200 hover:text-white transition-colors duration-300">Desarrolladores</a></li>
                        @if (Route::has('login'))
                            <li><a href="{{ route('login') }}" class="text-compost-200 hover:text-white transition-colors duration-300">Iniciar Sesión</a></li>
                        @endif
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-bold text-white mb-4">Contacto</h3>
                    <ul class="space-y-2 text-compost-200">
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-envelope text-compost-400"></i>
                            <span>info@compostcefa.com</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-phone text-compost-400"></i>
                            <span>+57 300 123 4567</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-map-marker-alt text-compost-400"></i>
                            <span>Centro de Acopio CEFA</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-compost-700 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="text-compost-300 text-sm">
                        <p>&copy; 2025 COMPOST CEFA. Sistema de Registro de Creación de Pilas de Compostaje y Manipulación de Maquinaria.</p>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-compost-300 hover:text-white transition-colors duration-300">
                            <i class="fab fa-facebook text-lg"></i>
                        </a>
                        <a href="#" class="text-compost-300 hover:text-white transition-colors duration-300">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="text-compost-300 hover:text-white transition-colors duration-300">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="text-compost-300 hover:text-white transition-colors duration-300">
                            <i class="fab fa-linkedin text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Typewriter effect for main title
        function typeWriter(element, text, speed = 100) {
            let i = 0;
            element.innerHTML = '';
            
            function type() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, speed);
                }
            }
            type();
        }

        // Initialize typewriter effects
        document.addEventListener('DOMContentLoaded', function() {
            const titleElement = document.getElementById('typewriter-title');
            const subtitleElement = document.getElementById('typewriter-subtitle');
            const descriptionElement = document.getElementById('typewriter-description');
            
            if (titleElement) {
                setTimeout(() => {
                    typeWriter(titleElement, 'COMPOST', 150);
                }, 500);
            }
            
            if (subtitleElement) {
                setTimeout(() => {
                    typeWriter(subtitleElement, 'CEFA', 200);
                }, 2000);
            }
            
            if (descriptionElement) {
                setTimeout(() => {
                    typeWriter(descriptionElement, 'Sistema de Registro de Creación de Pilas de Compostaje y Manipulación de Maquinaria', 50);
                }, 3500);
            }
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.classList.add('shadow-xl');
                header.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                header.classList.remove('shadow-xl');
                header.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });
    </script>
    </body>
</html>

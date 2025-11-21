<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Desarrolladores - COMPOST CEFA</title>

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
                        }
                    }
                }
            }
        </script>
    </head>
<body class="font-inter bg-white min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-compost-100 to-compost-200 border-b border-compost-200 fixed top-0 left-0 right-0 z-50 shadow-lg transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-seedling text-white text-xl"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-black text-compost-700">COMPOST</span>
                            <span class="text-xl font-bold text-compost-600 block -mt-1">CEFA</span>
                        </div>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('/') }}#about" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105">Acerca de</a>
                    <a href="{{ url('/') }}#modules" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105">Módulos</a>
                    <a href="{{ url('/') }}#features" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105">Características</a>
                    <a href="{{ route('developers') }}" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105 border-b-2 border-compost-600 pb-1">Desarrolladores</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-compost-600 to-compost-700 text-white px-6 py-3 rounded-xl font-bold hover:from-compost-700 hover:to-compost-800 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
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

    <!-- Developers Section -->
    <section class="pt-32 pb-20 min-h-screen bg-gradient-to-br from-compost-50 via-white to-compost-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Title -->
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-black text-compost-800 mb-6">Desarrolladores</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-compost-600 to-compost-500 mx-auto mb-8"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Conoce al equipo que desarrolló este sistema
                </p>
            </div>

            <!-- Developers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Developer 1: Juan Pablo Martinez Lievano -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6">
                        <!-- Profile Image -->
                        <div class="flex justify-center mb-6">
                            <img id="dev1-img" src="" alt="Juan Pablo Martinez Lievano" class="w-32 h-32 rounded-full object-cover object-top shadow-lg border-4 border-compost-200 cursor-pointer hover:scale-105 transition-transform duration-300" style="object-position: center top;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-32 h-32 bg-gradient-to-br from-compost-600 to-compost-700 rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:scale-105 transition-transform duration-300" style="display:none;" id="dev1-placeholder">
                                <i class="fas fa-user text-white text-5xl"></i>
                            </div>
                        </div>
                        
                        <!-- Role Badge -->
                        <div class="text-center mb-4">
                            <span class="inline-block bg-compost-100 text-compost-700 px-4 py-1 rounded-full text-sm font-semibold">
                                Aprendiz
                            </span>
                        </div>
                        
                        <!-- Name -->
                        <h3 class="text-xl font-bold text-compost-800 text-center mb-6">
                            Juan Pablo Martinez Lievano
                        </h3>
                        
                        <!-- Social Links -->
                        <div class="flex justify-center space-x-3">
                            <a href="#" target="_blank" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                            <a href="#" target="_blank" class="w-10 h-10 bg-gray-800 hover:bg-gray-900 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md">
                                <i class="fab fa-github text-sm"></i>
                            </a>
                            <a href="#" target="_blank" class="w-10 h-10 bg-blue-500 hover:bg-blue-600 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md">
                                <i class="fab fa-facebook-f text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Developer 2: Placeholder -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6">
                        <!-- Profile Image -->
                        <div class="flex justify-center mb-6">
                            <img id="dev2-img" src="" alt="Desarrollador 2" class="w-32 h-32 rounded-full object-cover object-top shadow-lg border-4 border-compost-200 cursor-pointer hover:scale-105 transition-transform duration-300" style="object-position: center top;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-32 h-32 bg-gradient-to-br from-compost-400 to-compost-500 rounded-full flex items-center justify-center shadow-lg opacity-60 cursor-pointer hover:scale-105 transition-transform duration-300" style="display:none;" id="dev2-placeholder">
                                <i class="fas fa-user text-white text-5xl"></i>
                            </div>
                        </div>
                        
                        <!-- Role Badge -->
                        <div class="text-center mb-4">
                            <span class="inline-block bg-compost-100 text-compost-700 px-4 py-1 rounded-full text-sm font-semibold">
                                Aprendiz
                            </span>
                        </div>
                        
                        <!-- Name -->
                        <h3 class="text-xl font-bold text-compost-800 text-center mb-6">
                            [Nombre del Desarrollador]
                        </h3>
                        
                        <!-- Social Links -->
                        <div class="flex justify-center space-x-3">
                            <a href="#" target="_blank" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                            <a href="#" target="_blank" class="w-10 h-10 bg-gray-800 hover:bg-gray-900 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md">
                                <i class="fab fa-github text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Developer 3: Placeholder -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6">
                        <!-- Profile Image -->
                        <div class="flex justify-center mb-6">
                            <img id="dev3-img" src="" alt="Desarrollador 3" class="w-32 h-32 rounded-full object-cover object-top shadow-lg border-4 border-compost-200 cursor-pointer hover:scale-105 transition-transform duration-300" style="object-position: center top;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-32 h-32 bg-gradient-to-br from-compost-400 to-compost-500 rounded-full flex items-center justify-center shadow-lg opacity-60 cursor-pointer hover:scale-105 transition-transform duration-300" style="display:none;" id="dev3-placeholder">
                                <i class="fas fa-user text-white text-5xl"></i>
                            </div>
                        </div>
                        
                        <!-- Role Badge -->
                        <div class="text-center mb-4">
                            <span class="inline-block bg-compost-100 text-compost-700 px-4 py-1 rounded-full text-sm font-semibold">
                                Aprendiz
                            </span>
                        </div>
                        
                        <!-- Name -->
                        <h3 class="text-xl font-bold text-compost-800 text-center mb-6">
                            [Nombre del Desarrollador]
                        </h3>
                        
                        <!-- Social Links -->
                        <div class="flex justify-center space-x-3">
                            <a href="#" target="_blank" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                            <a href="#" target="_blank" class="w-10 h-10 bg-gray-800 hover:bg-gray-900 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md">
                                <i class="fab fa-github text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Developer 4: Placeholder -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6">
                        <!-- Profile Image -->
                        <div class="flex justify-center mb-6">
                            <img id="dev4-img" src="" alt="Desarrollador 4" class="w-32 h-32 rounded-full object-cover object-top shadow-lg border-4 border-compost-200 cursor-pointer hover:scale-105 transition-transform duration-300" style="object-position: center top;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-32 h-32 bg-gradient-to-br from-compost-400 to-compost-500 rounded-full flex items-center justify-center shadow-lg opacity-60 cursor-pointer hover:scale-105 transition-transform duration-300" style="display:none;" id="dev4-placeholder">
                                <i class="fas fa-user text-white text-5xl"></i>
                            </div>
                        </div>
                        
                        <!-- Role Badge -->
                        <div class="text-center mb-4">
                            <span class="inline-block bg-compost-100 text-compost-700 px-4 py-1 rounded-full text-sm font-semibold">
                                Aprendiz
                            </span>
                        </div>
                        
                        <!-- Name -->
                        <h3 class="text-xl font-bold text-compost-800 text-center mb-6">
                            [Nombre del Desarrollador]
                        </h3>
                        
                        <!-- Social Links -->
                        <div class="flex justify-center space-x-3">
                            <a href="#" target="_blank" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                            <a href="#" target="_blank" class="w-10 h-10 bg-gray-800 hover:bg-gray-900 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md">
                                <i class="fab fa-github text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Credits Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Title -->
            <div class="text-center mb-16">
                <h2 class="text-5xl md:text-6xl font-black text-compost-800 mb-6">Créditos</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-compost-600 to-compost-500 mx-auto mb-8"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Tecnologías y herramientas utilizadas en el desarrollo de este sistema
                </p>
            </div>

            <!-- Technologies Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Laravel -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/laravel.png') }}" alt="Laravel" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-red-600 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fab fa-laravel text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">Laravel</h3>
                        <p class="text-sm text-gray-600 mb-4">v12.0</p>
                        <a href="https://laravel.com" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- PHP -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/php.png') }}" alt="PHP" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-indigo-600 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fab fa-php text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">PHP</h3>
                        <p class="text-sm text-gray-600 mb-4">v8.2+</p>
                        <a href="https://www.php.net" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- MySQL -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/mysql.png') }}" alt="MySQL" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-blue-600 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fas fa-database text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">MySQL</h3>
                        <p class="text-sm text-gray-600 mb-4">v8.0</p>
                        <a href="https://www.mysql.com" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- AdminLTE -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/adminlte.png') }}" alt="AdminLTE" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-blue-500 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fas fa-cube text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">AdminLTE</h3>
                        <p class="text-sm text-gray-600 mb-4">v3.2.0</p>
                        <a href="https://adminlte.io" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Font Awesome -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/fontawesome.png') }}" alt="Font Awesome" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-blue-700 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fab fa-font-awesome text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">Font Awesome</h3>
                        <p class="text-sm text-gray-600 mb-4">v6.4.0</p>
                        <a href="https://fontawesome.com" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- SweetAlert2 -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/sweetalert2.png') }}" alt="SweetAlert2" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-pink-500 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fas fa-bell text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">SweetAlert2</h3>
                        <p class="text-sm text-gray-600 mb-4">v11</p>
                        <a href="https://sweetalert2.github.io" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- JavaScript -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/javascript.png') }}" alt="JavaScript" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-yellow-400 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <span class="text-black font-bold text-2xl">JS</span>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">JavaScript</h3>
                        <p class="text-sm text-gray-600 mb-4">ES6+</p>
                        <a href="https://developer.mozilla.org/es/docs/Web/JavaScript" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Tailwind CSS -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/tailwindcss.png') }}" alt="Tailwind CSS" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-cyan-500 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fab fa-css3-alt text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">Tailwind CSS</h3>
                        <p class="text-sm text-gray-600 mb-4">v3.4.17</p>
                        <a href="https://tailwindcss.com" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- CSS -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/css.png') }}" alt="CSS" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-blue-500 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fab fa-css3 text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">CSS</h3>
                        <p class="text-sm text-gray-600 mb-4">CSS3</p>
                        <a href="https://developer.mozilla.org/es/docs/Web/CSS" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Laragon -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/laragon.png') }}" alt="Laragon" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-orange-500 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fas fa-server text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">Laragon</h3>
                        <p class="text-sm text-gray-600 mb-4">Servidor Local</p>
                        <a href="https://laragon.org" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- VS Code -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/vscode.png') }}" alt="VS Code" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-blue-600 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fas fa-code text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">VS Code</h3>
                        <p class="text-sm text-gray-600 mb-4">Editor de Código</p>
                        <a href="https://code.visualstudio.com" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Cursor -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/cursor.png') }}" alt="Cursor" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-purple-600 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fas fa-mouse-pointer text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">Cursor</h3>
                        <p class="text-sm text-gray-600 mb-4">Editor de Código</p>
                        <a href="https://cursor.sh" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- DataTables -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/datatables.png') }}" alt="DataTables" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-blue-600 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fas fa-table text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">DataTables</h3>
                        <p class="text-sm text-gray-600 mb-4">Plataforma</p>
                        <a href="https://datatables.net" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- GitHub -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/github.png') }}" alt="GitHub" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-gray-800 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fab fa-github text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">GitHub</h3>
                        <p class="text-sm text-gray-600 mb-4">Repositorio</p>
                        <a href="https://github.com" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <span>Más Info</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Back to Home Button -->
            <div class="text-center mt-16">
                <a href="{{ url('/') }}" class="inline-flex items-center space-x-2 bg-gradient-to-r from-compost-600 to-compost-700 text-white px-8 py-4 rounded-xl font-bold text-lg hover:from-compost-700 hover:to-compost-800 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver al Inicio</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-compost-800 to-compost-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-compost-600 to-compost-700 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-seedling text-white text-xl"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-black text-white">COMPOST</span>
                            <span class="text-xl font-bold text-compost-300 block -mt-1">CEFA</span>
                        </div>
                    </div>
                    <p class="text-compost-200 leading-relaxed max-w-md">
                        Sistema integral de registro para la creación de pilas de compostaje y manipulación de maquinaria. 
                        Optimizando procesos para un futuro más sostenible.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-bold text-white mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}#about" class="text-compost-200 hover:text-white transition-colors duration-300">Acerca de</a></li>
                        <li><a href="{{ url('/') }}#modules" class="text-compost-200 hover:text-white transition-colors duration-300">Módulos</a></li>
                        <li><a href="{{ url('/') }}#features" class="text-compost-200 hover:text-white transition-colors duration-300">Características</a></li>
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

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center" onclick="closeImageModal(event)">
        <!-- Backdrop with blur -->
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-md"></div>
        
        <!-- Modal Content -->
        <div class="relative z-10 max-w-4xl max-h-[90vh] w-full mx-4 flex items-center justify-center" onclick="event.stopPropagation()">
            <!-- Close Button -->
            <button onclick="closeImageModal()" class="absolute -top-12 right-0 text-white hover:text-compost-300 transition-colors duration-300 z-20">
                <i class="fas fa-times text-3xl bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75"></i>
            </button>
            
            <!-- Image Container -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <img id="modalImage" src="" alt="Imagen del desarrollador" class="max-w-full max-h-[85vh] w-auto h-auto object-contain">
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Function to load image with multiple format support
        function loadDeveloperImage(imgElement, baseName, altText) {
            const formats = ['jpeg', 'jpg', 'png', 'webp', 'gif'];
            // Use asset helper to get the base path
            const developersPath = '{{ asset("img/developers") }}';
            let currentFormat = 0;
            
            function tryNextFormat() {
                if (currentFormat >= formats.length) {
                    // All formats failed, show placeholder
                    imgElement.style.display = 'none';
                    const placeholder = imgElement.nextElementSibling;
                    if (placeholder) {
                        placeholder.style.display = 'flex';
                    }
                    return;
                }
                
                const format = formats[currentFormat];
                // Ensure proper path construction with slash
                const imagePath = developersPath.replace(/\/$/, '') + '/' + baseName + '.' + format;
                const testImg = new Image();
                
                testImg.onload = function() {
                    // Set image source and attributes
                    imgElement.src = imagePath;
                    imgElement.alt = altText;
                    
                    // Ensure image is visible
                    imgElement.style.display = 'block';
                    
                    // Hide placeholder if it exists
                    const placeholder = imgElement.nextElementSibling;
                    if (placeholder && placeholder.classList.contains('bg-gradient-to-br')) {
                        placeholder.style.display = 'none';
                    }
                    
                    // Set click handler to open modal
                    imgElement.onclick = function() {
                        openImageModal(imagePath);
                    };
                };
                
                testImg.onerror = function() {
                    currentFormat++;
                    tryNextFormat();
                };
                
                testImg.src = imagePath;
            }
            
            tryNextFormat();
        }

        // Load developer images on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Developer 1: Juan Pablo Martinez
            const dev1Img = document.getElementById('dev1-img');
            if (dev1Img) {
                loadDeveloperImage(dev1Img, 'juan-pablo-martinez', 'Juan Pablo Martinez Lievano');
            }
            
            // Developer 2
            const dev2Img = document.getElementById('dev2-img');
            if (dev2Img) {
                loadDeveloperImage(dev2Img, 'desarrollador-2', 'Desarrollador 2');
            }
            
            // Developer 3
            const dev3Img = document.getElementById('dev3-img');
            if (dev3Img) {
                loadDeveloperImage(dev3Img, 'desarrollador-3', 'Desarrollador 3');
            }
            
            // Developer 4
            const dev4Img = document.getElementById('dev4-img');
            if (dev4Img) {
                loadDeveloperImage(dev4Img, 'desarrollador-4', 'Desarrollador 4');
            }
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

        // Open Image Modal
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            
            modalImage.src = imageSrc;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        // Close Image Modal
        function closeImageModal(event) {
            // If event is provided and clicked element is not the modal content, close
            if (event && event.target.id === 'imageModal') {
                const modal = document.getElementById('imageModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            } else if (!event) {
                // Called directly from button
                const modal = document.getElementById('imageModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('imageModal');
                if (!modal.classList.contains('hidden')) {
                    closeImageModal();
                }
            }
        });
    </script>
</body>
</html>


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="format-detection" content="telephone=no">
        <meta name="theme-color" content="#16a34a">
        <title>Desarrolladores - COMPOST CEFA</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('img/logo-compost-cefa.webp') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Developer CSS -->
        <link rel="stylesheet" href="{{ asset('css/developer.css') }}">
        
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
                    <a href="{{ url('/') }}" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105">Inicio</a>
                    <a href="{{ url('/') }}#about" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105">Acerca de</a>
                    <a href="{{ url('/') }}#modules" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105">Módulos</a>
                    <a href="{{ url('/') }}#features" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105">Características</a>
                    <a href="{{ route('soporte') }}" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105"><i class="fas fa-question-circle mr-1"></i>Ayuda</a>
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
                <h1 class="scroll-animated-title text-5xl md:text-6xl font-black text-compost-800 mb-6">Desarrolladores</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-compost-600 to-compost-500 mx-auto mb-8"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Conoce al equipo que desarrolló este sistema
                </p>
            </div>

            <!-- Developers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Developer 1: Juan Pablo Martinez Lievano -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden developer-card scroll-reveal" style="transition-delay: 0.1s;">
                    <div class="p-6">
                        <!-- Profile Image -->
                        <div class="flex justify-center mb-6 relative">
                            <picture>
                                <source srcset="{{ asset('img/developers/juan-pablo-martinez.webp') }}" type="image/webp">
                                <img id="dev1-img" src="{{ asset('img/developers/juan-pablo-martinez.jpeg') }}" alt="Juan Pablo Martinez Lievano" class="w-40 h-40 rounded-full object-cover object-top shadow-lg border-4 border-compost-200 cursor-pointer hover:scale-105 transition-transform duration-300 developer-image" onclick="openImageModal(this.currentSrc || this.src)" onerror="this.style.display='none'; this.parentElement.nextElementSibling.classList.add('show');" loading="eager" decoding="async">
                            </picture>
                            <div class="w-40 h-40 bg-gradient-to-br from-compost-600 to-compost-700 rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:scale-105 transition-transform duration-300 developer-placeholder absolute top-0 left-1/2 transform -translate-x-1/2" id="dev1-placeholder">
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
                        <h3 class="text-xl font-bold text-compost-800 text-center mb-1 break-words">
                            Juan Pablo Martinez Lievano
                        </h3>
                        <p class="text-center mb-2 text-sm font-semibold bg-gradient-to-r from-compost-600 to-compost-800 bg-clip-text text-transparent">
                            Desarrollador de Software
                        </p>
                        <p class="text-xs text-gray-500 text-justify mb-6 leading-relaxed px-2">
                            Encargado del Frontend, diseño y la experiencia de usuario (UI/UX) del sistema.
                        </p>
                        
                        <!-- Social Links -->
                        <div class="flex justify-center space-x-3">
                            <a href="https://www.linkedin.com/in/juan-pablo-martinez-bb8310368/" target="_blank" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md social-link">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                            <a href="https://github.com/PabloMartinez29" target="_blank" class="w-10 h-10 bg-gray-800 hover:bg-gray-900 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md social-link">
                                <i class="fab fa-github text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Developer 2: Ivan Dario Perdomo Perez -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden developer-card scroll-reveal" style="transition-delay: 0.25s;">
                    <div class="p-6">
                        <!-- Profile Image -->
                        <div class="flex justify-center mb-6 relative">
                            <picture>
                                <source srcset="{{ asset('img/developers/Ivan-Dario-Perdomo-Perez.webp') }}" type="image/webp">
                                <img id="dev2-img" src="{{ asset('img/developers/Ivan-Dario-Perdomo-Perez.png') }}" alt="Ivan Dario Perdomo Perez" class="w-40 h-40 rounded-full object-cover object-top shadow-lg border-4 border-compost-200 cursor-pointer hover:scale-105 transition-transform duration-300 developer-image" onclick="openImageModal(this.currentSrc || this.src)" onerror="this.style.display='none'; this.parentElement.nextElementSibling.classList.add('show');" loading="eager" decoding="async">
                            </picture>
                            <div class="w-40 h-40 bg-gradient-to-br from-compost-600 to-compost-700 rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:scale-105 transition-transform duration-300 developer-placeholder absolute top-0 left-1/2 transform -translate-x-1/2" id="dev2-placeholder">
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
                        <h3 class="text-xl font-bold text-compost-800 text-center mb-1 break-words">
                            Iván Darío Perdomo Pérez
                        </h3>
                        <p class="text-center mb-2 text-sm font-semibold bg-gradient-to-r from-compost-600 to-compost-800 bg-clip-text text-transparent">
                            Desarrollador de Software
                        </p>
                        <p class="text-xs text-gray-500 text-justify mb-6 leading-relaxed px-2">
                            Encargado del Backend y la construcción de la lógica interna y bases de datos.
                        </p>
                        
                        <!-- Social Links -->
                        <div class="flex justify-center space-x-3">
                            <a href="https://www.linkedin.com/in/ivan-perdomo-2a898039a/" target="_blank" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md social-link">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                            <a href="https://github.com/perdomoivan" target="_blank" class="w-10 h-10 bg-gray-800 hover:bg-gray-900 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md social-link">
                                <i class="fab fa-github text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Developer 3: Anggie Lizeth Anaya Perdomo -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden developer-card scroll-reveal" style="transition-delay: 0.4s;">
                    <div class="p-6">
                        <!-- Profile Image -->
                        <div class="flex justify-center mb-6 relative">
                            <picture>
                                <source srcset="{{ asset('img/developers/Anggie-Anaya.webp') }}" type="image/webp">
                                <img id="dev3-img" src="{{ asset('img/developers/Anggie-Anaya.png') }}" alt="Anggie Lizeth Anaya Perdomo" class="w-40 h-40 rounded-full object-cover object-top shadow-lg border-4 border-compost-200 cursor-pointer hover:scale-105 transition-transform duration-300 developer-image" onclick="openImageModal(this.currentSrc || this.src)" onerror="this.style.display='none'; this.parentElement.nextElementSibling.classList.add('show');" loading="eager" decoding="async">
                            </picture>
                            <div class="w-40 h-40 bg-gradient-to-br from-compost-600 to-compost-700 rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:scale-105 transition-transform duration-300 developer-placeholder absolute top-0 left-1/2 transform -translate-x-1/2" id="dev3-placeholder">
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
                        <h3 class="text-xl font-bold text-compost-800 text-center mb-1 break-words">
                            Anggie Lizeth Anaya Perdomo
                        </h3>
                        <p class="text-center mb-2 text-sm font-semibold bg-gradient-to-r from-compost-600 to-compost-800 bg-clip-text text-transparent">
                            Desarrolladora de Software
                        </p>
                        <p class="text-xs text-gray-500 text-justify mb-6 leading-relaxed px-2">
                            Encargada del análisis y levantamiento de requerimientos del sistema.
                        </p>
                        
                        <!-- Social Links -->
                        <div class="flex justify-center space-x-3">
                            <a href="https://www.linkedin.com/in/anggie-anaya-00187639a/" target="_blank" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md social-link">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                            <a href="https://github.com/anggieanaya16" target="_blank" class="w-10 h-10 bg-gray-800 hover:bg-gray-900 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md social-link">
                                <i class="fab fa-github text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Developer 4: Juan Andres Almanza Salinas  -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden developer-card scroll-reveal" style="transition-delay: 0.55s;">
                    <div class="p-6">
                        <!-- Profile Image -->
                        <div class="flex justify-center mb-6 relative">
                            <picture>
                                <source srcset="{{ asset('img/developers/Juan-Almanza.webp') }}" type="image/webp">
                                <img id="dev4-img" src="{{ asset('img/developers/Juan-Almanza.png') }}" alt="Juan Andres Almanza Salinas" class="w-40 h-40 rounded-full object-cover object-top shadow-lg border-4 border-compost-200 cursor-pointer hover:scale-105 transition-transform duration-300 developer-image" onclick="openImageModal(this.currentSrc || this.src)" onerror="this.style.display='none'; this.parentElement.nextElementSibling.classList.add('show');" loading="eager" decoding="async">
                            </picture>
                            <div class="w-40 h-40 bg-gradient-to-br from-compost-600 to-compost-700 rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:scale-105 transition-transform duration-300 developer-placeholder absolute top-0 left-1/2 transform -translate-x-1/2" id="dev4-placeholder">
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
                        <h3 class="text-xl font-bold text-compost-800 text-center mb-1 break-words">
                            Juan Andres Almanza Salinas 
                        </h3>
                        <p class="text-center mb-2 text-sm font-semibold bg-gradient-to-r from-compost-600 to-compost-800 bg-clip-text text-transparent">
                            Desarrollador de Software
                        </p>
                        <p class="text-xs text-gray-500 text-justify mb-6 leading-relaxed px-2">
                            Encargado de la ejecución de pruebas y validación de calidad de código y funcionalidad.
                        </p>
                        
                        <!-- Social Links -->
                        <div class="flex justify-center space-x-3">
                            <a href="https://www.linkedin.com/in/juan-andres-almanza-salinas-80b87639a/" target="_blank" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md social-link">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                            <a href="https://github.com/Almanza310" target="_blank" class="w-10 h-10 bg-gray-800 hover:bg-gray-900 rounded-lg flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md social-link">
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
                <h2 class="scroll-animated-title text-5xl md:text-6xl font-black text-compost-800 mb-6">Créditos</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-compost-600 to-compost-500 mx-auto mb-8"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-6">
                    Tecnologías y herramientas utilizadas en el desarrollo de este sistema
                </p>
                <!-- Toggle Arrow -->
                <div class="flex justify-center">
                    <button id="creditsToggle" onclick="toggleCredits()" class="text-compost-600 hover:text-compost-700 transition-all duration-300 transform hover:scale-110 animate-bounce cursor-pointer">
                        <i id="creditsArrow" class="fas fa-chevron-down text-4xl"></i>
                    </button>
                </div>
            </div>

            <!-- Technologies Grid -->
            <div id="creditsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 hidden">
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

                <!-- Alpine.js -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/alpinejs.png') }}" alt="Alpine.js" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-teal-500 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fas fa-mountain text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">Alpine.js</h3>
                        <p class="text-sm text-gray-600 mb-4">v3.4.2</p>
                        <a href="https://alpinejs.dev" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
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

                <!-- Vite -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/vite.png') }}" alt="Vite" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-purple-600 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fas fa-bolt text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">Vite</h3>
                        <p class="text-sm text-gray-600 mb-4">v7.0.4</p>
                        <a href="https://vitejs.dev" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
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

                <!-- DomPDF -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-compost-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/credits/dompdf.png') }}" alt="DomPDF" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-red-600 rounded-lg flex items-center justify-center shadow-md" style="display:none;">
                                <i class="fas fa-file-pdf text-white text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-compost-800 mb-2">DomPDF</h3>
                        <p class="text-sm text-gray-600 mb-4">Generación de Reportes</p>
                        <a href="https://github.com/dompdf/dompdf" target="_blank" class="inline-flex items-center justify-center space-x-2 w-full bg-compost-600 hover:bg-compost-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
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
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-compost-800 to-compost-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-lg font-bold text-white mb-4">Ubicación</h3>
                    <!-- Iframe de Google Maps público: q=coordenadas | z=zoom | t=k indica vista satélite -->
                    <div class="rounded-lg overflow-hidden shadow-lg" style="height: 300px;">
                        <iframe 
                            src="https://www.google.com/maps?q=2.61361,-75.36111&hl=es&z=15&t=k&output=embed"
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
                        <p>&copy; Copyright 2025 COMPOST CEFA. Todos los derechos reservados.</p>
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
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.classList.add('shadow-xl', 'scrolled');
            } else {
                header.classList.remove('shadow-xl', 'scrolled');
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

        // Toggle Credits Section
        function toggleCredits() {
            const creditsGrid = document.getElementById('creditsGrid');
            const creditsArrow = document.getElementById('creditsArrow');
            const creditsToggle = document.getElementById('creditsToggle');
            
            if (creditsGrid.classList.contains('hidden')) {
                // Show credits
                creditsGrid.classList.remove('hidden');
                creditsGrid.classList.add('animate-fadeIn');
                creditsArrow.classList.remove('fa-chevron-down');
                creditsArrow.classList.add('fa-chevron-up');
                creditsToggle.classList.remove('animate-bounce');
            } else {
                // Hide credits
                creditsGrid.classList.add('hidden');
                creditsArrow.classList.remove('fa-chevron-up');
                creditsArrow.classList.add('fa-chevron-down');
                creditsToggle.classList.add('animate-bounce');
            }
        }

        // Scroll animations (IntersectionObserver)
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.scroll-animated-title, .scroll-reveal');
            if (!animatedElements.length) return;

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in-view');
                        }
                    });
                }, {
                    threshold: 0.15,
                    rootMargin: '0px 0px -10% 0px'
                });

                animatedElements.forEach(el => observer.observe(el));
            } else {
                animatedElements.forEach(el => el.classList.add('in-view'));
            }
        });
    </script>
</body>
</html>


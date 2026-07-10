<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="format-detection" content="telephone=no">
        <meta name="theme-color" content="#16a34a">
        <title>Ayuda - COMPOST CEFA</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('img/logo-compost-cefa.webp') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'compost': {
                                50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac',
                                400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d',
                                800: '#166534', 900: '#14532d',
                            }
                        },
                        fontFamily: { 'inter': ['Inter', 'sans-serif'] }
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
                    <a href="{{ route('soporte') }}" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105 border-b-2 border-compost-600 pb-1"><i class="fas fa-question-circle mr-1"></i>Ayuda</a>
                    <a href="{{ route('developers') }}" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105">Desarrolladores</a>
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

    <!-- Main Section -->
    <section class="pt-32 pb-20 min-h-screen bg-gradient-to-br from-compost-50 via-white to-compost-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex items-center">
                    <i class="fas fa-check-circle text-xl mr-3"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="text-center mb-12">
                <h1 class="scroll-animated-title text-5xl md:text-6xl font-black text-compost-800 mb-6"><i class="fas fa-question-circle mr-3"></i>Ayuda</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-compost-600 to-compost-50
                20 mx-auto mb-8"></div>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Aquí encuentras todo lo que necesitas para usar el sistema con confianza.
                </p>
            </div>


            <!-- Manuales en PDF -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 max-w-3xl mx-auto">
                <!-- Manual de Aprendiz -->
                @if($hasManualAprendiz)
                    <a href="{{ route('manual.view', 'aprendiz') }}" target="_blank" rel="noopener"
                       class="scroll-reveal bg-white rounded-2xl shadow-xl border border-compost-100 p-8 md:p-10 text-center hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group cursor-pointer block" style="transition-delay: 0.15s;">
                        <div class="w-24 h-24 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-red-200 transition-colors duration-300">
                            <i class="fas fa-file-pdf text-red-600 text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-compost-800 mb-2">Manual de Aprendiz</h3>
                        <p class="text-sm text-gray-500">Haz clic para abrir</p>
                    </a>
                @else
                    <div class="scroll-reveal bg-white rounded-2xl shadow-xl border border-compost-100 p-8 md:p-10 text-center opacity-60" style="transition-delay: 0.15s;">
                        <div class="w-24 h-24 bg-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-file-pdf text-gray-400 text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-compost-800 mb-2">Manual de Aprendiz</h3>
                        <p class="text-sm text-gray-500">Aún no disponible</p>
                    </div>
                @endif

                <!-- Manual de Administrador -->
                @if($hasManualAdmin)
                    <a href="{{ route('manual.view', 'administrador') }}" target="_blank" rel="noopener"
                       class="scroll-reveal bg-white rounded-2xl shadow-xl border border-compost-100 p-8 md:p-10 text-center hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group cursor-pointer block" style="transition-delay: 0.35s;">
                        <div class="w-24 h-24 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-red-200 transition-colors duration-300">
                            <i class="fas fa-file-pdf text-red-600 text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-compost-800 mb-2">Manual de Administrador</h3>
                        <p class="text-sm text-gray-500">Haz clic para abrir</p>
                    </a>
                @else
                    <div class="scroll-reveal bg-white rounded-2xl shadow-xl border border-compost-100 p-8 md:p-10 text-center opacity-60" style="transition-delay: 0.35s;">
                        <div class="w-24 h-24 bg-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-file-pdf text-gray-400 text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-compost-800 mb-2">Manual de Administrador</h3>
                        <p class="text-sm text-gray-500">Aún no disponible</p>
                    </div>
                @endif
            </div>

            <!-- Sección de subida para administradores -->
            @auth
                @if(Auth::user()->role === 'admin')
                    <div class="bg-white rounded-2xl shadow-xl border border-compost-100 overflow-hidden mt-10">
                        <div class="border-t border-compost-100 bg-compost-50/50 p-6 md:p-8">
                            <h4 class="text-lg font-bold text-compost-800 mb-3 flex items-center">
                                <i class="fas fa-cloud-upload-alt text-compost-600 mr-2"></i>
                                Actualizar manuales (solo administradores)
                            </h4>
                            <p class="text-sm text-gray-600 mb-4">Sube un archivo PDF. Selecciona el tipo de manual a reemplazar.</p>
                            <form action="{{ route('soporte.upload') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-4 items-start">
                                @csrf
                                <select name="type" required class="block w-full sm:w-auto text-sm text-gray-700 border border-compost-300 rounded-lg px-4 py-2.5 bg-white focus:ring-2 focus:ring-compost-500 focus:border-compost-500">
                                    <option value="" disabled selected>Seleccionar manual…</option>
                                    <option value="aprendiz">Manual de Aprendiz</option>
                                    <option value="admin">Manual de Administrador</option>
                                    <option value="tecnico">Manual Técnico</option>
                                </select>
                                <input type="file" name="manual" accept=".pdf" required
                                       class="block w-full sm:w-auto text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-compost-600 file:text-white file:font-semibold hover:file:bg-compost-700">
                                <button type="submit" class="inline-flex items-center justify-center space-x-2 bg-compost-600 hover:bg-compost-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 shrink-0">
                                    <i class="fas fa-upload"></i>
                                    <span>Subir manual</span>
                                </button>
                            </form>
                            @error('manual')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                            @error('type')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </section>

    <style>
        .scroll-animated-title {
            display: inline-block;
            position: relative;
            opacity: 0;
            transform: translate3d(0, 60px, -30px) scale(0.92) rotateX(18deg);
            letter-spacing: 0.35em;
            filter: blur(6px);
            transition:
                opacity 0.85s cubic-bezier(0.19, 1, 0.22, 1),
                transform 0.85s cubic-bezier(0.19, 1, 0.22, 1),
                letter-spacing 0.85s cubic-bezier(0.19, 1, 0.22, 1),
                filter 0.85s ease;
        }
        .scroll-animated-title::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -14px;
            width: 0;
            height: 4px;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            box-shadow: 0 0 18px rgba(34, 197, 94, 0.5);
            transition: width 0.7s ease, left 0.7s ease;
        }
        .scroll-animated-title.in-view {
            opacity: 1;
            transform: translate3d(0, 0, 0) scale(1) rotateX(0deg);
            letter-spacing: 0.02em;
            filter: blur(0);
            text-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }
        .scroll-animated-title.in-view::after {
            width: 140px;
            left: calc(50% - 70px);
        }
        .scroll-reveal {
            opacity: 0;
            transform: translateY(50px) scale(0.95);
            filter: blur(4px);
            transition:
                opacity 0.7s cubic-bezier(0.19, 1, 0.22, 1),
                transform 0.7s cubic-bezier(0.19, 1, 0.22, 1),
                filter 0.7s ease;
        }
        .scroll-reveal.in-view {
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: blur(0);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const els = document.querySelectorAll('.scroll-animated-title, .scroll-reveal');
            if (!els.length) return;
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) entry.target.classList.add('in-view');
                    });
                }, { threshold: 0.15, rootMargin: '0px 0px -10% 0px' });
                els.forEach(el => observer.observe(el));
            } else {
                els.forEach(el => el.classList.add('in-view'));
            }
        });
    </script>
</body>
</html>

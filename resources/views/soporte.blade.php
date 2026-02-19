<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Soporte - Manual de Usuario | COMPOST CEFA</title>

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
                    <a href="{{ route('soporte') }}" class="text-compost-700 hover:text-compost-800 font-semibold transition-all duration-300 hover:scale-105 border-b-2 border-compost-600 pb-1">Soporte</a>
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
                <h1 class="text-5xl md:text-6xl font-black text-compost-800 mb-6">Soporte</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-compost-600 to-compost-50
                20 mx-auto mb-8"></div>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Aquí encuentras todo lo que necesitas para usar el sistema con confianza.
                </p>
            </div>

            <!-- Descripción atractiva -->
            <div class="bg-white/80 backdrop-blur rounded-2xl shadow-xl border border-compost-100 p-8 md:p-10 mb-10 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-compost-500 to-compost-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-book-open text-white text-4xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-compost-800 mb-4">Manual de Usuario</h2>
                <p class="text-gray-600 text-lg leading-relaxed max-w-xl mx-auto">
                    Guía paso a paso para administradores y aprendices: registro de residuos, pilas de compostaje,
                    maquinaria, seguimiento y más. Descárgalo cuando quieras y consúltalo sin conexión.
                </p>
            </div>

            <!-- Card: Descargar / Estado -->
            <div class="bg-white rounded-2xl shadow-xl border border-compost-100 overflow-hidden">
                <div class="p-8 md:p-10">
                    @if($hasManual)
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                            <div class="w-24 h-24 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-file-pdf text-red-600 text-5xl"></i>
                            </div>
                            <div class="text-center sm:text-left">
                                <h3 class="text-xl font-bold text-compost-800 mb-1">Manual de usuario (PDF)</h3>
                                <p class="text-gray-600 text-sm mb-4">Descarga la guía y tenla a mano en tu equipo.</p>
                                <a href="{{ asset($manualUrl) }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center justify-center space-x-2 bg-compost-600 hover:bg-compost-700 text-white px-6 py-3 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-download"></i>
                                    <span>Descargar manual</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="w-20 h-20 bg-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-file-pdf text-gray-400 text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-compost-800 mb-2">Manual de usuario</h3>
                            <p class="text-gray-600">El manual estará disponible aquí una vez que se haya subido.</p>
                        </div>
                    @endif
                </div>

                @auth
                    @if(Auth::user()->role === 'admin')
                        <div class="border-t border-compost-100 bg-compost-50/50 p-6 md:p-8">
                            <h4 class="text-lg font-bold text-compost-800 mb-3 flex items-center">
                                <i class="fas fa-cloud-upload-alt text-compost-600 mr-2"></i>
                                Actualizar manual (solo administradores)
                            </h4>
                            <p class="text-sm text-gray-600 mb-4">Sube un archivo PDF. Reemplazará el manual actual si ya existe.</p>
                            <form action="{{ route('soporte.upload') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-4 items-start">
                                @csrf
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
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </section>
</body>
</html>

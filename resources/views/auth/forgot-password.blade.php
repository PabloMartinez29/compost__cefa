<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Olvidé mi Contraseña</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

    <!-- Custom Auth CSS -->
    @vite(['resources/css/auth.css'])
</head>

<body class="bg-gradient-to-br from-green-50 via-green-100 to-green-200 min-h-screen font-sans">
    <!-- Back to Home Button -->
    <div class="absolute top-6 right-6 z-10">
        <a href="{{ route('login') }}" class="back-button">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full fade-in-up">
            <!-- Main Container -->
            <div class="auth-container">
                <div class="flex">
                    <!-- Left Side - Image -->
                    <div class="auth-side-panel">
                        <div class="absolute inset-0">
                            <img src="{{ asset('img/auth/forgot-password.png') }}"
                                 alt="Recuperar contraseña"
                                 class="w-full h-full object-cover">
                        </div>
                    </div>

                    <!-- Right Side - Forgot Password Form -->
                    <div class="auth-form-panel">
                        <div class="text-center mb-6">
                            <div class="auth-logo">
                                <i class="fas fa-unlock-alt text-white text-xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-soft-gray-800 mb-2 typewriter">COMPOST CEFA</h2>
                            <p class="text-soft-gray-600">Recupera el acceso a tu cuenta</p>
                        </div>

                        <div class="space-y-6">
                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="p-3 rounded-lg border bg-green-50 border-green-200 text-green-700">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-check-circle text-sm"></i>
                                        </div>
                                        <div class="ml-2">
                                            <p class="text-sm font-medium">Hemos enviado el enlace de restablecimiento de contraseña a tu correo.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="text-center mb-4">
                                <p class="text-soft-gray-600 text-sm">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
                            </div>

                            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                                @csrf

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-soft-gray-700 mb-2">Correo Electrónico</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-soft-gray-400"></i>
                                        </div>
                                        <input id="email" name="email" type="email" required autofocus
                                               class="auth-input"
                                               placeholder="tu@email.com">
                                    </div>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div>
                                    <button type="submit" class="auth-button">
                                        <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                            <i class="fas fa-paper-plane text-soft-green-300 group-hover:text-soft-green-200"></i>
                                        </span>
                                        Enviar enlace de restablecimiento
                                    </button>
                                </div>
                            </form>

                            <!-- Back to login -->
                            <div class="auth-divider">
                                <p class="text-sm text-soft-gray-500">
                                    ¿Recordaste tu contraseña?
                                    <a href="{{ route('login') }}" class="auth-link">Inicia sesión aquí</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

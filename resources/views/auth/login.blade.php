<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - Sistema de Compostaje</title>
    
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
    
    <style>
        /* Prevenir zoom en inputs en dispositivos móviles */
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            font-size: 16px !important;
        }
        
        /* Asegurar que el contenedor de alertas mantenga su altura */
        #alerts-container {
            min-height: 60px;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-50 via-green-100 to-green-200 min-h-screen font-sans">
    <!-- Back to Home Button -->
    <div class="absolute top-6 right-6 z-10">
        <a href="{{ url('/') }}" class="back-button">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl w-full fade-in-up">
            <!-- Main Container -->
            <div class="auth-container">
                <div class="flex">
                    <!-- Left Side - Image -->
                    <div class="auth-side-panel">
                        <!-- Background Image -->
                        <div class="absolute inset-0">
                            <img src="{{ asset('img/auth/login-bg.jpg') }}" 
                                 alt="Compostaje" 
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <!-- Right Side - Login Form -->
                    <div class="auth-form-panel">
                        <!-- Logo and Title -->
                        <div class="text-center mb-8">
                            <div class="auth-logo">
                                <i class="fas fa-seedling text-white text-2xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-soft-gray-800 mb-4 typewriter">
                                COMPOST CEFA
                            </h2>
                        </div>

                        <!-- Login Form -->
                        <div class="space-y-6">
                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-bold text-soft-gray-800 mb-2">Iniciar Sesión</h3>
                                <p class="text-soft-gray-600">Accede a tu cuenta para gestionar el compostaje</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                                @csrf
                                
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-soft-gray-700 mb-2">
                                        Correo Electrónico
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-soft-gray-400"></i>
                                        </div>
                                        <input id="email" name="email" type="email" required 
                                               class="auth-input"
                                               placeholder="tu@email.com"
                                               onblur="validateEmail(this)"
                                               autocomplete="email">
                                    </div>
                                    <div id="email-error-container" class="mt-2 min-h-[20px]">
                                        @error('email')
                                            <p class="text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-soft-gray-700 mb-2">
                                        Contraseña
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-soft-gray-400"></i>
                                        </div>
                                        <input id="password" name="password" type="password" required 
                                               class="auth-input pr-12"
                                               placeholder="••••••••">
                                        <button type="button" 
                                                onclick="togglePassword('password')"
                                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-soft-gray-400 hover:text-soft-gray-600 transition-colors duration-200">
                                            <i id="password-icon" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Remember Me and Forgot Password -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input id="remember" name="remember" type="checkbox" 
                                               class="h-4 w-4 text-soft-green-600 focus:ring-soft-green-500 border-soft-gray-300 rounded">
                                        <label for="remember" class="ml-2 block text-sm text-soft-gray-700">
                                            Recordarme
                                        </label>
                                    </div>
                                    
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="auth-link text-sm">
                                            ¿Olvidaste tu contraseña?
                                        </a>
                                    @endif
                                </div>

                            <!-- Alerts Container -->
                            <div id="alerts-container" class="space-y-2 mb-4 min-h-[60px]">
                                @if (session('success'))
                                    <div class="p-3 rounded-lg border bg-green-50 border-green-200 text-green-700">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-check-circle text-sm"></i>
                                            </div>
                                            <div class="ml-2">
                                                <p class="text-sm font-medium">{{ session('success') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="p-3 rounded-lg border bg-red-50 border-red-200 text-red-700">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-exclamation-triangle text-sm"></i>
                                            </div>
                                            <div class="ml-2">
                                                <p class="text-sm font-medium">{{ session('error') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit" class="auth-button">
                                    <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                        <i class="fas fa-sign-in-alt text-soft-green-300 group-hover:text-soft-green-200"></i>
                                    </span>
                                    Iniciar Sesión
                                </button>
                            </div>
                        </form>

                            <!-- Register Link -->
                            <div class="auth-divider">
                                <p class="text-sm text-soft-gray-500">
                                    ¿No tienes una cuenta? 
                                    <a href="{{ route('register') }}" class="auth-link">
                                        Regístrate aquí
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-remove server alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const serverAlerts = document.querySelectorAll('#alerts-container .bg-red-50, #alerts-container .bg-green-50');
            serverAlerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.parentElement) {
                        alert.remove();
                    }
                }, 5000);
            });
        });

        // Form validation without page reload
        document.querySelector('form').addEventListener('submit', function(e) {
            // Don't prevent default if there are server errors
            if (document.querySelector('#alerts-container .bg-red-50')) {
                return;
            }
            
            e.preventDefault();
            
            const formData = new FormData(this);
            const email = formData.get('email');
            const password = formData.get('password');
            
            // Clear previous alerts
            clearAlerts();
            
            let hasErrors = false;
            
            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                showAlert('Por favor ingresa un email válido', 'error');
                hasErrors = true;
            }
            
            // Validate password
            if (!password || password.length < 1) {
                showAlert('Por favor ingresa tu contraseña', 'error');
                hasErrors = true;
            }
            
            if (!hasErrors) {
                // Show success message and submit form
                showAlert('Iniciando sesión...', 'success');
                setTimeout(() => {
                    this.submit();
                }, 1000);
            }
        });

        // Function to show alerts
        function showAlert(message, type) {
            const alertsContainer = document.getElementById('alerts-container');
            
            // Limpiar alertas previas pero mantener el contenedor con altura mínima
            alertsContainer.innerHTML = '';
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `p-3 rounded-lg border transition-opacity duration-300 ${
                type === 'error' 
                    ? 'bg-red-50 border-red-200 text-red-700' 
                    : 'bg-green-50 border-green-200 text-green-700'
            }`;
            
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas ${type === 'error' ? 'fa-exclamation-triangle' : 'fa-check-circle'} text-sm"></i>
                    </div>
                    <div class="ml-2">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-auto pl-2">
                        <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Asegurar que el contenedor tenga altura mínima antes de agregar
            alertsContainer.style.minHeight = '60px';
            alertsContainer.appendChild(alertDiv);
            
            // Auto remove after 5 seconds for errors, 10 seconds for success
            const duration = type === 'error' ? 5000 : 10000;
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.style.opacity = '0';
                    setTimeout(() => {
                        if (alertDiv.parentElement) {
                            alertDiv.remove();
                        }
                    }, 300);
                }
            }, duration);
        }

        // Function to clear all alerts
        function clearAlerts() {
            const alertsContainer = document.getElementById('alerts-container');
            alertsContainer.innerHTML = '';
            // Mantener altura mínima para evitar cambios de layout
            alertsContainer.style.minHeight = '60px';
        }

        // Función para validar email personalizada
        function validateEmail(input) {
            const email = input.value.trim();
            const errorContainer = document.getElementById('email-error-container');
            
            // Limpiar errores previos
            errorContainer.innerHTML = '';
            
            // Solo validar si hay contenido
            if (email.length > 0) {
                // Validar formato de email básico
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (!email.includes('@')) {
                    // Mostrar error solo si no tiene @
                    const errorDiv = document.createElement('p');
                    errorDiv.className = 'text-sm text-red-600';
                    errorDiv.textContent = 'El campo email debe contener el símbolo @.';
                    errorContainer.appendChild(errorDiv);
                    input.classList.add('border-red-500');
                    input.classList.remove('border-soft-gray-300');
                } else if (!emailRegex.test(email)) {
                    // Validar formato completo si tiene @ pero formato incorrecto
                    const errorDiv = document.createElement('p');
                    errorDiv.className = 'text-sm text-red-600';
                    errorDiv.textContent = 'Por favor ingresa un email válido.';
                    errorContainer.appendChild(errorDiv);
                    input.classList.add('border-red-500');
                    input.classList.remove('border-soft-gray-300');
                } else {
                    // Email válido
                    input.classList.remove('border-red-500');
                    input.classList.add('border-soft-gray-300');
                }
            } else {
                // Campo vacío, resetear estilos
                input.classList.remove('border-red-500');
                input.classList.add('border-soft-gray-300');
            }
        }

        // Función para mostrar/ocultar contraseña
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>

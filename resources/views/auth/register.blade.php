<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro - Sistema de Compostaje</title>
    
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
                            <img src="{{ asset('img/auth/register-bg.jpg') }}" 
                                 alt="Compostaje" 
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <!-- Right Side - Register Form -->
                    <div class="auth-form-panel">
                        <!-- Logo and Title -->
                        <div class="text-center mb-6">
                            <div class="auth-logo">
                                <i class="fas fa-user-plus text-white text-xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-soft-gray-800 mb-2 typewriter">
                                COMPOST CEFA
                            </h2>
                            <p class="text-soft-gray-600 mb-4">Únete al sistema de gestión de compostaje</p>
                        </div>

                        <!-- Register Form -->
                        <div class="space-y-6">
                            <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
                                <div class="bounce-in" style="animation-delay: 0.1s;">
                                    <label for="name" class="block text-sm font-medium text-soft-gray-700 mb-2">
                                        Nombre Completo
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-soft-gray-400"></i>
                                        </div>
                                        <input id="name" name="name" type="text" required 
                                               class="auth-input"
                                               placeholder="Tu nombre completo">
                                    </div>
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="bounce-in" style="animation-delay: 0.2s;">
                                    <label for="email" class="block text-sm font-medium text-soft-gray-700 mb-2">
                                        Correo Electrónico
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-soft-gray-400"></i>
                                        </div>
                                        <input id="email" name="email" type="email" required 
                                               class="auth-input"
                                               placeholder="tu@email.com">
                                    </div>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
        </div>

                                <!-- Identification -->
                                <div class="bounce-in" style="animation-delay: 0.25s;">
                                    <label for="identification" class="block text-sm font-medium text-soft-gray-700 mb-2">
                                        Identificación
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-id-card text-soft-gray-400"></i>
                                        </div>
                                        <input id="identification" name="identification" type="text" required 
                                               class="auth-input"
                                               placeholder="Tu número de identificación">
                                    </div>
                                    @error('identification')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
        </div>

        <!-- Password -->
                                <div class="bounce-in" style="animation-delay: 0.35s;">
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
                                    <div class="mt-2 flex items-center space-x-2">
                                        <div class="password-strength-bar">
                                            <div class="password-strength-fill bg-soft-green-500" style="width: 0%"></div>
                                        </div>
                                        <span class="text-xs text-soft-gray-500" id="password-strength-text">Mínimo 8 caracteres</span>
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
        </div>

        <!-- Confirm Password -->
                                <div class="bounce-in" style="animation-delay: 0.45s;">
                                    <label for="password_confirmation" class="block text-sm font-medium text-soft-gray-700 mb-2">
                                        Confirmar Contraseña
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-soft-gray-400"></i>
                                        </div>
                                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                                               class="auth-input pr-12"
                                               placeholder="••••••••">
                                        <button type="button" 
                                                onclick="togglePassword('password_confirmation')"
                                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-soft-gray-400 hover:text-soft-gray-600 transition-colors duration-200">
                                            <i id="password_confirmation-icon" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="bounce-in" style="animation-delay: 0.55s;">
                                    <div class="flex items-start">
                                        <input id="terms" name="terms" type="checkbox" required
                                               class="h-4 w-4 text-soft-green-600 focus:ring-soft-green-500 border-soft-gray-300 rounded mt-1">
                                        <label for="terms" class="ml-3 block text-sm text-soft-gray-700">
                                            Acepto los <a href="#" class="auth-link">términos y condiciones</a> del sistema
                                        </label>
                                    </div>
                                </div>

                                <!-- Alerts Container -->
                                <div id="alerts-container" class="space-y-2 mb-4">
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
        </div>

                                <!-- Submit Button -->
                                <div class="bounce-in" style="animation-delay: 0.65s;">
                                    <button type="submit" class="auth-button">
                                        <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                            <i class="fas fa-user-plus text-soft-green-300 group-hover:text-soft-green-200"></i>
                                        </span>
                                        Crear Cuenta
                                    </button>
                                </div>
                            </form>

                            <!-- Login Link -->
                            <div class="auth-divider">
                                <p class="text-sm text-soft-gray-500">
                                    ¿Ya tienes una cuenta? 
                                    <a href="{{ route('login') }}" class="auth-link">
                                        Inicia sesión aquí
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
            const serverAlerts = document.querySelectorAll('#alerts-container .bg-green-50');
            serverAlerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.parentElement) {
                        alert.remove();
                    }
                }, 5000);
            });
        });

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.querySelector('.password-strength-fill');
            const strengthText = document.getElementById('password-strength-text');
            
            if (password.length < 8) {
                strengthBar.style.width = '25%';
                strengthBar.className = 'password-strength-fill bg-red-500';
                strengthText.textContent = 'Mínimo 8 caracteres';
            } else if (password.length >= 8 && password.length < 10) {
                strengthBar.style.width = '60%';
                strengthBar.className = 'password-strength-fill bg-yellow-500';
                strengthText.textContent = 'Buena contraseña';
            } else if (password.length >= 10) {
                strengthBar.style.width = '100%';
                strengthBar.className = 'password-strength-fill bg-soft-green-500';
                strengthText.textContent = 'Contraseña fuerte';
            }
        });

        // Form validation without page reload
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const password = formData.get('password');
            const passwordConfirmation = formData.get('password_confirmation');
            const email = formData.get('email');
            const name = formData.get('name');
            const identification = formData.get('identification');
            const terms = formData.get('terms');
            
            // Clear previous alerts
            clearAlerts();
            
            let hasErrors = false;
            
            // Validate name
            if (!name || name.trim().length < 2) {
                showAlert('El nombre debe tener al menos 2 caracteres', 'error');
                hasErrors = true;
            }
            
            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                showAlert('Por favor ingresa un email válido', 'error');
                hasErrors = true;
            }
            
            // Validate identification
            if (!identification || identification.trim().length < 3) {
                showAlert('La identificación debe tener al menos 3 caracteres', 'error');
                hasErrors = true;
            }
            
            // Validate password
            if (!password || password.length < 8) {
                showAlert('La contraseña debe tener al menos 8 caracteres', 'error');
                hasErrors = true;
            }
            
            // Validate password confirmation
            if (password !== passwordConfirmation) {
                showAlert('Las contraseñas no coinciden', 'error');
                hasErrors = true;
            }
            
            // Validate terms
            if (!terms) {
                showAlert('Debes aceptar los términos y condiciones', 'error');
                hasErrors = true;
            }
            
            if (!hasErrors) {
                // Show success message and submit form
                showAlert('Formulario válido, enviando...', 'success');
                setTimeout(() => {
                    this.submit();
                }, 1000);
            }
        });

        // Check if there's a success message from server (after registration)
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = '{{ session("success") }}';
            if (successMessage) {
                showAlert(successMessage, 'success');
            }
        });

        // Function to show alerts
        function showAlert(message, type) {
            const alertsContainer = document.getElementById('alerts-container');
            const alertDiv = document.createElement('div');
            alertDiv.className = `p-3 rounded-lg border ${
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
            
            alertsContainer.appendChild(alertDiv);
            
            // Auto remove after 5 seconds for errors, 10 seconds for success
            const duration = type === 'error' ? 5000 : 10000;
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.remove();
                }
            }, duration);
        }

        // Function to clear all alerts
        function clearAlerts() {
            const alertsContainer = document.getElementById('alerts-container');
            alertsContainer.innerHTML = '';
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

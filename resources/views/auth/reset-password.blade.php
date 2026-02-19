<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Restablecer Contraseña - Sistema de Compostaje</title>

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
        <div class="max-w-md w-full fade-in-up">
            <!-- Main Container -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-soft-green-100">
                <!-- Reset Password Form -->
                <div class="p-8">
                        <div class="text-center mb-6">
                            <div class="auth-logo">
                                <i class="fas fa-key text-white text-xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-soft-gray-800 mb-2 typewriter">COMPOST CEFA</h2>
                            <p class="text-soft-gray-600">Establece tu nueva contraseña</p>
                        </div>

                        <div class="space-y-6">
                            <!-- Success Message -->
                            @if (session('status'))
                                <div class="p-4 rounded-lg border bg-green-50 border-green-200 text-green-700 mb-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-check-circle text-lg"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium">¡Cambio de contraseña exitoso!</p>
                                            <p class="text-xs mt-1">Tu contraseña ha sido actualizada correctamente.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Error: enlace usado, expirado o inválido -->
                            @error('email')
                                <div class="p-4 rounded-lg border bg-amber-50 border-amber-200 text-amber-800 mb-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <i class="fas fa-exclamation-triangle text-lg"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium">Este enlace ya ha sido utilizado o ha expirado.</p>
                                            <p class="text-xs mt-1">Si necesitas restablecer tu contraseña nuevamente, solicita un nuevo enlace desde la pantalla de inicio de sesión.</p>
                                        </div>
                                    </div>
                                </div>
                            @enderror

                            <div class="text-center mb-4">
                                <p class="text-soft-gray-600 text-sm">Ingresa tu nueva contraseña y confírmala para completar el restablecimiento.</p>
                            </div>

                            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                                @csrf

                                <!-- Password Reset Token -->
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <!-- Email Address (Hidden) -->
                                <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

                                <!-- Email Display -->
                                <div>
                                    <label class="block text-sm font-medium text-soft-gray-700 mb-2">Correo Electrónico</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-soft-gray-400"></i>
                                        </div>
                                        <input type="email" 
                                               class="auth-input bg-soft-gray-50 cursor-not-allowed"
                                               value="{{ old('email', $request->email) }}" 
                                               disabled>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-soft-gray-700 mb-2">Nueva Contraseña</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-soft-gray-400"></i>
                                        </div>
                                        <input id="password" name="password" type="password" required
                                               class="auth-input pr-12"
                                               placeholder="Ingresa tu nueva contraseña"
                                               oninput="validatePassword()">
                                        <button type="button" 
                                                onclick="togglePassword('password')"
                                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-soft-gray-400 hover:text-soft-gray-600">
                                            <i id="password-icon" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <!-- Password strength indicator -->
                                    <div id="password-strength" class="mt-2 hidden">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div id="strength-bar" class="h-full transition-all duration-300"></div>
                                            </div>
                                            <span id="strength-text" class="text-xs font-medium"></span>
                                        </div>
                                    </div>
                                    <div id="password-error" class="mt-2 text-sm text-red-600 hidden"></div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-soft-gray-700 mb-2">Confirmar Nueva Contraseña</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-soft-gray-400"></i>
                                        </div>
                                        <input id="password_confirmation" name="password_confirmation" type="password" required
                                               class="auth-input pr-12"
                                               placeholder="Confirma tu nueva contraseña"
                                               oninput="validatePasswordMatch()">
                                        <button type="button" 
                                                onclick="togglePassword('password_confirmation')"
                                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-soft-gray-400 hover:text-soft-gray-600">
                                            <i id="password_confirmation-icon" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="password-match-error" class="mt-2 text-sm text-red-600 hidden"></div>
                                    <div id="password-match-success" class="mt-2 text-sm text-green-600 hidden">
                                        <i class="fas fa-check-circle mr-1"></i>Las contraseñas coinciden
                                    </div>
                                    @error('password_confirmation')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div>
                                    <button type="submit" class="auth-button">
                                        <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                            <i class="fas fa-check text-soft-green-300 group-hover:text-soft-green-200"></i>
                                        </span>
                                        Restablecer contraseña
                                    </button>
                                </div>
                            </form>

                            <!-- Back to login -->
                            <div class="auth-divider">
                                <p class="text-sm text-soft-gray-500">
                                    ¿Ya tienes acceso?
                                    <a href="{{ route('login') }}" class="auth-link">Inicia sesión aquí</a>
                                </p>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function validatePassword() {
            const password = document.getElementById('password').value;
            const strengthDiv = document.getElementById('password-strength');
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            const errorDiv = document.getElementById('password-error');

            if (password.length === 0) {
                strengthDiv.classList.add('hidden');
                errorDiv.classList.add('hidden');
                return;
            }

            strengthDiv.classList.remove('hidden');

            let strength = 0;
            let feedback = [];

            // Longitud mínima
            if (password.length >= 8) {
                strength += 1;
            } else {
                feedback.push('Mínimo 8 caracteres');
            }

            // Mayúsculas
            if (/[A-Z]/.test(password)) {
                strength += 1;
            } else {
                feedback.push('Una mayúscula');
            }

            // Minúsculas
            if (/[a-z]/.test(password)) {
                strength += 1;
            } else {
                feedback.push('Una minúscula');
            }

            // Números
            if (/[0-9]/.test(password)) {
                strength += 1;
            } else {
                feedback.push('Un número');
            }

            // Caracteres especiales
            if (/[^A-Za-z0-9]/.test(password)) {
                strength += 1;
            } else {
                feedback.push('Un carácter especial');
            }

            // Actualizar barra y texto
            const percentage = (strength / 5) * 100;
            strengthBar.style.width = percentage + '%';

            if (strength <= 2) {
                strengthBar.className = 'h-full transition-all duration-300 bg-red-500';
                strengthText.textContent = 'Débil';
                strengthText.className = 'text-xs font-medium text-red-600';
            } else if (strength <= 3) {
                strengthBar.className = 'h-full transition-all duration-300 bg-yellow-500';
                strengthText.textContent = 'Media';
                strengthText.className = 'text-xs font-medium text-yellow-600';
            } else if (strength <= 4) {
                strengthBar.className = 'h-full transition-all duration-300 bg-blue-500';
                strengthText.textContent = 'Buena';
                strengthText.className = 'text-xs font-medium text-blue-600';
            } else {
                strengthBar.className = 'h-full transition-all duration-300 bg-green-500';
                strengthText.textContent = 'Fuerte';
                strengthText.className = 'text-xs font-medium text-green-600';
            }

            // Mostrar errores
            if (feedback.length > 0) {
                errorDiv.textContent = 'Falta: ' + feedback.join(', ');
                errorDiv.classList.remove('hidden');
            } else {
                errorDiv.classList.add('hidden');
            }

            // Validar coincidencia si ya hay algo en confirmación
            validatePasswordMatch();
        }

        function validatePasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const errorDiv = document.getElementById('password-match-error');
            const successDiv = document.getElementById('password-match-success');

            if (confirmation.length === 0) {
                errorDiv.classList.add('hidden');
                successDiv.classList.add('hidden');
                return;
            }

            if (password !== confirmation) {
                errorDiv.textContent = 'Las contraseñas no coinciden';
                errorDiv.classList.remove('hidden');
                successDiv.classList.add('hidden');
            } else {
                errorDiv.classList.add('hidden');
                successDiv.classList.remove('hidden');
            }
        }
    </script>

</body>
</html>
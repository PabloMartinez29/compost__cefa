@extends('layouts.master')

@section('content')
@vite(['resources/css/waste.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-user-plus waste-icon"></i>
                    Crear Nuevo Usuario
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Panel de Administración
                </p>
            </div>
            <div class="text-right">
                <a href="{{ route('admin.users.index') }}" class="waste-btn waste-btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver a Usuarios
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="waste-card animate-fade-in-up">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name Field -->
                <div class="waste-form-group">
                    <label for="name" class="waste-form-label">
                        <i class="fas fa-user mr-2"></i>
                        Nombre Completo *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="waste-form-input @error('name') border-red-500 @enderror"
                           placeholder="Ingrese el nombre completo"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="waste-form-group">
                    <label for="email" class="waste-form-label">
                        <i class="fas fa-envelope mr-2"></i>
                        Correo Electrónico *
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="waste-form-input @error('email') border-red-500 @enderror"
                           placeholder="usuario@ejemplo.com"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Identification Field -->
                <div class="waste-form-group">
                    <label for="identification" class="waste-form-label">
                        <i class="fas fa-id-card mr-2"></i>
                        Identificación *
                    </label>
                    <input type="text" 
                           id="identification" 
                           name="identification" 
                           value="{{ old('identification') }}"
                           class="waste-form-input @error('identification') border-red-500 @enderror"
                           placeholder="Número de identificación"
                           required>
                    @error('identification')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Role Field -->
                <div class="waste-form-group">
                    <label for="role" class="waste-form-label">
                        <i class="fas fa-user-tag mr-2"></i>
                        Rol del Usuario *
                    </label>
                    <select id="role" 
                            name="role" 
                            class="waste-form-input @error('role') border-red-500 @enderror"
                            required>
                        <option value="">Seleccione un rol</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                            Administrador
                        </option>
                        <option value="aprendiz" {{ old('role') == 'aprendiz' ? 'selected' : '' }}>
                            Aprendiz
                        </option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="waste-form-group">
                    <label for="password" class="waste-form-label">
                        <i class="fas fa-lock mr-2"></i>
                        Contraseña *
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="waste-form-input @error('password') border-red-500 @enderror pr-10"
                               placeholder="Mínimo 8 caracteres"
                               required>
                        <button type="button" 
                                onclick="togglePassword('password')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-eye" id="password-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="waste-form-group">
                    <label for="password_confirmation" class="waste-form-label">
                        <i class="fas fa-lock mr-2"></i>
                        Confirmar Contraseña *
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="waste-form-input @error('password_confirmation') border-red-500 @enderror pr-10"
                               placeholder="Repita la contraseña"
                               required>
                        <button type="button" 
                                onclick="togglePassword('password_confirmation')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-eye" id="password_confirmation-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Role Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-blue-900 mb-2">Información sobre Roles</h4>
                        <div class="space-y-2 text-sm text-blue-800">
                            <div class="flex items-center">
                                <i class="fas fa-user-shield text-green-600 mr-2"></i>
                                <strong>Administrador:</strong> Acceso completo al sistema, puede gestionar usuarios, ver todos los datos y administrar el sistema.
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user-graduate text-yellow-600 mr-2"></i>
                                <strong>Aprendiz:</strong> Acceso limitado para registrar y gestionar sus propios datos de compostaje.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200 flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg flex items-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}

// Password strength indicator
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    const strength = getPasswordStrength(password);
    
    // You can add visual feedback here if needed
});

function getPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    return strength;
}
</script>

<style>
/* Eliminar efectos 3D y hover del formulario de crear usuario */
.waste-card {
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
    border: 1px solid #e5e7eb !important;
}

.waste-form-group {
    margin-bottom: 1.5rem;
}

.waste-form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.waste-form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.2s ease-in-out;
    background-color: #ffffff;
}

.waste-form-input:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.waste-form-input:hover {
    border-color: #9ca3af;
}

/* Eliminar efectos 3D de botones */
.waste-btn {
    transition: none !important;
    transform: none !important;
    box-shadow: none !important;
}

.waste-btn:hover {
    transform: none !important;
    box-shadow: none !important;
}
</style>

@endsection

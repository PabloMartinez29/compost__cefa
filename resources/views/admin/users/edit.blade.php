@extends('layouts.master')

@section('content')
@vite(['resources/css/waste.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-user-edit waste-icon"></i>
                    Editar Usuario
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
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name Field -->
                <div class="space-y-2">
                    <label for="name" class="waste-label">
                        <i class="fas fa-user mr-2"></i>
                        Nombre Completo *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}"
                           class="waste-input @error('name') border-red-500 @enderror"
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
                <div class="space-y-2">
                    <label for="email" class="waste-label">
                        <i class="fas fa-envelope mr-2"></i>
                        Correo Electrónico *
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}"
                           class="waste-input @error('email') border-red-500 @enderror"
                           placeholder="usuario@ejemplo.com"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Role Field -->
                <div class="space-y-2">
                    <label for="role" class="waste-label">
                        <i class="fas fa-user-tag mr-2"></i>
                        Rol del Usuario *
                    </label>
                    <select id="role" 
                            name="role" 
                            class="waste-input @error('role') border-red-500 @enderror"
                            required>
                        <option value="">Seleccione un rol</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                            <i class="fas fa-user-shield mr-2"></i>
                            Administrador
                        </option>
                        <option value="aprendiz" {{ old('role', $user->role) == 'aprendiz' ? 'selected' : '' }}>
                            <i class="fas fa-user-graduate mr-2"></i>
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
                <div class="space-y-2">
                    <label for="password" class="waste-label">
                        <i class="fas fa-lock mr-2"></i>
                        Nueva Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="waste-input @error('password') border-red-500 @enderror pr-10"
                               placeholder="Dejar vacío para mantener la actual">
                        <button type="button" 
                                onclick="togglePassword('password')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password-eye"></i>
                        </button>
                    </div>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Deje vacío si no desea cambiar la contraseña
                    </p>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="waste-label">
                        <i class="fas fa-lock mr-2"></i>
                        Confirmar Nueva Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="waste-input @error('password_confirmation') border-red-500 @enderror pr-10"
                               placeholder="Repita la nueva contraseña">
                        <button type="button" 
                                onclick="togglePassword('password_confirmation')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
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

            <!-- User Information -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-user-circle text-gray-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Información del Usuario</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                            <div>
                                <strong>ID:</strong> {{ $user->id }}
                            </div>
                            <div>
                                <strong>Registrado:</strong> {{ $user->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div>
                                <strong>Última actualización:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}
                            </div>
                            <div>
                                <strong>Estado:</strong> 
                                @if($user->email_verified_at)
                                    <span class="text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Verificado
                                    </span>
                                @else
                                    <span class="text-yellow-600">
                                        <i class="fas fa-clock mr-1"></i>
                                        Sin verificar
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
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
                   class="waste-btn waste-btn-secondary">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" class="waste-btn waste-btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Actualizar Usuario
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

// Password validation
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    const confirmPassword = document.getElementById('password_confirmation');
    
    if (password && confirmPassword.value) {
        if (password !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Las contraseñas no coinciden');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }
});

document.getElementById('password_confirmation').addEventListener('input', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = e.target.value;
    
    if (password && confirmPassword) {
        if (password !== confirmPassword) {
            e.target.setCustomValidity('Las contraseñas no coinciden');
        } else {
            e.target.setCustomValidity('');
        }
    }
});
</script>

@endsection

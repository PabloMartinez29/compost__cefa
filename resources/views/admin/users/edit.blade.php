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
                    {{ Auth::user()->name }} - Usuario #{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-2xl mx-auto">
        <div class="waste-form animate-fade-in-up animate-delay-1">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name Field -->
                <div class="waste-form-group">
                    <label for="name" class="waste-form-label">Nombre Completo *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}"
                           class="waste-form-input @error('name') border-red-500 @enderror"
                           placeholder="Ingrese el nombre completo"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="waste-form-group">
                    <label for="email" class="waste-form-label">Correo Electrónico *</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}"
                           class="waste-form-input @error('email') border-red-500 @enderror"
                           placeholder="usuario@ejemplo.com"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Field -->
                <div class="waste-form-group">
                    <label for="role" class="waste-form-label">Rol del Usuario *</label>
                    <div>
                        <select id="role" 
                                name="role" 
                                class="waste-form-select @error('role') border-red-500 @enderror"
                                required>
                            <option value="">Seleccione un rol</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="aprendiz" {{ old('role', $user->role) == 'aprendiz' ? 'selected' : '' }}>Aprendiz</option>
                        </select>
                    </div>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="waste-form-group">
                    <label for="password" class="waste-form-label">Nueva Contraseña</label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="waste-form-input @error('password') border-red-500 @enderror pr-10"
                               placeholder="Dejar vacío para mantener la actual">
                        <button type="button" 
                                onclick="togglePassword('password')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password-eye"></i>
                        </button>
                    </div>
                    <p class="text-gray-500 text-sm mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Deje vacío si no desea cambiar la contraseña
                    </p>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="waste-form-group">
                    <label for="password_confirmation" class="waste-form-label">Confirmar Nueva Contraseña</label>
                    <div class="relative">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="waste-form-input @error('password_confirmation') border-red-500 @enderror pr-10"
                               placeholder="Repita la nueva contraseña">
                        <button type="button" 
                                onclick="togglePassword('password_confirmation')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password_confirmation-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
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
                                @if($user->is_active)
                                    <span class="text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Estado Activado
                                    </span>
                                @else
                                    <span class="text-red-600">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Desactivado
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

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" 
                   class="waste-btn-secondary">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" class="waste-btn">
                    <i class="fas fa-save mr-2"></i>
                    Guardar
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

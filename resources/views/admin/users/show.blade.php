@extends('layouts.master')

@section('content')
@vite(['resources/css/waste.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-user waste-icon"></i>
                    Detalles del Usuario
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information Card -->
        <div class="lg:col-span-2">
            <div class="waste-card animate-fade-in-up">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">
                        <i class="fas fa-user-circle mr-2"></i>
                        Información Personal
                    </h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="waste-btn waste-btn-warning waste-btn-sm">
                            <i class="fas fa-edit mr-1"></i>
                            Editar
                        </a>
                        @if($user->id !== auth()->id())
                        <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                                class="waste-btn waste-btn-danger waste-btn-sm">
                            <i class="fas fa-trash mr-1"></i>
                            Eliminar
                        </button>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- User Avatar and Basic Info -->
                    <div class="flex items-center space-x-6">
                        <div class="w-20 h-20 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            <div class="mt-2">
                                @if($user->role === 'admin')
                                    <span class="waste-badge waste-badge-success">
                                        <i class="fas fa-user-shield mr-1"></i>
                                        Administrador
                                    </span>
                                @else
                                    <span class="waste-badge waste-badge-warning">
                                        <i class="fas fa-user-graduate mr-1"></i>
                                        Aprendiz
                                    </span>
                                @endif
                                @if($user->id === auth()->id())
                                    <span class="waste-badge waste-badge-info ml-2">
                                        <i class="fas fa-user mr-1"></i>
                                        Tu cuenta
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- User Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">ID de Usuario</label>
                                <p class="text-lg font-semibold text-gray-900">#{{ $user->id }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Estado de Verificación</label>
                                <div class="flex items-center">
                                    @if($user->email_verified_at)
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <span class="text-green-600 font-medium">Email Verificado</span>
                                    @else
                                        <i class="fas fa-clock text-yellow-500 mr-2"></i>
                                        <span class="text-yellow-600 font-medium">Email Sin Verificar</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Fecha de Registro</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-500">{{ $user->created_at->format('H:i:s') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Última Actualización</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $user->updated_at->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-500">{{ $user->updated_at->format('H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="space-y-6">
            <!-- Role Information -->
            <div class="waste-card animate-fade-in-up">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-info-circle mr-2"></i>
                    Información del Rol
                </h3>
                <div class="space-y-3">
                    @if($user->role === 'admin')
                        <div class="flex items-center p-3 bg-green-50 rounded-lg">
                            <i class="fas fa-user-shield text-green-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-green-900">Administrador</p>
                                <p class="text-sm text-green-700">Acceso completo al sistema</p>
                            </div>
                        </div>
                        <ul class="text-sm text-gray-600 space-y-1 ml-6">
                            <li>• Gestionar usuarios</li>
                            <li>• Ver todos los datos</li>
                            <li>• Administrar el sistema</li>
                            <li>• Acceso a reportes</li>
                        </ul>
                    @else
                        <div class="flex items-center p-3 bg-yellow-50 rounded-lg">
                            <i class="fas fa-user-graduate text-yellow-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-yellow-900">Aprendiz</p>
                                <p class="text-sm text-yellow-700">Acceso limitado</p>
                            </div>
                        </div>
                        <ul class="text-sm text-gray-600 space-y-1 ml-6">
                            <li>• Registrar residuos orgánicos</li>
                            <li>• Gestionar compostaje</li>
                            <li>• Ver sus propios datos</li>
                            <li>• Solicitar permisos</li>
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="waste-card animate-fade-in-up">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-bolt mr-2"></i>
                    Acciones Rápidas
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="waste-btn waste-btn-warning w-full justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Usuario
                    </a>
                    @if($user->id !== auth()->id())
                    <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                            class="waste-btn waste-btn-danger w-full justify-center">
                        <i class="fas fa-trash mr-2"></i>
                        Eliminar Usuario
                    </button>
                    @endif
                    <a href="{{ route('admin.users.index') }}" 
                       class="waste-btn waste-btn-secondary w-full justify-center">
                        <i class="fas fa-list mr-2"></i>
                        Ver Todos los Usuarios
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function deleteUser(userId, userName) {
    Swal.fire({
        title: '¿Eliminar Usuario?',
        html: `¿Estás seguro de que deseas eliminar al usuario <strong>${userName}</strong>?<br><br>Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}`;
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            const tokenField = document.createElement('input');
            tokenField.type = 'hidden';
            tokenField.name = '_token';
            tokenField.value = '{{ csrf_token() }}';
            
            form.appendChild(methodField);
            form.appendChild(tokenField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

@endsection

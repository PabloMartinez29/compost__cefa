@extends('layouts.master')

@php
use App\Models\User;
@endphp

@section('content')
@vite(['resources/css/waste.css'])

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Alertas de éxito/error -->
@if(session('success'))
<script>
Swal.fire({
    title: '¡Éxito!',
    text: '{{ session('success') }}',
    icon: 'success',
    confirmButtonText: 'Entendido',
    customClass: {
        popup: 'swal2-popup-custom',
        title: 'swal2-title-custom',
        htmlContainer: 'swal2-html-custom'
    }
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    title: 'Error',
    text: '{{ session('error') }}',
    icon: 'error',
    confirmButtonText: 'Entendido',
    customClass: {
        popup: 'swal2-popup-custom',
        title: 'swal2-title-custom',
        htmlContainer: 'swal2-html-custom'
    }
});
</script>
@endif

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-users waste-icon"></i>
                    Gestión de Usuarios
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Panel de Administración
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Users -->
        <div class="waste-card waste-card-primary animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Usuarios</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $users->total() }}</div>
                </div>
                <div class="waste-card-icon text-blue-600">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Admin Users -->
        <div class="waste-card waste-card-success animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Administradores</div>
                    <div class="text-3xl font-bold text-gray-800">{{ User::where('role', 'admin')->count() }}</div>
                </div>
                <div class="waste-card-icon text-green-600">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>

        <!-- Apprentice Users -->
        <div class="waste-card waste-card-warning animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Aprendices</div>
                    <div class="text-3xl font-bold text-gray-800">{{ User::where('role', 'aprendiz')->count() }}</div>
                </div>
                <div class="waste-card-icon text-yellow-600">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
    </div>


    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Table Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-users text-green-600 mr-2"></i>
                Lista de Usuarios
            </h2>
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <form method="GET" action="{{ route('admin.users.index') }}" class="relative">
                    <input type="text" name="search" id="searchInput" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por nombre, identificación o email..." 
                           class="w-full px-6 py-2 pl-40 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 w-80">
                    <div class="absolute left-2 top-1/2 transform -translate-y-1/2">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                </form>
                <a href="{{ route('admin.users.download.all-pdf') }}" class="bg-red-500 text-white border border-red-600 hover:bg-red-600 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-file-pdf"></i>
                </a>
                <a href="{{ route('admin.users.create') }}" class="bg-green-400 text-green-800 border border-green-500 hover:bg-green-500 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Usuario
                </a>
            </div>
        </div>
        

        <div class="overflow-x-auto">
            <table class="waste-table">
                <thead>
                    <tr>
                        <th>Identificación</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="font-mono">{{ $user->identification ?? 'ID' . str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    @if($user->id === auth()->id())
                                        <span class="text-xs text-green-600 font-medium">(Tú)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-user-shield mr-1"></i>
                                    Administrador
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-user-graduate mr-1"></i>
                                    Aprendiz
                                </span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="flex space-x-2 items-center">
                                    <button onclick="openViewModal({{ $user->id }})" 
                                            class="inline-flex items-center text-blue-500 hover:text-blue-700" 
                                            title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="openEditModal({{ $user->id }})" 
                                            class="inline-flex items-center text-green-500 hover:text-green-700" 
                                            title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                                            class="inline-flex items-center text-red-500 hover:text-red-700" 
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                    <a href="{{ route('admin.users.download.pdf', $user) }}" 
                                       class="inline-flex items-center text-red-800 hover:text-red-900" 
                                       title="Descargar PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i class="fas fa-users text-4xl mb-4 block"></i>
                            No se encontraron usuarios registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="mt-10 pt-8 border-t border-gray-200 px-6 pb-6">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal para ver detalles del usuario -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-eye waste-icon"></i>
                    Detalles del Usuario
                </h3>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    <span id="viewUserInfo">{{ Auth::user()->name }} - Usuario #<span id="viewUserId"></span></span>
                </p>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="space-y-6">
                <!-- Avatar del usuario -->
                <div class="text-center">
                    <div id="viewUserAvatar" class="w-20 h-20 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                    </div>
                    <h4 id="viewUserName" class="text-xl font-bold text-gray-900"></h4>
                    <p id="viewUserEmail" class="text-gray-600"></p>
                </div>

                <!-- Información del usuario -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="waste-form-group">
                        <label class="waste-form-label">Identificación</label>
                        <div class="waste-form-input bg-gray-50" id="viewUserIdentification"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Rol</label>
                        <div class="waste-form-input bg-gray-50" id="viewUserRole"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Estado de Verificación</label>
                        <div class="waste-form-input bg-gray-50" id="viewUserVerified"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha de Registro</label>
                        <div class="waste-form-input bg-gray-50" id="viewUserCreatedAt"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Última Actualización</label>
                        <div class="waste-form-input bg-gray-50" id="viewUserUpdatedAt"></div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button onclick="closeViewModal()" 
                            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar usuario -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-edit waste-icon"></i>
                    Editar Usuario
                </h3>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    <span id="editUserInfo">{{ Auth::user()->name }} - Editar Usuario #<span id="editUserId"></span></span>
                </p>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name Field -->
                        <div class="waste-form-group">
                            <label for="editName" class="waste-form-label">
                                <i class="fas fa-user mr-2"></i>
                                Nombre Completo *
                            </label>
                            <input type="text" 
                                   id="editName" 
                                   name="name" 
                                   class="waste-form-input"
                                   placeholder="Ingrese el nombre completo"
                                   required>
                        </div>

                        <!-- Email Field -->
                        <div class="waste-form-group">
                            <label for="editEmail" class="waste-form-label">
                                <i class="fas fa-envelope mr-2"></i>
                                Correo Electrónico *
                            </label>
                            <input type="email" 
                                   id="editEmail" 
                                   name="email" 
                                   class="waste-form-input"
                                   placeholder="usuario@ejemplo.com"
                                   required>
                        </div>

                        <!-- Identification Field -->
                        <div class="waste-form-group">
                            <label for="editIdentification" class="waste-form-label">
                                <i class="fas fa-id-card mr-2"></i>
                                Identificación *
                            </label>
                            <input type="text" 
                                   id="editIdentification" 
                                   name="identification" 
                                   class="waste-form-input"
                                   placeholder="Número de identificación"
                                   required>
                        </div>

                        <!-- Role Field -->
                        <div class="waste-form-group">
                            <label for="editRole" class="waste-form-label">
                                <i class="fas fa-user-tag mr-2"></i>
                                Rol del Usuario *
                            </label>
                            <select id="editRole" 
                                    name="role" 
                                    class="waste-form-input"
                                    required>
                                <option value="">Seleccione un rol</option>
                                <option value="admin">Administrador</option>
                                <option value="aprendiz">Aprendiz</option>
                            </select>
                        </div>

                        <!-- Password Field -->
                        <div class="waste-form-group">
                            <label for="editPassword" class="waste-form-label">
                                <i class="fas fa-lock mr-2"></i>
                                Nueva Contraseña
                            </label>
                            <input type="password" 
                                   id="editPassword" 
                                   name="password" 
                                   class="waste-form-input"
                                   placeholder="Dejar vacío para mantener la actual">
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Deje vacío si no desea cambiar la contraseña
                            </p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeEditModal()" 
                                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200 flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Actualizar Usuario
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<script>
// Función para abrir modal de ver detalles
function openViewModal(userId) {
    // Mostrar loading
    Swal.fire({
        title: 'Cargando...',
        text: 'Obteniendo información del usuario',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Fetch user data
    fetch(`/admin/users/${userId}/data`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        Swal.close();
        
            // Llenar datos del modal
            document.getElementById('viewUserId').textContent = data.id;
            document.getElementById('viewUserIdentification').textContent = data.identification || 'No asignada';
            document.getElementById('viewUserName').textContent = data.name;
            document.getElementById('viewUserEmail').textContent = data.email;
            document.getElementById('viewUserAvatar').textContent = data.name.substring(0, 2).toUpperCase();
            
            // Rol
            const roleElement = document.getElementById('viewUserRole');
            if (data.role === 'admin') {
                roleElement.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-user-shield mr-1"></i>Administrador</span>';
            } else {
                roleElement.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><i class="fas fa-user-graduate mr-1"></i>Aprendiz</span>';
            }
            
            // Estado de verificación
            const verifiedElement = document.getElementById('viewUserVerified');
            if (data.email_verified_at) {
                verifiedElement.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Email Verificado</span>';
            } else {
                verifiedElement.innerHTML = '<span class="text-yellow-600"><i class="fas fa-clock mr-1"></i>Email Sin Verificar</span>';
            }
            
            // Fechas
            document.getElementById('viewUserCreatedAt').textContent = new Date(data.created_at).toLocaleDateString('es-ES');
            document.getElementById('viewUserUpdatedAt').textContent = new Date(data.updated_at).toLocaleDateString('es-ES');
            
            // Mostrar modal
            document.getElementById('viewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            Swal.close();
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar la información del usuario. Verifique su conexión e intente nuevamente.',
                icon: 'error',
                confirmButtonText: 'Entendido',
                customClass: {
                    popup: 'swal2-popup-custom',
                    title: 'swal2-title-custom',
                    htmlContainer: 'swal2-html-custom'
                }
            });
        });
}

// Función para cerrar modal de ver detalles
function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Función para abrir modal de editar
function openEditModal(userId) {
    // Mostrar loading
    Swal.fire({
        title: 'Cargando...',
        text: 'Obteniendo información del usuario',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Fetch user data
    fetch(`/admin/users/${userId}/data`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        Swal.close();
        
            // Llenar formulario
            document.getElementById('editUserId').textContent = data.id;
            document.getElementById('editName').value = data.name;
            document.getElementById('editEmail').value = data.email;
            document.getElementById('editIdentification').value = data.identification || '';
            document.getElementById('editRole').value = data.role;
            document.getElementById('editPassword').value = '';
            
            // Configurar acción del formulario
            document.getElementById('editForm').action = `/admin/users/${userId}`;
            
            // Mostrar modal
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            Swal.close();
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar la información del usuario. Verifique su conexión e intente nuevamente.',
                icon: 'error',
                confirmButtonText: 'Entendido',
                customClass: {
                    popup: 'swal2-popup-custom',
                    title: 'swal2-title-custom',
                    htmlContainer: 'swal2-html-custom'
                }
            });
        });
}

// Función para cerrar modal de editar
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Función para eliminar usuario con alerta bonita
function deleteUser(userId, userName) {
    Swal.fire({
        title: '¿Eliminar Usuario?',
        html: `
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-times text-red-500 text-2xl"></i>
                </div>
                <p class="text-gray-700 mb-2">¿Estás seguro de que deseas eliminar al usuario?</p>
                <p class="font-semibold text-gray-900">${userName}</p>
                <p class="text-sm text-red-600 mt-2">Esta acción no se puede deshacer</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Sí, eliminar',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
        reverseButtons: true,
        customClass: {
            popup: 'swal2-popup-custom',
            title: 'swal2-title-custom',
            htmlContainer: 'swal2-html-custom'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Eliminando...',
                text: 'Eliminando usuario del sistema',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

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

// Cerrar modales al hacer clic fuera
document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeViewModal();
    }
});

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const identification = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        
        if (identification.includes(searchTerm) || name.includes(searchTerm) || email.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

</script>

<style>
/* Estilos para alertas bonitas */
.swal2-popup-custom {
    border-radius: 16px !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
}

.swal2-title-custom {
    font-size: 1.5rem !important;
    font-weight: 700 !important;
    color: #1f2937 !important;
}

.swal2-html-custom {
    font-size: 1rem !important;
    color: #374151 !important;
}

/* Animaciones para modales */
.modal-backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Transiciones suaves para modales */
#viewModal, #editModal {
    transition: all 0.3s ease-in-out;
}

#viewModal.hidden, #editModal.hidden {
    opacity: 0;
    transform: scale(0.9);
}

#viewModal:not(.hidden), #editModal:not(.hidden) {
    opacity: 1;
    transform: scale(1);
}

/* Estilos mejorados para formularios en modales */
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

/* Mejoras para el modal de editar */
#editModal .waste-form-group {
    margin-bottom: 1.25rem;
}

#editModal .waste-form-label {
    color: #1f2937;
    font-weight: 500;
}

#editModal .waste-form-input {
    background-color: #f9fafb;
    border-color: #e5e7eb;
}

#editModal .waste-form-input:focus {
    background-color: #ffffff;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Estilos mejorados para la tabla de usuarios */
.waste-table {
    border: 1px solid #e5e7eb !important;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
}

.waste-table tr {
    transition: none !important;
}

.waste-table tr:hover {
    background-color: transparent !important;
    transform: none !important;
    box-shadow: none !important;
}

.waste-table td {
    transition: none !important;
}

.waste-table td:hover {
    background-color: transparent !important;
    transform: none !important;
}

/* Mejorar el contenedor de la tabla */
.bg-white.rounded-lg.shadow-sm {
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
    border: 1px solid #e5e7eb !important;
}

/* Estilo para el header de la tabla */
.table-header {
    background-color: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    padding: 1rem 1.5rem;
}

/* Mejorar las cards de estadísticas */
.waste-card {
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
    border: 1px solid #e5e7eb !important;
}
</style>

<script>
// Búsqueda con paginación del servidor
let searchTimeout;

document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const searchTerm = this.value.trim();
    
    if (searchTerm.length >= 1) {
        searchTimeout = setTimeout(() => {
            // Enviar formulario de búsqueda
            this.form.submit();
        }, 500); // Esperar 500ms después de que el usuario deje de escribir
    } else if (searchTerm.length === 0) {
        // Si el campo está vacío, limpiar búsqueda
        window.location.href = '{{ route("admin.users.index") }}';
    }
});

// Búsqueda al presionar Enter
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        this.form.submit();
    }
});
</script>

@endsection

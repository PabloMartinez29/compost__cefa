@extends('layouts.master')

@section('content')
@vite(['resources/css/waste.css'])

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-3 sm:px-4 md:px-6 py-4 sm:py-6 md:py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
            <div class="flex-1 min-w-0">
                <h1 class="waste-title text-xl sm:text-2xl">
                    <i class="fas fa-wrench waste-icon"></i>
                    Control de Actividades
                </h1>
                <p class="waste-subtitle text-sm sm:text-base">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    <span class="break-words">{{ Auth::user()->name }} - Admin Panel</span>
                </p>
            </div>
            <div class="text-left sm:text-right flex-shrink-0">
                <div class="text-green-400 font-bold text-base sm:text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Total Registros -->
        <div class="waste-card waste-card-primary animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Registros</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalMaintenances }}</div>
                </div>
                <div class="waste-card-icon text-blue-600">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
        
        <!-- Mantenimientos -->
        <div class="waste-card waste-card-success animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Mantenimientos</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $maintenanceCount }}</div>
                </div>
                <div class="waste-card-icon text-green-600">
                    <i class="fas fa-wrench"></i>
                </div>
            </div>
        </div>
        
        <!-- Operaciones -->
        <div class="waste-card waste-card-warning animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Operaciones</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $operationsCount }}</div>
                </div>
                <div class="waste-card-icon text-yellow-600">
                    <i class="fas fa-play"></i>
                </div>
            </div>
        </div>

        <!-- Este Mes -->
        <div class="waste-card waste-card-info animate-fade-in-up animate-delay-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Este Mes</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $thisMonthMaintenances }}</div>
                </div>
                <div class="waste-card-icon text-cyan-600">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Table Header -->
        <div class="p-3 sm:p-4 md:p-6 border-b border-gray-200 bg-gray-50">
            <!-- Primera fila: Título y botones -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4 mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-wrench text-green-600 mr-2"></i>
                    Registros de Actividades
                </h2>
                <div class="flex items-center space-x-2 sm:space-x-4 w-full sm:w-auto">
                    @if($maintenances->count() > 0)
                        <button type="button" id="btn-download-all-pdf" class="bg-red-500 text-white border border-red-600 hover:bg-red-600 px-3 sm:px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm text-sm sm:text-base" title="Descargar PDF de los registros visibles (filtrados)">
                            <i class="fas fa-file-pdf"></i>
                            <span class="hidden sm:inline ml-2">PDF</span>
                        </button>
                    @endif
                    <a href="{{ route('admin.machinery.maintenance.create') }}" class="bg-green-400 text-green-800 border border-green-500 hover:bg-green-500 px-3 sm:px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                        <i class="fas fa-plus mr-2"></i>
                        <span class="hidden sm:inline">Nuevo Registro</span>
                        <span class="sm:hidden">Nuevo</span>
                    </a>
                </div>
            </div>
        </div>

        @if($maintenances->count() > 0)
            <!-- Vista móvil: tarjetas -->
            <div class="block md:hidden p-3 sm:p-4 space-y-4">
                @foreach($maintenances as $maintenance)
                    <div class="waste-mobile-card bg-gray-50 border border-gray-200 rounded-xl p-4 shadow-sm" data-id="{{ $maintenance->id }}">
                        <div class="flex gap-3">
                            @if($maintenance->machinery && $maintenance->machinery->image)
                                <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 cursor-pointer" onclick="openImageModal('{{ asset('storage/'.$maintenance->machinery->image) }}')">
                                    <img src="{{ asset('storage/'.$maintenance->machinery->image) }}" alt="" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center" style="display: none;"><i class="fas fa-image text-gray-400"></i></div>
                                </div>
                            @else
                                <div class="w-14 h-14 rounded-xl bg-gray-200 flex items-center justify-center flex-shrink-0"><i class="fas fa-cogs text-gray-400 text-xl"></i></div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">{{ $maintenance->machinery->name ?? 'N/A' }}</h3>
                                <p class="text-sm text-gray-600">{{ $maintenance->date->format('d/m/Y') }}</p>
                                <span class="waste-badge {{ $maintenance->type == 'M' ? 'waste-badge-danger' : 'waste-badge-success' }}">{{ $maintenance->type_name }}</span>
                            </div>
                        </div>
                        <div class="waste-mobile-card-actions mt-4 pt-3 border-t border-gray-200">
                            <button type="button" onclick="openViewModal({{ $maintenance->id }})" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg flex-shrink-0" title="Ver"><i class="fas fa-eye"></i></button>
                            <button type="button" onclick="confirmEdit(event, {{ $maintenance->id }})" class="p-2 text-green-600 hover:bg-green-50 rounded-lg flex-shrink-0" title="Editar"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.machinery.maintenance.destroy', $maintenance) }}" method="POST" class="inline flex-shrink-0" onsubmit="return confirmDelete(event, this)">@csrf @method('DELETE')<button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg" title="Eliminar"><i class="fas fa-trash"></i></button></form>
                            <a href="{{ route('admin.machinery.maintenance.download.pdf', $maintenance) }}" class="p-2 text-red-700 hover:bg-red-50 rounded-lg flex-shrink-0" title="PDF"><i class="fas fa-file-pdf"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Tabla (escritorio) -->
            <div class="hidden md:block overflow-x-auto -mx-3 sm:mx-0">
                <div id="maintenancesTable_wrapper" class="p-3 sm:p-4 md:p-6">
                    <div style="width: 100%; overflow: hidden; margin-bottom: 1rem;">
                        <div id="dt-length-container" style="float: left;"></div>
                        <div id="dt-filter-container" style="float: right;"></div>
                    </div>
                    <table id="maintenancesTable" class="waste-table min-w-[900px]">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Fecha</th>
                                <th>Maquinaria</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Responsable</th>
                                <th>Próx. mantenimiento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($maintenances as $maintenance)
                                <tr data-id="{{ $maintenance->id }}">
                                    <td class="font-mono">#{{ str_pad($maintenance->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        @if($maintenance->machinery && $maintenance->machinery->image)
                                            <img src="{{ asset('storage/'.$maintenance->machinery->image) }}?v={{ $maintenance->machinery->updated_at->timestamp }}" 
                                                 alt="Imagen de maquinaria" 
                                                 class="w-12 h-12 object-cover rounded-full cursor-pointer hover:opacity-80 transition-opacity"
                                                 onclick="openImageModal('{{ asset('storage/'.$maintenance->machinery->image) }}?v={{ $maintenance->machinery->updated_at->timestamp }}')"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center" style="display: none;">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                                <i class="fas fa-cogs text-gray-400"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $maintenance->date->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="font-semibold">{{ $maintenance->machinery->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $maintenance->machinery->brand ?? '' }} {{ $maintenance->machinery->model ?? '' }}</div>
                                    </td>
                                    <td>
                                        <span class="waste-badge 
                                            @if($maintenance->type == 'M') waste-badge-danger
                                            @else waste-badge-success
                                            @endif">
                                            {{ $maintenance->type_name }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="max-w-xs truncate" title="{{ $maintenance->description }}">
                                            {{ Str::limit($maintenance->description, 50) }}
                                    </div>
                                </td>
                                    <td>{{ $maintenance->responsible }}</td>
                                <td class="text-center">
                                    @if($maintenance->machinery)
                                        @if($maintenance->machinery->status === 'En mantenimiento')
                                            <span class="text-sm font-semibold text-amber-600">Pausado</span>
                                        @else
                                            @php $machNextDue = $maintenance->machinery->getNextMaintenanceDueDateTime(); @endphp
                                            <span class="maintenance-row-countdown text-sm font-mono font-semibold text-gray-800" data-next-due="{{ $machNextDue?->toIso8601String() ?? '' }}">--</span>
                                        @endif
                                    @else
                                        --
                                    @endif
                                </td>
                                <td>
                                    <div class="flex space-x-2 items-center">
                                        <button onclick="openViewModal({{ $maintenance->id }})" 
                                           class="inline-flex items-center text-blue-400 hover:text-blue-500" title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" onclick="confirmEdit(event, {{ $maintenance->id }})" 
                                           class="inline-flex items-center text-green-500 hover:text-green-700" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.machinery.maintenance.destroy', $maintenance) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirmDelete(event, this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center text-red-500 hover:text-red-700" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.machinery.maintenance.download.pdf', $maintenance) }}" 
                                           class="inline-flex items-center text-red-800 hover:text-red-900" 
                                           title="Descargar PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Estado vacío -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-wrench text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay registros de actividades</h3>
                <p class="text-gray-600">Comienza registrando tu primera actividad en el sistema.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal para ver detalles del mantenimiento -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header relative">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-eye waste-icon"></i>
                    Detalles del Registro
                </h3>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    <span id="viewUserInfo">{{ Auth::user()->name }} - Registro #<span id="viewRecordId"></span></span>
                </p>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="space-y-6">
                <!-- Información del registro -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="waste-form-group">
                        <label class="waste-form-label">Maquinaria</label>
                        <div class="waste-form-input bg-gray-50" id="viewMachinery"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha</label>
                        <div class="waste-form-input bg-gray-50" id="viewDate"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Tipo</label>
                        <div class="waste-form-input bg-gray-50" id="viewType"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Responsable</label>
                        <div class="waste-form-input bg-gray-50" id="viewResponsible"></div>
                    </div>

                    <div class="waste-form-group md:col-span-2">
                        <label class="waste-form-label">Descripción</label>
                        <div class="waste-form-textarea bg-gray-50" id="viewDescription" style="min-height: 100px;"></div>
            </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha de Creación</label>
                        <div class="waste-form-input bg-gray-50" id="viewCreatedAt"></div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button onclick="closeViewModal()" class="waste-btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para visualizar imagen -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-6xl max-h-[90vh] w-full flex items-center justify-center">
        <!-- Botón de cerrar -->
        <button onclick="closeImageModal()" class="absolute top-4 right-4 z-10 bg-black bg-opacity-50 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75 transition-all">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <!-- Imagen -->
        <img id="modalImage" src="" alt="Imagen de maquinaria" 
             class="max-w-4xl max-h-[80vh] w-auto h-auto object-contain rounded-lg shadow-2xl mx-auto">
    </div>
</div>

<!-- Modal de edición -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-edit waste-icon"></i>
                    Editar Actividad
                </h3>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    <span id="editUserInfo">{{ Auth::user()->name }} - Registro #<span id="editMaintenanceId"></span></span>
                </p>
            </div>
            <button id="closeEditModal" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" id="edit_type_hidden" value="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Fecha -->
                    <div class="waste-form-group">
                        <label for="edit_date" class="waste-form-label">Fecha *</label>
                        <input type="date" id="edit_date" name="date" required
                               max="{{ date('Y-m-d') }}"
                               class="waste-form-input" />
                    </div>

                    <!-- Maquinaria -->
                    <div class="waste-form-group">
                        <label for="edit_machinery_id" class="waste-form-label">Maquinaria *</label>
                        <div>
                            <select id="edit_machinery_id" name="machinery_id" required class="waste-form-select">
                                <option value="">Seleccionar maquinaria</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tipo de Registro (valor real en edit_type_hidden para que siempre se envíe al guardar) -->
                    <div class="waste-form-group md:col-span-2">
                        <label class="waste-form-label">Tipo de Registro *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-green-50 transition-all duration-200">
                                <input type="radio" name="type_radio" value="M" id="edit_type_maintenance"
                                       class="sr-only peer">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-green-700">M: Mantenimiento</span>
                            </label>
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-green-50 transition-all duration-200">
                                <input type="radio" name="type_radio" value="O" id="edit_type_operation"
                                       class="sr-only peer">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-green-700">O: Operación</span>
                            </label>
                        </div>
                    </div>

                    <!-- Responsable -->
                    <div class="waste-form-group">
                        <label for="edit_responsible" class="waste-form-label">Responsable *</label>
                        <input type="text" id="edit_responsible" name="responsible" maxlength="150" required
                               placeholder="Nombre del responsable"
                               class="waste-form-input" />
                    </div>

                    <!-- Fecha de Fin (solo para mantenimiento) -->
                    <div class="waste-form-group" id="edit_end_date_container" style="display: none;">
                        <label for="edit_end_date" class="waste-form-label">Fecha de Fin de Mantenimiento</label>
                        <input type="date" id="edit_end_date" name="end_date"
                               class="waste-form-input" />
                        <p class="text-gray-500 text-xs mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Campo opcional
                        </p>
                    </div>

                    <!-- Descripción -->
                    <div class="waste-form-group md:col-span-2">
                        <label for="edit_description" class="waste-form-label">Descripción del Trabajo Realizado *</label>
                        <textarea id="edit_description" name="description" rows="4" maxlength="1000" required
                                  placeholder="Describe detalladamente el mantenimiento u operación realizada..."
                                  class="waste-form-textarea"></textarea>
                        <div class="flex justify-between mt-1">
                            <p class="text-gray-500 text-xs">
                                <i class="fas fa-info-circle mr-1"></i>
                                Máximo 1000 caracteres
                            </p>
                            <p class="text-gray-500 text-xs" id="edit_char-count">0/1000</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <button type="button" id="cancelEditModal" class="waste-btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="waste-btn">
                        <i class="fas fa-save mr-2"></i>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Funciones para el modal de imagen
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Cerrar modal de imagen al hacer clic fuera
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Cerrar modal de imagen con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Funciones para el modal de vista
function openViewModal(maintenanceId) {
    fetch(`/admin/machinery/maintenance/${maintenanceId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('viewRecordId').textContent = data.id.toString().padStart(3, '0');
            document.getElementById('viewMachinery').textContent = `${data.machinery_name} - ${data.machinery_brand} ${data.machinery_model}`;
            document.getElementById('viewDate').textContent = data.date_formatted || data.date;
            document.getElementById('viewType').innerHTML = `<span class="waste-badge ${data.type === 'M' ? 'waste-badge-danger' : 'waste-badge-success'}">${data.type_name}</span>`;
            document.getElementById('viewResponsible').textContent = data.responsible || 'N/A';
            document.getElementById('viewDescription').textContent = data.description || 'N/A';
            document.getElementById('viewCreatedAt').textContent = data.created_at_formatted || data.created_at;
            
            // Mostrar modal
            document.getElementById('viewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del registro');
        });
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Cerrar modal de vista al hacer clic fuera
document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeViewModal();
    }
});

// Cerrar modal de vista con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewModal();
    }
});

// Funciones para el modal de edición
let editModal, closeEditBtn, cancelEditBtn, editForm;

// Inicializar elementos cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    editModal = document.getElementById('editModal');
    closeEditBtn = document.getElementById('closeEditModal');
    cancelEditBtn = document.getElementById('cancelEditModal');
    editForm = document.getElementById('editForm');
    
    if (closeEditBtn) {
        closeEditBtn.addEventListener('click', closeEditModal);
    }
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', closeEditModal);
    }
    if (editModal) {
        // Cerrar solo cuando se hace clic en el fondo oscuro, no dentro del contenido
        editModal.addEventListener('click', (e) => {
            if (e.target === editModal) {
                closeEditModal();
            }
        });
    }
    // Asegurar que el tipo se envíe al guardar: sincronizar hidden con el radio seleccionado en cada submit
    if (editForm) {
        editForm.addEventListener('submit', function() {
            const checked = document.querySelector('input[name="type_radio"]:checked');
            const typeHidden = document.getElementById('edit_type_hidden');
            if (checked && typeHidden) typeHidden.value = checked.value;
        });
    }
});

function openEditModal() {
    if (!editModal) {
        editModal = document.getElementById('editModal');
    }
    if (editModal) {
        editModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeEditModal() {
    if (!editModal) {
        editModal = document.getElementById('editModal');
    }
    if (editModal) {
        editModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!editModal) {
            editModal = document.getElementById('editModal');
        }
        if (editModal && !editModal.classList.contains('hidden')) {
            closeEditModal();
        }
    }
});

// Confirmación antes de editar
function confirmEdit(event, maintenanceId) {
    // Prevenir cualquier comportamiento por defecto
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    Swal.fire({
        title: 'Confirmar edición',
        text: '¿Está seguro de que desea editar este registro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, editar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            openEditMaintenanceModal(maintenanceId);
        }
    });
    
    return false;
}

// Función para mostrar/ocultar campo de fecha de fin
function toggleEndDateField() {
    const typeMaintenance = document.getElementById('edit_type_maintenance');
    const endDateContainer = document.getElementById('edit_end_date_container');
    const dateInput = document.getElementById('edit_date');
    const endDateInput = document.getElementById('edit_end_date');
    
    if (typeMaintenance && typeMaintenance.checked) {
        endDateContainer.style.display = 'block';
        if (dateInput && dateInput.value) {
            endDateInput.min = dateInput.value;
        }
    } else {
        if (!endDateInput || !endDateInput.value) {
            endDateContainer.style.display = 'none';
        } else {
            endDateContainer.style.display = 'block';
        }
    }
}

// Función para abrir modal de edición con datos
function openEditMaintenanceModal(maintenanceId) {
    fetch(`/admin/machinery/maintenance/${maintenanceId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('La respuesta no es JSON');
            }
            return response.json();
        })
        .then(result => {
            const data = result.maintenance;
            const machineries = result.machineries || [];
            
            // Asegurarse de que los elementos existan
            if (!editForm) {
                editForm = document.getElementById('editForm');
            }
            if (!editModal) {
                editModal = document.getElementById('editModal');
            }
            
            if (!editForm || !editModal) {
                throw new Error('No se encontraron los elementos del modal');
            }
            
            // Actualizar ID en el header
            const editMaintenanceId = document.getElementById('editMaintenanceId');
            if (editMaintenanceId) {
                editMaintenanceId.textContent = data.id.toString().padStart(3, '0');
            }
            
            // Configurar acción del formulario
            editForm.action = `/admin/machinery/maintenance/${maintenanceId}`;
            
            // Llenar campos
            document.getElementById('edit_date').value = data.date || '';
            document.getElementById('edit_responsible').value = data.responsible || '';
            document.getElementById('edit_description').value = data.description || '';
            
            if (data.end_date) {
                document.getElementById('edit_end_date').value = data.end_date || '';
            }
            
            // Seleccionar tipo y sincronizar campo oculto (name="type") que es el que se envía al guardar
            const typeHidden = document.getElementById('edit_type_hidden');
            if (data.type === 'M') {
                document.getElementById('edit_type_maintenance').checked = true;
                if (typeHidden) typeHidden.value = 'M';
            } else {
                document.getElementById('edit_type_operation').checked = true;
                if (typeHidden) typeHidden.value = 'O';
            }
            
            // Llenar select de maquinarias
            const select = document.getElementById('edit_machinery_id');
            select.innerHTML = '<option value="">Seleccionar maquinaria</option>';
            machineries.forEach(machinery => {
                const option = document.createElement('option');
                option.value = machinery.id;
                option.textContent = `${machinery.name} - ${machinery.brand} ${machinery.model}`;
                if (machinery.id == data.machinery_id) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
            
            // Configurar eventos para mostrar/ocultar fecha de fin (sin clonar los radios para que type se envíe al guardar)
            const typeMaintenance = document.getElementById('edit_type_maintenance');
            const typeOperation = document.getElementById('edit_type_operation');
            const dateInput = document.getElementById('edit_date');
            const endDateInput = document.getElementById('edit_end_date');
            
            typeMaintenance.removeEventListener('change', toggleEndDateField);
            typeOperation.removeEventListener('change', toggleEndDateField);
            typeMaintenance.addEventListener('change', function() {
                toggleEndDateField();
                const h = document.getElementById('edit_type_hidden');
                if (h) h.value = 'M';
            });
            typeOperation.addEventListener('change', function() {
                toggleEndDateField();
                const h = document.getElementById('edit_type_hidden');
                if (h) h.value = 'O';
            });
            
            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    if (endDateInput && typeMaintenance.checked) {
                        endDateInput.min = this.value;
                    }
                });
            }
            
            // Inicializar estado del campo de fecha de fin
            toggleEndDateField();
            
            // Contador de caracteres
            const descriptionTextarea = document.getElementById('edit_description');
            const charCount = document.getElementById('edit_char-count');
            
            function updateCharCount() {
                const count = descriptionTextarea.value.length;
                charCount.textContent = `${count}/1000`;
                if (count > 900) {
                    charCount.classList.add('text-red-500');
                    charCount.classList.remove('text-gray-500');
                } else {
                    charCount.classList.remove('text-red-500');
                    charCount.classList.add('text-gray-500');
                }
            }
            
            descriptionTextarea.addEventListener('input', updateCharCount);
            updateCharCount();
            
            // Mostrar modal
            openEditModal();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Error al cargar los datos del registro. Por favor, intente nuevamente.',
                icon: 'error',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Entendido'
            });
        });
}

// Función para confirmar eliminación con SweetAlert2
function confirmDelete(event, form) {
    event.preventDefault();
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Quieres eliminar este registro de actividad?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-lg',
            title: 'text-lg font-semibold',
            content: 'text-sm text-gray-600',
            confirmButton: 'px-4 py-2 rounded-lg font-medium',
            cancelButton: 'px-4 py-2 rounded-lg font-medium'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Eliminando...',
                text: 'Por favor espera',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar formulario
            form.submit();
        }
    });
    
    return false;
}

// Mostrar mensaje de éxito si existe
@if(session('success'))
    Swal.fire({
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#22c55e',
        confirmButtonText: 'Entendido',
        customClass: {
            popup: 'rounded-lg',
            title: 'text-lg font-semibold text-green-600',
            content: 'text-sm text-gray-600',
            confirmButton: 'px-4 py-2 rounded-lg font-medium'
        }
    });
@endif

// Mostrar mensaje de error si existe
@if(session('error'))
    Swal.fire({
        title: '¡Error!',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Entendido',
        customClass: {
            popup: 'rounded-lg',
            title: 'text-lg font-semibold text-red-600',
            content: 'text-sm text-gray-600',
            confirmButton: 'px-4 py-2 rounded-lg font-medium'
        }
    });
@endif

// Inicializar DataTables
document.addEventListener('DOMContentLoaded', function() {
    if (typeof DataTable === 'undefined') {
        console.error('DataTable no está cargado. Verifica que el script de DataTables esté incluido.');
        return;
    }
    
    // Verificar que la tabla exista y que haya registros
    const tableElement = document.querySelector('#maintenancesTable');
    if (!tableElement) {
        console.log('No hay tabla para inicializar DataTables (no hay registros)');
        return;
    }
    
    // Verificar que haya filas de datos (no solo el thead)
    const tbody = tableElement.querySelector('tbody');
    if (!tbody || tbody.children.length === 0) {
        console.log('No hay registros para mostrar en DataTables');
        return;
    }
    
    window.maintenancesDataTable = new DataTable('#maintenancesTable', {
        language: {
            search: 'Buscar:',
            lengthMenu: 'Mostrar _MENU_ registros',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
            infoEmpty: 'Mostrando 0 a 0 de 0 registros',
            infoFiltered: '(filtrado de _MAX_ registros totales)',
            zeroRecords: 'No se encontraron registros',
            emptyTable: 'No hay datos disponibles',
            paginate: {
                first: '«',
                previous: '<',
                next: '>',
                last: '»'
            }
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        order: [[2, 'desc']], // Ordenar por fecha descendente
        processing: false,
        serverSide: false,
        dom: 'rtip',
        initComplete: function() {
            const lengthContainer = document.createElement('div');
            lengthContainer.className = 'dataTables_length';
            lengthContainer.innerHTML = `
                <label>
                    Mostrar
                    <select name="maintenancesTable_length" aria-controls="maintenancesTable" class="px-3 py-2 border border-gray-300 rounded-lg ml-2">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="-1">Todos</option>
                    </select>
                    registros
                </label>
            `;
            
            const filterContainer = document.createElement('div');
            filterContainer.className = 'dataTables_filter';
            filterContainer.innerHTML = `
                <label>
                    Buscar:
                    <input type="search" class="px-3 py-2 border border-gray-300 rounded-lg ml-2" placeholder="Buscar..." aria-controls="maintenancesTable" style="width: 250px; outline: none; transition: none;">
                </label>
            `;
            
            const lengthTarget = document.getElementById('dt-length-container');
            const filterTarget = document.getElementById('dt-filter-container');
            
            if (lengthTarget) lengthTarget.appendChild(lengthContainer);
            if (filterTarget) filterTarget.appendChild(filterContainer);
            
            const lengthSelect = lengthContainer.querySelector('select');
            const searchInput = filterContainer.querySelector('input');
            
            if (lengthSelect) {
                lengthSelect.addEventListener('change', function() {
                    window.maintenancesDataTable.page.len(parseInt(this.value)).draw();
                });
            }
            
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    window.maintenancesDataTable.search(this.value).draw();
                });
            }
        }
    });

    document.getElementById('btn-download-all-pdf')?.addEventListener('click', function() {
        let url = '{{ route("admin.machinery.maintenance.download.all-pdf") }}';
        if (window.maintenancesDataTable) {
            const ids = [];
            window.maintenancesDataTable.rows({ search: 'applied' }).every(function() {
                const row = this.node();
                const id = row.getAttribute('data-id');
                if (id) ids.push(id);
            });
            if (ids.length > 0) {
                url += '?ids=' + ids.join(',');
            }
        }
        window.location.href = url;
    });

    function formatCountdown(totalSeconds) {
        if (totalSeconds == null || totalSeconds < 0) return '--';
        if (totalSeconds <= 0) return '0d 0h 0m 0s';
        const d = Math.floor(totalSeconds / 86400);
        const h = Math.floor((totalSeconds % 86400) / 3600);
        const m = Math.floor((totalSeconds % 3600) / 60);
        const s = totalSeconds % 60;
        return d + 'd ' + h + 'h ' + m + 'm ' + s + 's';
    }
    function updateMaintenanceRowCountdowns() {
        document.querySelectorAll('.maintenance-row-countdown').forEach(function(el) {
            const nextDue = el.getAttribute('data-next-due');
            if (!nextDue) {
                el.textContent = '--';
                return;
            }
            const end = new Date(nextDue);
            const now = new Date();
            const sec = Math.max(0, Math.floor((end - now) / 1000));
            el.textContent = formatCountdown(sec);
        });
    }
    updateMaintenanceRowCountdowns();
    setInterval(updateMaintenanceRowCountdowns, 1000);
});
</script>

<style>
/* Estilos para DataTables */
.dataTables_wrapper {
    position: relative;
    clear: both;
    width: 100%;
}

.dataTables_wrapper .dataTables_length {
    float: left !important;
    margin-bottom: 1rem;
    padding: 0.5rem 0;
    clear: none !important;
    width: auto !important;
}

.dataTables_wrapper .dataTables_filter {
    float: right !important;
    margin-bottom: 1rem;
    padding: 0.5rem 0;
    text-align: right !important;
    clear: none !important;
    width: auto !important;
}

.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    color: #374151;
    margin: 0;
    white-space: nowrap;
}

.dataTables_wrapper .dataTables_length select {
    margin-left: 0.5rem;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    min-width: 60px;
}

.dataTables_wrapper .dataTables_filter input {
    margin-left: 0.5rem;
    padding: 0.5rem;
    border: 1px solid #d1d5db !important;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    width: 250px;
    outline: none !important;
    transition: none;
    background-color: white;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #d1d5db !important;
    box-shadow: none !important;
    outline: none !important;
    background-color: white !important;
}

.dataTables_wrapper .dataTables_filter input:hover {
    border-color: #9ca3af !important;
    box-shadow: none !important;
    background-color: white !important;
}

.dataTables_wrapper .dataTables_info {
    float: left;
    padding: 0.75rem 0;
    margin-top: 1.5rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_paginate {
    float: right;
    text-align: right;
    padding: 0.75rem 0;
    margin-top: 1.5rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.375rem 0.625rem;
    margin: 0 0.125rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background: white;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-block;
    text-decoration: none;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f3f4f6 !important;
    border-color: #d1d5db !important;
    color: #374151 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #22c55e;
    color: white;
    border-color: #22c55e;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.dataTables_wrapper::after {
    content: "";
    display: table;
    clear: both;
}
</style>
@endsection

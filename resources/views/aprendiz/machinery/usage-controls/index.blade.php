@extends('layouts.masteraprendiz')

@section('content')
@vite(['resources/css/waste.css'])

@php
    use Illuminate\Support\Facades\Storage;
@endphp

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-clipboard-check waste-icon"></i>
                    Control de Uso del Equipo
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Panel de Aprendiz
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Registros -->
        <div class="waste-card waste-card-primary animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Registros</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalUsageControls }}</div>
                </div>
                <div class="waste-card-icon text-blue-600">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>

        <!-- Total Horas -->
        <div class="waste-card waste-card-success animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Horas</div>
                    <div class="text-3xl font-bold text-gray-800">{{ number_format($totalHours, 0) }}</div>
                </div>
                <div class="waste-card-icon text-green-600">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <!-- Registros Hoy -->
        <div class="waste-card waste-card-warning animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Registros Hoy</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $todayUsageControls }}</div>
                </div>
                <div class="waste-card-icon text-yellow-600">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <!-- Este Mes -->
        <div class="waste-card waste-card-info animate-fade-in-up animate-delay-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Este Mes</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $thisMonthUsageControls }}</div>
                </div>
                <div class="waste-card-icon text-cyan-600">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Table Header -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <!-- Primera fila: Título y botones -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-clipboard-check text-green-600 mr-2"></i>
                    Registros de Uso del Equipo
                </h2>
                <div class="flex items-center space-x-4">
                    @if($usageControls->count() > 0)
                        <a href="{{ route('aprendiz.machinery.usage-control.download.all-pdf') }}" class="bg-red-500 text-white border border-red-600 hover:bg-red-600 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    @endif
                    <a href="{{ route('aprendiz.machinery.usage-control.create') }}" class="bg-green-400 text-green-800 border border-green-500 hover:bg-green-500 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Registro
                    </a>
                </div>
            </div>
        </div>

        @if($usageControls->count() > 0)
            <!-- Tabla de controles de uso -->
            <div class="overflow-x-auto">
                <!-- DataTables agregará los controles y la tabla aquí -->
                <div id="usageControlsTable_wrapper" class="p-6">
                    <!-- Contenedor para controles superiores -->
                    <div style="width: 100%; overflow: hidden; margin-bottom: 1rem;">
                        <div id="dt-length-container" style="float: left;"></div>
                        <div id="dt-filter-container" style="float: right;"></div>
                    </div>
                    <table id="usageControlsTable" class="waste-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Maquinaria</th>
                                <th>Fecha/Hora Inicio</th>
                                <th>Fecha/Hora Fin</th>
                                <th>Total Horas</th>
                                <th>Responsable</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usageControls as $usageControl)
                            <tr>
                                <td class="font-mono">#{{ str_pad($usageControl->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    @if($usageControl->machinery && $usageControl->machinery->image)
                                        <img src="{{ Storage::url($usageControl->machinery->image) }}?v={{ $usageControl->machinery->updated_at->timestamp }}" 
                                             alt="Imagen de maquinaria" 
                                             class="w-12 h-12 object-cover rounded-full cursor-pointer hover:opacity-80 transition-opacity"
                                             onclick="openImageModal('{{ Storage::url($usageControl->machinery->image) }}?v={{ $usageControl->machinery->updated_at->timestamp }}')"
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
                                <td>
                                    <div class="font-semibold">{{ $usageControl->machinery->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $usageControl->machinery->brand ?? '' }} {{ $usageControl->machinery->model ?? '' }}</div>
                                </td>
                                <td>{{ $usageControl->start_date ? $usageControl->start_date->setTimezone('America/Bogota')->format('d/m/Y h:i A') : 'N/A' }}</td>
                                <td>{{ $usageControl->end_date ? $usageControl->end_date->setTimezone('America/Bogota')->format('d/m/Y h:i A') : 'N/A' }}</td>
                                <td class="font-semibold">{{ $usageControl->hours ?? 0 }} hrs</td>
                                <td>{{ $usageControl->responsible }}</td>
                                <td>
                                    <div class="flex space-x-2 items-center">
                                        <button onclick="openViewModal({{ $usageControl->id }})" 
                                           class="inline-flex items-center text-blue-500 hover:text-blue-700" title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="confirmEdit({{ $usageControl->id }})" 
                                           class="inline-flex items-center text-green-500 hover:text-green-700" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('aprendiz.machinery.usage-control.download.pdf', $usageControl) }}" 
                                           class="inline-flex items-center text-red-500 hover:text-red-700" 
                                           title="Descargar PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        
                                        @php
                                            $isApproved = isset($approvedUsageControlIds) && in_array($usageControl->id, $approvedUsageControlIds);
                                            $isPending = isset($pendingUsageControlIds) && in_array($usageControl->id, $pendingUsageControlIds);
                                            $isRejected = isset($rejectedUsageControlIds) && in_array($usageControl->id, $rejectedUsageControlIds);
                                        @endphp

                                        @if($isRejected)
                                            <button type="button" class="inline-flex items-center text-red-600 hover:text-red-800" title="Solicitud rechazada"
                                                onclick="showRejectedAlert({{ $usageControl->id }})">
                                                <i class="fas fa-ban text-lg"></i>
                                            </button>
                                        @elseif($isApproved)
                                            <form id="delete-form-{{ $usageControl->id }}" action="{{ route('aprendiz.machinery.usage-control.destroy', $usageControl) }}" method="POST" class="inline-flex items-center" style="margin: 0; padding: 0; margin-left: 0.5rem;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="inline-flex items-center text-red-500 hover:text-red-700" title="Eliminar"
                                                    onclick="confirmDelete('delete-form-{{ $usageControl->id }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @elseif($isPending)
                                            <button type="button" class="inline-flex items-center text-yellow-500 cursor-default" title="Permiso pendiente de aprobación">
                                                <i class="fas fa-hourglass-half"></i>
                                            </button>
                                        @else
                                            <button id="deleteBtn{{ $usageControl->id }}" onclick="requestDeletePermission({{ $usageControl->id }})" 
                                               class="inline-flex items-center text-red-500 hover:text-red-700" title="Solicitar Eliminación">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
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
                    <i class="fas fa-clipboard-check text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay registros de uso del equipo</h3>
                <p class="text-gray-600">Comienza registrando tu primer control de uso en el sistema.</p>
            </div>
        @endif
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

<!-- Modal para ver detalles del uso del equipo -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header">
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
                <!-- Imagen de la maquinaria -->
                <div id="viewImageContainer" class="text-center">
                    <img id="viewImage" src="" alt="Imagen de maquinaria" 
                         class="max-w-full h-64 object-cover rounded-lg shadow-md mx-auto cursor-pointer"
                         onclick="openImageModal(this.src)">
                </div>

                <!-- Información del registro -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="waste-form-group">
                        <label class="waste-form-label">Maquinaria</label>
                        <div class="waste-form-input bg-gray-50" id="viewMachinery"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha/Hora Inicio</label>
                        <div class="waste-form-input bg-gray-50" id="viewStartDate"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha/Hora Fin</label>
                        <div class="waste-form-input bg-gray-50" id="viewEndDate"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Total Horas</label>
                        <div class="waste-form-input bg-gray-50 font-semibold" id="viewHours"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Responsable</label>
                        <div class="waste-form-input bg-gray-50" id="viewResponsible"></div>
                    </div>

                    <div class="waste-form-group md:col-span-2">
                        <label class="waste-form-label">Observaciones</label>
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
function openViewModal(usageControlId) {
    fetch(`/admin/machinery/usage-control/${usageControlId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('viewRecordId').textContent = data.id.toString().padStart(3, '0');
            document.getElementById('viewMachinery').textContent = `${data.machinery_name} - ${data.machinery_brand} ${data.machinery_model}`;
            // Formatear fecha/hora en formato colombiano con AM/PM
            if (data.start_date) {
                const startDate = new Date(data.start_date);
                const options = { 
                    timeZone: 'America/Bogota',
                    day: '2-digit', 
                    month: '2-digit', 
                    year: 'numeric', 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: true
                };
                document.getElementById('viewStartDate').textContent = startDate.toLocaleString('es-CO', options);
            } else {
                document.getElementById('viewStartDate').textContent = data.start_date_formatted || 'N/A';
            }
            
            if (data.end_date) {
                const endDate = new Date(data.end_date);
                const options = { 
                    timeZone: 'America/Bogota',
                    day: '2-digit', 
                    month: '2-digit', 
                    year: 'numeric', 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: true
                };
                document.getElementById('viewEndDate').textContent = endDate.toLocaleString('es-CO', options);
            } else {
                document.getElementById('viewEndDate').textContent = data.end_date_formatted || 'N/A';
            }
            document.getElementById('viewHours').textContent = `${data.hours} horas`;
            document.getElementById('viewResponsible').textContent = data.responsible || 'N/A';
            document.getElementById('viewDescription').textContent = data.description || 'Sin observaciones';
            document.getElementById('viewCreatedAt').textContent = data.created_at_formatted || data.created_at;
            
            // Mostrar imagen si existe
            if (data.machinery_image_url) {
                document.getElementById('viewImage').src = data.machinery_image_url;
                document.getElementById('viewImageContainer').style.display = 'block';
            } else {
                document.getElementById('viewImageContainer').style.display = 'none';
            }
            
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

// Confirmación antes de editar
function confirmEdit(usageControlId) {
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
            window.location.href = `/admin/machinery/usage-control/${usageControlId}/edit`;
        }
    });
}

// Función para confirmar eliminación con SweetAlert2
function confirmDelete(formId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Quieres eliminar este registro de uso del equipo?',
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
            
            document.getElementById(formId).submit();
        }
    });
}

function showRejectedAlert(usageControlId) {
    Swal.fire({
        title: 'Solicitud rechazada',
        text: 'Esta solicitud de eliminación ha sido rechazada por el administrador. No puede eliminar este registro.',
        icon: 'error',
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Entendido'
    });
}

function requestDeletePermission(usageControlId) {
    Swal.fire({
        title: 'Solicitar permiso',
        text: '¿Desea solicitar permiso al administrador para eliminar este registro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, solicitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/aprendiz/machinery/usage-control/${usageControlId}/request-delete`;
            
            const tokenField = document.createElement('input');
            tokenField.type = 'hidden';
            tokenField.name = '_token';
            tokenField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            form.appendChild(tokenField);
            document.body.appendChild(form);
            form.submit();
        }
    });
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
    const tableElement = document.querySelector('#usageControlsTable');
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
    
    let table = new DataTable('#usageControlsTable', {
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
        order: [[3, 'desc']], // Ordenar por fecha/hora inicio descendente
        processing: false,
        serverSide: false,
        dom: 'rtip',
        initComplete: function() {
            const lengthContainer = document.createElement('div');
            lengthContainer.className = 'dataTables_length';
            lengthContainer.innerHTML = `
                <label>
                    Mostrar
                    <select name="usageControlsTable_length" aria-controls="usageControlsTable" class="px-3 py-2 border border-gray-300 rounded-lg ml-2">
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
                    <input type="search" class="px-3 py-2 border border-gray-300 rounded-lg ml-2" placeholder="Buscar..." aria-controls="usageControlsTable" style="width: 250px; outline: none; transition: none;">
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
                    table.page.len(parseInt(this.value)).draw();
                });
            }
            
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    table.search(this.value).draw();
                });
            }
        }
    });
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



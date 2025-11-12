@extends('layouts.master')

@section('content')
@vite(['resources/css/waste.css'])

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-seedling waste-icon"></i>
                    Gestión de Abono Terminado
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    {{ Auth::user()?->name ?? 'Usuario' }} - Admin Panel
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Amount -->
        <div class="waste-card waste-card-primary animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Cantidad Total</div>
                    <div class="text-3xl font-bold text-gray-800">{{ number_format($totalAmount, 2) }} Kg/L</div>
                </div>
                <div class="waste-card-icon text-blue-600">
                    <i class="fas fa-weight"></i>
                </div>
            </div>
        </div>

        <!-- Total Records -->
        <div class="waste-card waste-card-success animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Registros</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalRecords }}</div>
                </div>
                <div class="waste-card-icon text-green-600">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>

        <!-- Today Records -->
        <div class="waste-card waste-card-warning animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Registros Hoy</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $todayRecords }}</div>
                </div>
                <div class="waste-card-icon text-yellow-600">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <!-- Today Amount -->
        <div class="waste-card waste-card-info animate-fade-in-up animate-delay-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Cantidad Hoy</div>
                    <div class="text-3xl font-bold text-gray-800">{{ number_format($todayAmount, 2) }} Kg/L</div>
                </div>
                <div class="waste-card-icon text-cyan-600">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="waste-container animate-fade-in-up animate-delay-2">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-green-600 mr-2"></i>
                Registros de Abono Terminado
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.fertilizer.download.all-pdf') }}" class="bg-red-500 text-white border border-red-600 hover:bg-red-600 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-file-pdf"></i>
                </a>
                <a href="{{ route('admin.fertilizer.create') }}" class="bg-green-400 text-green-800 border border-green-500 hover:bg-green-500 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Registro
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <!-- DataTables agregará los controles y la tabla aquí -->
            <div id="fertilizersTable_wrapper" class="p-6">
                <!-- Contenedor para controles superiores -->
                <div style="width: 100%; overflow: hidden; margin-bottom: 1rem;">
                    <div id="dt-length-container" style="float: left;"></div>
                    <div id="dt-filter-container" style="float: right;"></div>
                </div>
                <table id="fertilizersTable" class="waste-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Pila</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Solicitante</th>
                            <th>Destino</th>
                            <th>Recibido Por</th>
                            <th>Entregado Por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fertilizers as $fertilizer)
                        <tr>
                            <td class="font-mono">#{{ str_pad($fertilizer->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $fertilizer->formatted_date }}</td>
                            <td>{{ $fertilizer->time }}</td>
                            <td>
                                @if($fertilizer->composting)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-mountain mr-1"></i>
                                        {{ $fertilizer->composting->formatted_pile_num }}
                                    </span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="waste-badge 
                                    @if($fertilizer->type == 'Liquid') waste-badge-info
                                    @else waste-badge-success
                                    @endif">
                                    {{ $fertilizer->type_in_spanish }}
                                </span>
                            </td>
                            <td class="font-semibold">{{ $fertilizer->formatted_amount }}</td>
                            <td>{{ $fertilizer->requester }}</td>
                            <td>{{ $fertilizer->destination }}</td>
                            <td>{{ $fertilizer->received_by }}</td>
                            <td>{{ $fertilizer->delivered_by }}</td>
                            <td>
                                <div class="flex space-x-2 items-center">
                                    <button onclick="openViewModal({{ $fertilizer->id }})" 
                                       class="inline-flex items-center text-blue-400 hover:text-blue-500" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="openEditModal({{ $fertilizer->id }})" 
                                       class="inline-flex items-center text-green-500 hover:text-green-700" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.fertilizer.destroy', $fertilizer) }}" 
                                          method="POST" class="inline" 
                                          onsubmit="return confirmDelete(event, this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center text-red-500 hover:text-red-700" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.fertilizer.download.pdf', $fertilizer) }}" 
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
    </div>
</div>

<!-- Modal para ver detalles -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="waste-header">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-eye waste-icon"></i>
                    Detalles del Abono
                </h3>
            </div>
        </div>
        <div class="p-6">
            <div id="viewContent" class="space-y-4">
                <!-- Contenido se llenará con JavaScript -->
            </div>
            <div class="flex justify-end pt-6 border-t border-gray-200 mt-6">
                <button onclick="closeViewModal()" class="waste-btn-secondary">
                    <i class="fas fa-times mr-2"></i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="waste-header">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-edit waste-icon"></i>
                    Editar Registro de Abono
                </h3>
            </div>
        </div>
        <div class="p-6">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div id="editContent">
                    <!-- Contenido se llenará con JavaScript -->
                </div>
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                    <button type="button" onclick="closeEditModal()" class="waste-btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="waste-btn">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Estilos para DataTables */
.dataTables_wrapper {
    position: relative;
    clear: both;
    width: 100%;
}

/* Contenedor superior: Mostrar (izquierda) y Buscar (derecha) - MISMA LÍNEA */
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

.dataTables_wrapper .dataTables_filter input:active {
    border-color: #d1d5db !important;
    outline: none !important;
}

/* Información y paginación inferior */
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

/* Paginación más pequeña */
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

/* Limpiar floats */
.dataTables_wrapper::after {
    content: "";
    display: table;
    clear: both;
}
</style>

<script>
// Inicializar DataTables
document.addEventListener('DOMContentLoaded', function() {
    if (typeof DataTable === 'undefined') {
        console.error('DataTable no está cargado.');
        return;
    }
    
    const tableElement = document.querySelector('#fertilizersTable');
    if (!tableElement) {
        console.error('No se encontró la tabla con id #fertilizersTable');
        return;
    }
    
    let table = new DataTable('#fertilizersTable', {
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
        order: [[1, 'desc']],
        processing: false,
        serverSide: false,
        dom: 'rtip',
        initComplete: function() {
            const lengthContainer = document.createElement('div');
            lengthContainer.className = 'dataTables_length';
            lengthContainer.innerHTML = `
                <label>
                    Mostrar
                    <select name="fertilizersTable_length" aria-controls="fertilizersTable" class="px-3 py-2 border border-gray-300 rounded-lg ml-2">
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
                    <input type="search" class="px-3 py-2 border border-gray-300 rounded-lg ml-2" placeholder="Buscar..." aria-controls="fertilizersTable" style="width: 250px; outline: none; transition: none;">
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

function openViewModal(fertilizerId) {
    fetch(`/admin/fertilizer/${fertilizerId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('viewContent').innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><strong>ID:</strong> #${data.id.toString().padStart(3, '0')}</div>
                <div><strong>Fecha:</strong> ${data.formatted_date}</div>
                <div><strong>Hora:</strong> ${data.time}</div>
                <div><strong>Pila:</strong> ${data.composting ? data.composting.formatted_pile_num : 'N/A'}</div>
                <div><strong>Tipo:</strong> ${data.type_in_spanish}</div>
                <div><strong>Cantidad:</strong> ${data.formatted_amount}</div>
                <div><strong>Solicitante:</strong> ${data.requester}</div>
                <div><strong>Destino:</strong> ${data.destination}</div>
                <div><strong>Recibido Por:</strong> ${data.received_by}</div>
                <div><strong>Entregado Por:</strong> ${data.delivered_by}</div>
                <div class="md:col-span-2"><strong>Notas:</strong> ${data.notes || 'Sin notas'}</div>
            </div>
        `;
        document.getElementById('viewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cargar los datos');
    });
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openEditModal(fertilizerId) {
    window.location.href = `/admin/fertilizer/${fertilizerId}/edit`;
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function confirmDelete(event, form) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Quieres eliminar este registro de abono?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}

@if(session('success'))
    Swal.fire({
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#22c55e'
    });
@endif
</script>
@endsection


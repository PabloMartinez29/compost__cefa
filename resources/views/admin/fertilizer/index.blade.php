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
                    <i class="fas fa-seedling waste-icon"></i>
                    Gestión de Abono Terminado
                </h1>
                <p class="waste-subtitle text-sm sm:text-base">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    <span class="break-words">{{ Auth::user()?->name ?? 'Usuario' }} - Admin Panel</span>
                </p>
            </div>
            <div class="text-left sm:text-right flex-shrink-0">
                <div class="text-green-400 font-bold text-base sm:text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
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
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Table Header -->
        <div class="p-3 sm:p-4 md:p-6 border-b border-gray-200 bg-gray-50">
            <!-- Primera fila: Título y botones -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4 mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-seedling text-green-600 mr-2"></i>
                    Registros de Abono Terminado
                </h2>
                <div class="flex items-center space-x-2 sm:space-x-4 w-full sm:w-auto">
                    @if($fertilizers->count() > 0)
                        <button type="button" id="btn-download-all-pdf" class="bg-red-500 text-white border border-red-600 hover:bg-red-600 px-3 sm:px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm text-sm sm:text-base" title="Descargar PDF de los registros visibles (filtrados)">
                            <i class="fas fa-file-pdf"></i>
                            <span class="hidden sm:inline ml-2">PDF</span>
                        </button>
                    @endif
                    <a href="{{ route('admin.fertilizer.create') }}" class="bg-green-400 text-green-800 border border-green-500 hover:bg-green-500 px-3 sm:px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                        <i class="fas fa-plus mr-2"></i>
                        <span class="hidden sm:inline">Nuevo Registro</span>
                        <span class="sm:hidden">Nuevo</span>
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-3 sm:px-4 py-2 sm:py-3 rounded m-3 sm:m-6 text-sm sm:text-base">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 sm:px-4 py-2 sm:py-3 rounded m-3 sm:m-6 text-sm sm:text-base">
                {{ session('error') }}
            </div>
        @endif

        @if($fertilizers->count() > 0)
            <!-- Vista móvil: tarjetas -->
            <div class="block md:hidden p-3 sm:p-4 space-y-4">
                @foreach($fertilizers as $fertilizer)
                    <div class="waste-mobile-card bg-gray-50 border border-gray-200 rounded-xl p-4 shadow-sm" data-id="{{ $fertilizer->id }}">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900">#{{ str_pad($fertilizer->id, 3, '0', STR_PAD_LEFT) }} · {{ $fertilizer->formatted_date }}</h3>
                            <p class="text-sm text-gray-600">{{ $fertilizer->requester }} → {{ $fertilizer->destination }}</p>
                            <span class="waste-badge @if($fertilizer->type == 'Liquid') waste-badge-info @else waste-badge-success @endif">{{ $fertilizer->type_in_spanish }}</span>
                            <span class="ml-2 font-semibold text-gray-800">{{ $fertilizer->formatted_amount }}</span>
                            @if($fertilizer->composting)<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 ml-2">{{ $fertilizer->composting->formatted_pile_num }}</span>@endif
                        </div>
                        <div class="waste-mobile-card-actions mt-4 pt-3 border-t border-gray-200">
                            <button type="button" onclick="openViewModal({{ $fertilizer->id }})" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg flex-shrink-0" title="Ver"><i class="fas fa-eye"></i></button>
                            <button type="button" onclick="openEditModal({{ $fertilizer->id }})" class="p-2 text-green-600 hover:bg-green-50 rounded-lg flex-shrink-0" title="Editar"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.fertilizer.destroy', $fertilizer) }}" method="POST" class="inline flex-shrink-0" onsubmit="return confirmDelete(event, this)">@csrf @method('DELETE')<button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg" title="Eliminar"><i class="fas fa-trash"></i></button></form>
                            <a href="{{ route('admin.fertilizer.download.pdf', $fertilizer) }}" class="p-2 text-red-700 hover:bg-red-50 rounded-lg flex-shrink-0" title="PDF"><i class="fas fa-file-pdf"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tabla de abonos (escritorio) -->
            <div class="hidden md:block overflow-x-auto -mx-3 sm:mx-0">
                <!-- DataTables agregará los controles y la tabla aquí -->
                <div id="fertilizersTable_wrapper" class="p-3 sm:p-4 md:p-6">
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
                        <tr data-id="{{ $fertilizer->id }}">
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
        @else
            <!-- Estado vacío -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-seedling text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay registros de abono terminado</h3>
                <p class="text-gray-600">Comienza registrando tu primer abono terminado en el sistema.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal para ver detalles -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="waste-header">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-eye waste-icon"></i>
                    Detalles del Abono - Registro #<span id="viewRecordId"></span>
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
        <!-- Modal Header -->
        <div class="waste-header">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-edit waste-icon"></i>
                    Editar Registro de Abono
                </h3>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    <span id="editUserInfo">{{ Auth::user()?->name ?? 'Usuario' }} - Registro #<span id="editFertilizerId"></span></span>
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
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Fecha -->
                    <div class="waste-form-group">
                        <label for="edit_date" class="waste-form-label">Fecha *</label>
                        <input type="date" id="edit_date" name="date" required
                               class="waste-form-input" />
                    </div>

                    <!-- Hora -->
                    <div class="waste-form-group">
                        <label for="edit_time" class="waste-form-label">Hora *</label>
                        <input type="time" id="edit_time" name="time" required
                               class="waste-form-input" />
                    </div>

                    <!-- Pila (solo lectura) -->
                    <div class="waste-form-group md:col-span-2">
                        <label for="edit_composting_id" class="waste-form-label">Pila</label>
                        <input type="text" id="edit_composting_display" 
                               readonly
                               class="waste-form-input bg-gray-100 cursor-not-allowed" />
                        <input type="hidden" id="edit_composting_id" name="composting_id" />
                        <p class="text-gray-500 text-xs mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            La pila no se puede modificar después de crear el registro.
                        </p>
                    </div>

                    <!-- Solicitante -->
                    <div class="waste-form-group">
                        <label for="edit_requester" class="waste-form-label">Solicitante *</label>
                        <input type="text" id="edit_requester" name="requester" maxlength="150" required
                               placeholder="Nombre del solicitante"
                               class="waste-form-input" />
                    </div>

                    <!-- Destino -->
                    <div class="waste-form-group">
                        <label for="edit_destination" class="waste-form-label">Destino *</label>
                        <input type="text" id="edit_destination" name="destination" maxlength="150" required
                               placeholder="Lugar de destino"
                               class="waste-form-input" />
                    </div>

                    <!-- Quién Recibe -->
                    <div class="waste-form-group">
                        <label for="edit_received_by" class="waste-form-label">Quién Recibe *</label>
                        <input type="text" id="edit_received_by" name="received_by" maxlength="150" required
                               placeholder="Nombre de quien recibe"
                               class="waste-form-input" />
                    </div>

                    <!-- Quién Entrega -->
                    <div class="waste-form-group">
                        <label for="edit_delivered_by" class="waste-form-label">Quién Entrega *</label>
                        <input type="text" id="edit_delivered_by" name="delivered_by" maxlength="150" required
                               placeholder="Nombre de quien entrega"
                               class="waste-form-input" />
                    </div>

                    <!-- Tipo de Abono -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Tipo de Abono *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-green-50 transition-all duration-200">
                                <input type="radio" name="type" value="Liquid" id="edit_type_liquid"
                                       class="sr-only peer">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-green-700">Líquido</span>
                            </label>
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-green-50 transition-all duration-200">
                                <input type="radio" name="type" value="Solid" id="edit_type_solid"
                                       class="sr-only peer">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-green-700">Sólido</span>
                            </label>
                        </div>
                    </div>

                    <!-- Cantidad -->
                    <div class="waste-form-group">
                        <label for="edit_amount" class="waste-form-label">Cantidad (KG/L) *</label>
                        <div class="relative">
                            <input type="number" id="edit_amount" name="amount" step="0.01" min="0.01" required
                                   placeholder="0.00"
                                   class="waste-form-input pr-16" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <span class="text-gray-500 text-sm font-medium" id="edit_amountUnit">Kg</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notas -->
                    <div class="waste-form-group md:col-span-2">
                        <label for="edit_notes" class="waste-form-label">Notas</label>
                        <textarea id="edit_notes" name="notes" rows="4"
                                  placeholder="Observaciones adicionales sobre la entrega..."
                                  class="waste-form-textarea"></textarea>
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
    
    // Verificar que la tabla exista y que haya registros
    const tableElement = document.querySelector('#fertilizersTable');
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
    
    window.fertilizersDataTable = new DataTable('#fertilizersTable', {
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
                    window.fertilizersDataTable.page.len(parseInt(this.value)).draw();
                });
            }
            
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    window.fertilizersDataTable.search(this.value).draw();
                });
            }
        }
    });

    document.getElementById('btn-download-all-pdf')?.addEventListener('click', function() {
        let url = '{{ route("admin.fertilizer.download.all-pdf") }}';
        if (window.fertilizersDataTable) {
            const ids = [];
            window.fertilizersDataTable.rows({ search: 'applied' }).every(function() {
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
        document.getElementById('viewRecordId').textContent = data.id.toString().padStart(3, '0');
        document.getElementById('viewContent').innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="waste-form-group">
                    <label class="waste-form-label">Fecha</label>
                    <div class="waste-form-input bg-gray-50">${data.formatted_date}</div>
                </div>
                <div class="waste-form-group">
                    <label class="waste-form-label">Hora</label>
                    <div class="waste-form-input bg-gray-50">${data.time}</div>
                </div>
                <div class="waste-form-group">
                    <label class="waste-form-label">Pila</label>
                    <div class="waste-form-input bg-gray-50">${data.composting ? data.composting.formatted_pile_num : 'N/A'}</div>
                </div>
                <div class="waste-form-group">
                    <label class="waste-form-label">Tipo</label>
                    <div class="waste-form-input bg-gray-50">${data.type_in_spanish}</div>
                </div>
                <div class="waste-form-group">
                    <label class="waste-form-label">Cantidad</label>
                    <div class="waste-form-input bg-gray-50 font-semibold">${data.formatted_amount}</div>
                </div>
                <div class="waste-form-group">
                    <label class="waste-form-label">Solicitante</label>
                    <div class="waste-form-input bg-gray-50">${data.requester}</div>
                </div>
                <div class="waste-form-group">
                    <label class="waste-form-label">Destino</label>
                    <div class="waste-form-input bg-gray-50">${data.destination}</div>
                </div>
                <div class="waste-form-group">
                    <label class="waste-form-label">Recibido Por</label>
                    <div class="waste-form-input bg-gray-50">${data.received_by}</div>
                </div>
                <div class="waste-form-group">
                    <label class="waste-form-label">Entregado Por</label>
                    <div class="waste-form-input bg-gray-50">${data.delivered_by}</div>
                </div>
                <div class="waste-form-group md:col-span-2">
                    <label class="waste-form-label">Notas</label>
                    <div class="waste-form-textarea bg-gray-50" style="min-height: 100px;">${data.notes || 'Sin notas'}</div>
                </div>
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

// Funciones para el modal de edición
const editModal = document.getElementById('editModal');
const closeEditBtn = document.getElementById('closeEditModal');
const cancelEditBtn = document.getElementById('cancelEditModal');
const editForm = document.getElementById('editForm');

function showEditModal() {
    editModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    editModal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

closeEditBtn.addEventListener('click', closeEditModal);
cancelEditBtn.addEventListener('click', closeEditModal);
editModal.addEventListener('click', (e) => {
    // Cerrar solo cuando se hace clic directamente sobre el fondo del modal,
    // no cuando se interactúa con el contenido interno.
    if (e.target === editModal) {
        closeEditModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !editModal.classList.contains('hidden')) {
        closeEditModal();
    }
});

// Función para abrir modal de edición con datos
function openEditModal(fertilizerId) {
    fetch(`/admin/fertilizer/${fertilizerId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(result => {
            const data = result.fertilizer;
            
            // Actualizar ID en el header
            document.getElementById('editFertilizerId').textContent = data.id.toString().padStart(3, '0');
            
            // Configurar acción del formulario
            editForm.action = `/admin/fertilizer/${fertilizerId}`;
            
            // Llenar campos
            document.getElementById('edit_date').value = data.date || '';
            document.getElementById('edit_time').value = data.time || '';
            document.getElementById('edit_composting_id').value = data.composting_id || '';
            document.getElementById('edit_composting_display').value = data.composting ? data.composting.formatted_pile_num : 'N/A';
            document.getElementById('edit_requester').value = data.requester || '';
            document.getElementById('edit_destination').value = data.destination || '';
            document.getElementById('edit_received_by').value = data.received_by || '';
            document.getElementById('edit_delivered_by').value = data.delivered_by || '';
            document.getElementById('edit_amount').value = data.amount || '';
            document.getElementById('edit_notes').value = data.notes || '';
            
            // Seleccionar tipo de abono
            if (data.type === 'Liquid') {
                document.getElementById('edit_type_liquid').checked = true;
                document.getElementById('edit_amountUnit').textContent = 'L';
            } else {
                document.getElementById('edit_type_solid').checked = true;
                document.getElementById('edit_amountUnit').textContent = 'Kg';
            }
            
            // Actualizar unidad cuando cambie el tipo
            document.querySelectorAll('input[name="type"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'Liquid') {
                        document.getElementById('edit_amountUnit').textContent = 'L';
                    } else {
                        document.getElementById('edit_amountUnit').textContent = 'Kg';
                    }
                });
            });
            
            // Mostrar modal
            showEditModal();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del registro');
        });
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


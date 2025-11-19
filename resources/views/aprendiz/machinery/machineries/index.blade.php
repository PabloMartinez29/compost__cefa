@extends('layouts.masteraprendiz')

@section('title', 'Gestión de Maquinaria')

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
                    <i class="fas fa-cogs waste-icon"></i>
                    Gestión de Maquinaria
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Maquinarias -->
        <div class="waste-card waste-card-primary animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Maquinarias</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $machineries->count() }}</div>
                </div>
                <div class="waste-card-icon text-blue-600">
                    <i class="fas fa-cogs"></i>
                </div>
            </div>
        </div>

        <!-- Operación -->
        <div class="waste-card waste-card-success animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Operación</div>
                    <div class="text-3xl font-bold text-gray-800">
                        {{ $machineries->where('status', 'Operación')->count() }}
                    </div>
                </div>
                <div class="waste-card-icon text-green-600">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Mantenimiento -->
        <div class="waste-card waste-card-warning animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Mantenimiento</div>
                    <div class="text-3xl font-bold text-gray-800">
                        {{ $machineries->where('status', '!=', 'Operación')->count() }}
                    </div>
                </div>
                <div class="waste-card-icon text-yellow-600">
                    <i class="fas fa-wrench"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="waste-container animate-fade-in-up animate-delay-2">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-green-600 mr-2"></i>
                Registros de Maquinaria
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('aprendiz.machinery.download.all-pdf') }}" class="bg-red-500 text-white border border-red-600 hover:bg-red-600 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-file-pdf"></i>
                </a>
                <a href="{{ route('aprendiz.machinery.create') }}" class="bg-green-400 text-green-800 border border-green-500 hover:bg-green-500 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Registro
                </a>
            </div>
        </div>


        <!-- Tabla de maquinaria -->
        <div class="overflow-x-auto">
            <!-- DataTables agregará los controles y la tabla aquí -->
            <div id="machineriesTable_wrapper" class="p-6">
                <!-- Contenedor para controles superiores -->
                <div style="width: 100%; overflow: hidden; margin-bottom: 1rem;">
                    <div id="dt-length-container" style="float: left;"></div>
                    <div id="dt-filter-container" style="float: right;"></div>
                </div>
                <table id="machineriesTable" class="waste-table">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Maquinaria</th>
                            <th>Ubicación</th>
                            <th>Marca/Modelo</th>
                            <th>Serie</th>
                            <th>Estado</th>
                            <th>Mantenimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($machineries as $machinery)
                            <tr>
                                <td class="text-center align-middle">
                                    @if($machinery->image)
                                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0 mx-auto">
                                            <img src="{{ Storage::url($machinery->image) }}" 
                                                 alt="{{ $machinery->name }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0 mx-auto">
                                            <i class="fas fa-cogs text-green-600"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-left align-middle">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 break-words">
                                            {{ $machinery->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 break-words">
                                            Desde {{ $machinery->start_func->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="text-left align-middle">
                                    <div class="text-sm text-gray-900 break-words">{{ $machinery->location }}</div>
                                </td>
                                <td class="text-left align-middle">
                                    <div class="text-sm text-gray-900 break-words">{{ $machinery->brand }}</div>
                                    <div class="text-xs text-gray-500 break-words">{{ $machinery->model }}</div>
                                </td>
                                <td class="text-left align-middle">
                                    <div class="text-sm font-mono text-gray-900 break-words">{{ $machinery->serial }}</div>
                                </td>
                                <td class="text-center align-middle">
                                    @php
                                        $status = $machinery->status;
                                        $statusClass = match($status) {
                                            'Operación' => 'bg-green-100 text-green-800',
                                            'En mantenimiento' => 'bg-yellow-100 text-yellow-800',
                                            'Mantenimiento requerido' => 'bg-red-100 text-red-800',
                                            'Sin actividad' => 'bg-gray-100 text-gray-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }} break-words">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="text-sm text-gray-900 break-words">{{ $machinery->maint_freq }}</div>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ route('aprendiz.machinery.show', $machinery) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-1.5 rounded-lg hover:bg-blue-50 transition-colors"
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button"
                                                onclick="confirmEdit({{ $machinery->id }})"
                                                class="inline-flex items-center text-green-500 hover:text-green-700 p-1.5 rounded-lg transition-colors"
                                                title="Editar"
                                                data-id="{{ $machinery->id }}"
                                                data-name="{{ $machinery->name }}"
                                                data-location="{{ $machinery->location }}"
                                                data-brand="{{ $machinery->brand }}"
                                                data-model="{{ $machinery->model }}"
                                                data-serial="{{ $machinery->serial }}"
                                                data-start_func="{{ $machinery->start_func->format('Y-m-d') }}"
                                                data-maint_freq="{{ $machinery->maint_freq }}"
                                                data-image="{{ $machinery->image ? Storage::url($machinery->image) : '' }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('aprendiz.machinery.download.pdf', $machinery) }}" 
                                           class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 transition-colors"
                                           title="Descargar PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <form action="{{ route('aprendiz.machinery.destroy', $machinery) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirmDelete(event, this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 p-1.5 rounded-lg hover:bg-red-50 transition-colors"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-4 block"></i>
                                    No hay maquinaria registrada
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($machineries->count() === 0)
            <!-- Estado vacío -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-cogs text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay maquinaria registrada</h3>
                <p class="text-gray-600 mb-6">Comienza registrando tu primera maquinaria en el sistema.</p>
                <a href="{{ route('aprendiz.machinery.create') }}" 
                   class="bg-green-400 text-green-800 border border-green-500 hover:bg-green-500 px-4 py-2 rounded-lg transition-all duration-200 inline-flex items-center shadow-sm">
                    <i class="fas fa-plus mr-2"></i>
                    Registrar Primera Maquinaria
                </a>
            </div>
        @endif
    </div>
</div>
<!-- Modal de edición -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <div class="relative min-h-screen py-4 px-4 flex items-start justify-center">
        <div class="relative max-w-2xl w-full bg-white rounded-2xl shadow-xl overflow-hidden my-4">
            <div class="bg-green-100 border-b border-green-300 px-4 py-2 flex items-center justify-between">
                <h3 class="text-gray-800 font-semibold text-base"><i class="fas fa-edit mr-2 text-green-600"></i>Editar Maquinaria</h3>
                <button id="closeEditModal" class="text-gray-600 hover:text-gray-800"><i class="fas fa-times"></i></button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label for="edit_name" class="block text-xs font-medium text-gray-700 mb-0.5">Nombre de la maquinaria</label>
                        <input id="edit_name" name="name" type="text" required class="w-full px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-300 text-sm" />
                    </div>
                    <div>
                        <label for="edit_location" class="block text-xs font-medium text-gray-700 mb-0.5">Ubicación</label>
                        <input id="edit_location" name="location" type="text" required class="w-full px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-300 text-sm" />
                    </div>
                    <div>
                        <label for="edit_start_func" class="block text-xs font-medium text-gray-700 mb-0.5">Fecha de inicio de funcionamiento</label>
                        <input id="edit_start_func" name="start_func" type="date" max="{{ date('Y-m-d') }}" required class="w-full px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-300 text-sm" />
                    </div>
                    <div>
                        <label for="edit_brand" class="block text-xs font-medium text-gray-700 mb-0.5">Marca</label>
                        <input id="edit_brand" name="brand" type="text" required class="w-full px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-300 text-sm" />
                    </div>
                    <div>
                        <label for="edit_model" class="block text-xs font-medium text-gray-700 mb-0.5">Modelo</label>
                        <input id="edit_model" name="model" type="text" required class="w-full px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-300 text-sm" />
                    </div>
                    <div>
                        <label for="edit_serial" class="block text-xs font-medium text-gray-700 mb-0.5">Número de serie</label>
                        <input id="edit_serial" name="serial" type="text" required class="w-full px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-300 font-mono text-sm" />
                    </div>
                    <div class="md:col-span-2">
                        <label for="edit_maint_freq" class="block text-xs font-medium text-gray-700 mb-0.5">Frecuencia de mantenimiento</label>
                        <div class="relative">
                            <select id="edit_maint_freq" name="maint_freq" required class="w-full px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-300 appearance-none bg-white text-sm">
                                <option value="Diario">Diario</option>
                                <option value="Semanal">Semanal</option>
                                <option value="Quincenal">Quincenal</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Bimestral">Bimestral</option>
                                <option value="Trimestral">Trimestral</option>
                                <option value="Semestral">Semestral</option>
                                <option value="Anual">Anual</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Imagen de la maquinaria -->
                    <div class="md:col-span-2">
                        <label for="edit_image" class="block text-xs font-medium text-gray-700 mb-0.5">Imagen de la maquinaria</label>
                        <div id="currentImage" class="mb-2"></div>
                        <div class="relative">
                            <input type="file" name="image" id="edit_image" 
                                   class="w-full px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-300 text-sm" 
                                   accept="image/*" onchange="previewEditImage(this)">
                            <div id="editImagePreview" class="mt-2 hidden">
                                <p class="text-xs font-medium text-gray-600 mb-0.5">Nueva imagen:</p>
                                <div class="relative inline-block">
                                    <img id="editPreviewImg" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300 shadow-md" alt="Preview">
                                    <button type="button" onclick="removeEditImage()" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-500 text-xs mt-0.5 flex items-center">
                            <i class="fas fa-info-circle mr-1 text-xs"></i>
                            Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP
                        </p>
                    </div>
                </div>
                <div class="px-3 py-2 border-t border-gray-200 flex justify-end gap-2 bg-white">
                    <button type="button" id="cancelEditModal" class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">Cancelar</button>
                    <button type="submit" class="px-3 py-1.5 bg-green-400 text-green-800 border border-green-500 rounded-lg hover:bg-green-500 transition-all duration-200 text-sm">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('editModal');
    const closeBtn = document.getElementById('closeEditModal');
    const cancelBtn = document.getElementById('cancelEditModal');
    const form = document.getElementById('editForm');

    function openModal() { modal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); }
    function closeModal() { modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }

    // Función para abrir modal de edición
    function openEditModal(machineryId) {
        const btn = document.querySelector(`[data-id="${machineryId}"]`);
        if (!btn) return;
        
        const id = btn.dataset.id;
        form.action = `{{ url('aprendiz/machinery/machineries') }}/${id}`;
        document.getElementById('edit_name').value = btn.dataset.name;
        document.getElementById('edit_location').value = btn.dataset.location;
        document.getElementById('edit_brand').value = btn.dataset.brand;
        document.getElementById('edit_model').value = btn.dataset.model;
        document.getElementById('edit_serial').value = btn.dataset.serial;
        document.getElementById('edit_start_func').value = btn.dataset.start_func;
        document.getElementById('edit_maint_freq').value = btn.dataset.maint_freq;
        
        // Mostrar imagen actual si existe
        const currentImageDiv = document.getElementById('currentImage');
        const editImagePreview = document.getElementById('editImagePreview');
        if (btn.dataset.image) {
            currentImageDiv.innerHTML = `
                <p class="text-xs font-medium text-gray-600 mb-0.5">Imagen actual:</p>
                <div class="relative inline-block">
                    <img src="${btn.dataset.image}" alt="Imagen actual" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300 shadow-md">
                </div>
            `;
            editImagePreview.classList.add('hidden');
        } else {
            currentImageDiv.innerHTML = '';
            editImagePreview.classList.add('hidden');
        }
        
        openModal();
    }

    // Confirmación antes de editar
    function confirmEdit(machineryId) {
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
                openEditModal(machineryId);
            }
        });
    }

    // Función para confirmar eliminación con SweetAlert2
    function confirmDelete(event, form) {
        event.preventDefault();
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¿Quieres eliminar este registro de maquinaria?',
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
    
    // Función para previsualizar imagen en el modal de edición
    function previewEditImage(input) {
        const preview = document.getElementById('editImagePreview');
        const previewImg = document.getElementById('editPreviewImg');
        const currentImage = document.getElementById('currentImage');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
                currentImage.innerHTML = '';
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('hidden');
        }
    }
    
    function removeEditImage() {
        const input = document.getElementById('edit_image');
        const preview = document.getElementById('editImagePreview');
        
        input.value = '';
        preview.classList.add('hidden');
    }

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    // Validar número de serie en tiempo real
    document.getElementById('edit_serial').addEventListener('input', function(){
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });

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
        // Verificar que DataTable esté disponible
        if (typeof DataTable === 'undefined') {
            console.error('DataTable no está cargado. Verifica que el script de DataTables esté incluido.');
            return;
        }
        
        // Verificar que la tabla exista
        const tableElement = document.querySelector('#machineriesTable');
        if (!tableElement) {
            console.error('No se encontró la tabla con id #machineriesTable');
            return;
        }
        
        let table = new DataTable('#machineriesTable', {
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
            order: [[1, 'asc']], // Ordenar por nombre ascendente
            processing: false,
            serverSide: false,
            dom: 'rtip', // Sin length y filter, los moveremos manualmente
            initComplete: function() {
                const wrapper = this.api().table().container();
                
                // Crear controles manualmente
                const lengthContainer = document.createElement('div');
                lengthContainer.className = 'dataTables_length';
                lengthContainer.innerHTML = `
                    <label>
                        Mostrar
                        <select name="machineriesTable_length" aria-controls="machineriesTable" class="px-3 py-2 border border-gray-300 rounded-lg ml-2">
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
                        <input type="search" class="px-3 py-2 border border-gray-300 rounded-lg ml-2" placeholder="Buscar..." aria-controls="machineriesTable" style="width: 250px; outline: none; transition: none;">
                    </label>
                `;
                
                // Agregar a los contenedores
                const lengthTarget = document.getElementById('dt-length-container');
                const filterTarget = document.getElementById('dt-filter-container');
                
                if (lengthTarget) {
                    lengthTarget.appendChild(lengthContainer);
                }
                
                if (filterTarget) {
                    filterTarget.appendChild(filterContainer);
                }
                
                // Conectar eventos
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

@endsection



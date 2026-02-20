@extends('layouts.master')

@section('title', 'Gestión de Maquinaria')

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
                    <i class="fas fa-cogs waste-icon"></i>
                    Gestión de Maquinaria
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
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
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Table Header -->
        <div class="p-3 sm:p-4 md:p-6 border-b border-gray-200 bg-gray-50">
            <!-- Primera fila: Título y botones -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4 mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-cogs text-green-600 mr-2"></i>
                    Registros de Maquinaria
                </h2>
                <div class="flex items-center space-x-2 sm:space-x-4 w-full sm:w-auto">
                    @if($machineries->count() > 0)
                        <button type="button" id="btn-download-all-pdf" class="bg-red-500 text-white border border-red-600 hover:bg-red-600 px-3 sm:px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm text-sm sm:text-base" title="Descargar PDF de los registros visibles (filtrados)">
                            <i class="fas fa-file-pdf"></i>
                            <span class="hidden sm:inline ml-2">PDF</span>
                        </button>
                    @endif
                    <a href="{{ route('admin.machinery.create') }}" class="bg-green-400 text-green-800 border border-green-500 hover:bg-green-500 px-3 sm:px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                        <i class="fas fa-plus mr-2"></i>
                        <span class="hidden sm:inline">Nuevo Registro</span>
                        <span class="sm:hidden">Nuevo</span>
                    </a>
                </div>
            </div>
        </div>

        @if($machineries->count() > 0)
            <!-- Vista móvil: tarjetas (solo en pantallas pequeñas; en laptop/PC se muestra la tabla) -->
            <div class="block md:hidden p-3 sm:p-4 space-y-4">
                @foreach($machineries as $machinery)
                    @php
                        $status = $machinery->status;
                        $statusClass = match($status) {
                            'Operación' => 'waste-badge waste-badge-success',
                            'En mantenimiento' => 'waste-badge waste-badge-danger',
                            'Mantenimiento requerido' => 'waste-badge waste-badge-danger',
                            'Sin actividad' => 'bg-gray-100 text-gray-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                    @endphp
                    <div class="waste-mobile-card bg-gray-50 border border-gray-200 rounded-xl p-4 shadow-sm" data-id="{{ $machinery->id }}">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                @if($machinery->image)
                                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 cursor-pointer" onclick="openImageModal('{{ asset('storage/'.$machinery->image) }}')">
                                        <img src="{{ asset('storage/'.$machinery->image) }}" alt="{{ $machinery->name }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center" style="display: none;"><i class="fas fa-cogs text-gray-400"></i></div>
                                    </div>
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center"><i class="fas fa-cogs text-green-600 text-xl"></i></div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">{{ $machinery->name }}</h3>
                                <p class="text-sm text-gray-600 truncate">{{ $machinery->location }}</p>
                                <p class="text-xs text-gray-500">{{ $machinery->brand }} {{ $machinery->model }} · {{ $machinery->serial }}</p>
                                <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">{{ $status === 'En mantenimiento' ? 'Mantenimiento' : $status }}</span>
                            </div>
                        </div>
                        <div class="waste-mobile-card-actions mt-4 pt-3 border-t border-gray-200">
                            <a href="{{ route('admin.machinery.show', $machinery) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg flex-shrink-0" title="Ver"><i class="fas fa-eye"></i></a>
                            <button type="button" onclick="confirmEdit({{ $machinery->id }})" class="p-2 text-green-600 hover:bg-green-50 rounded-lg flex-shrink-0" title="Editar"
                                data-id="{{ $machinery->id }}" data-name="{{ $machinery->name }}" data-location="{{ $machinery->location }}" data-brand="{{ $machinery->brand }}" data-model="{{ $machinery->model }}" data-serial="{{ $machinery->serial }}" data-start_func="{{ $machinery->start_func->format('Y-m-d') }}" data-maint_freq="{{ $machinery->maint_freq }}" data-image="{{ $machinery->image ? asset('storage/'.$machinery->image) : '' }}"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.machinery.destroy', $machinery) }}" method="POST" class="inline flex-shrink-0" onsubmit="return confirmDelete(event, this)">@csrf @method('DELETE')<button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg" title="Eliminar"><i class="fas fa-trash"></i></button></form>
                            <a href="{{ route('admin.machinery.download.pdf', $machinery) }}" class="p-2 text-red-700 hover:bg-red-50 rounded-lg flex-shrink-0" title="PDF"><i class="fas fa-file-pdf"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tabla de maquinaria (escritorio: md 768px en adelante) -->
            <div class="hidden md:block overflow-x-auto -mx-3 sm:mx-0">
                <div id="machineriesTable_wrapper" class="p-3 sm:p-4 md:p-6 pr-6 sm:pr-12">
                    <div style="width: 100%; overflow: hidden; margin-bottom: 1rem;">
                        <div id="dt-length-container" style="float: left;"></div>
                        <div id="dt-filter-container" style="float: right;"></div>
                    </div>
                    <table id="machineriesTable" class="waste-table machineries-registros-table min-w-[900px]">
                        <thead>
                            <tr>
                                <th style="width: 56px;">Imagen</th>
                                <th style="width: 140px;">Maquinaria</th>
                                <th style="width: 100px;">Ubicación</th>
                                <th style="width: 100px;">Marca/Modelo</th>
                                <th style="width: 110px;">Serie</th>
                                <th style="width: 115px;">Estado</th>
                                <th style="width: 95px;">Mantenimiento</th>
                                <th style="width: 115px;">Cronómetro</th>
                                <th class="text-center" style="width: 120px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($machineries as $machinery)
                                <tr data-id="{{ $machinery->id }}">
                                    <td class="text-center align-middle py-2">
                                        @if($machinery->image)
                                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0 mx-auto cursor-pointer hover:opacity-80 transition-opacity">
                                                <img src="{{ asset('storage/'.$machinery->image) }}" 
                                                     alt="{{ $machinery->name }}" 
                                                     class="w-full h-full object-cover"
                                                     onclick="openImageModal('{{ asset('storage/'.$machinery->image) }}')"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center" style="display: none;">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0 mx-auto">
                                                <i class="fas fa-cogs text-green-600 text-sm"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-left align-middle py-2">
                                        <div class="max-w-[140px]">
                                            <div class="text-sm font-medium text-gray-900 truncate" title="{{ $machinery->name }}">{{ $machinery->name }}</div>
                                            <div class="text-xs text-gray-500">Desde {{ $machinery->start_func->format('d/m/Y') }}</div>
                                        </div>
                                    </td>
                                    <td class="text-left align-middle py-2">
                                        <div class="text-sm text-gray-900 truncate max-w-[100px]" title="{{ $machinery->location }}">{{ $machinery->location }}</div>
                                    </td>
                                    <td class="text-left align-middle py-2">
                                        <div class="text-sm text-gray-900 truncate max-w-[100px]" title="{{ $machinery->brand }} {{ $machinery->model }}">{{ $machinery->brand }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-[100px]">{{ $machinery->model }}</div>
                                    </td>
                                    <td class="text-left align-middle py-2">
                                        <div class="text-sm font-mono text-gray-900 truncate max-w-[110px]" title="{{ $machinery->serial }}">{{ $machinery->serial }}</div>
                                    </td>
                                    <td class="text-center align-middle py-2">
                                        @php
                                            $status = $machinery->status;
                                            $statusClass = match($status) {
                                                'Operación' => 'waste-badge waste-badge-success',
                                                'En mantenimiento' => 'waste-badge waste-badge-danger',
                                                'Mantenimiento requerido' => 'waste-badge waste-badge-danger',
                                                'Sin actividad' => 'bg-gray-100 text-gray-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $status === 'En mantenimiento' ? 'Mantenimiento' : $status }}
                                        </span>
                                    </td>
                                    <td class="text-center align-middle py-2">
                                        <div class="text-sm text-gray-900">{{ $machinery->maint_freq }}</div>
                                    </td>
                                    <td class="text-center align-middle py-2">
                                        @if($machinery->status === 'En mantenimiento')
                                            <span class="text-sm font-semibold text-amber-600">Pausado</span>
                                        @else
                                            @php $nextDue = $machinery->getNextMaintenanceDueDateTime(); @endphp
                                            <span class="machinery-countdown text-sm font-mono font-semibold text-gray-800" data-next-due="{{ $nextDue?->toIso8601String() ?? '' }}">--</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle py-2" style="white-space: nowrap;">
                                        <div class="flex flex-nowrap items-center justify-center gap-0.5">
                                            <a href="{{ route('admin.machinery.show', $machinery) }}" 
                                               class="inline-flex items-center justify-center text-blue-400 hover:text-blue-500 w-7 h-7 rounded hover:bg-blue-50 transition-colors shrink-0"
                                               title="Ver detalles">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <button type="button"
                                                    onclick="confirmEdit({{ $machinery->id }})"
                                                    class="inline-flex items-center justify-center text-green-500 hover:text-green-700 w-7 h-7 rounded hover:bg-green-50 transition-colors shrink-0"
                                                    title="Editar"
                                                    data-id="{{ $machinery->id }}"
                                                    data-name="{{ $machinery->name }}"
                                                    data-location="{{ $machinery->location }}"
                                                    data-brand="{{ $machinery->brand }}"
                                                    data-model="{{ $machinery->model }}"
                                                    data-serial="{{ $machinery->serial }}"
                                                    data-start_func="{{ $machinery->start_func->format('Y-m-d') }}"
                                                    data-maint_freq="{{ $machinery->maint_freq }}"
                                                    data-image="{{ $machinery->image ? asset('storage/'.$machinery->image) : '' }}">
                                                <i class="fas fa-edit text-sm"></i>
                                            </button>
                                            <form action="{{ route('admin.machinery.destroy', $machinery) }}" 
                                                  method="POST" 
                                                  class="inline-flex shrink-0"
                                                  onsubmit="return confirmDelete(event, this)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center text-red-500 hover:text-red-700 w-7 h-7 rounded hover:bg-red-50 transition-colors"
                                                        title="Eliminar">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.machinery.download.pdf', $machinery) }}" 
                                               class="inline-flex items-center justify-center text-red-800 hover:text-red-900 w-7 h-7 rounded hover:bg-red-50 transition-colors shrink-0"
                                               title="Descargar PDF">
                                                <i class="fas fa-file-pdf text-sm"></i>
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
                    <i class="fas fa-cogs text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay maquinaria registrada</h3>
                <p class="text-gray-600">Comienza registrando tu primera maquinaria en el sistema.</p>
            </div>
        @endif
    </div>
</div>
<!-- Modal de edición -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header relative">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-edit waste-icon"></i>
                    Editar Maquinaria
                </h3>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    <span id="editUserInfo">{{ Auth::user()->name }} - Maquinaria #<span id="editMachineryId"></span></span>
                </p>
            </div>
            <button id="closeEditModal" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nombre -->
                    <div class="waste-form-group">
                        <label for="edit_name" class="waste-form-label">Nombre de la maquinaria *</label>
                        <input id="edit_name" name="name" type="text" required class="waste-form-input" />
                    </div>

                    <!-- Ubicación -->
                    <div class="waste-form-group">
                        <label for="edit_location" class="waste-form-label">Ubicación *</label>
                        <input id="edit_location" name="location" type="text" required class="waste-form-input" />
                    </div>

                    <!-- Fecha de inicio -->
                    <div class="waste-form-group">
                        <label for="edit_start_func" class="waste-form-label">Fecha de inicio de funcionamiento *</label>
                        <input id="edit_start_func" name="start_func" type="date" max="{{ date('Y-m-d') }}" required class="waste-form-input" />
                    </div>

                    <!-- Marca -->
                    <div class="waste-form-group">
                        <label for="edit_brand" class="waste-form-label">Marca *</label>
                        <input id="edit_brand" name="brand" type="text" required class="waste-form-input" />
                    </div>

                    <!-- Modelo -->
                    <div class="waste-form-group">
                        <label for="edit_model" class="waste-form-label">Modelo *</label>
                        <input id="edit_model" name="model" type="text" required class="waste-form-input" />
                    </div>

                    <!-- Número de serie -->
                    <div class="waste-form-group">
                        <label for="edit_serial" class="waste-form-label">Número de serie *</label>
                        <input id="edit_serial" name="serial" type="text" required class="waste-form-input font-mono" />
                    </div>

                    <!-- Frecuencia de mantenimiento -->
                    <div class="waste-form-group md:col-span-2">
                        <label for="edit_maint_freq" class="waste-form-label">Frecuencia de mantenimiento *</label>
                        <div class="relative">
                            <select id="edit_maint_freq" name="maint_freq" required class="waste-form-select">
                                <option value="">Seleccionar frecuencia...</option>
                                <option value="Diario">Diario</option>
                                <option value="Semanal">Semanal</option>
                                <option value="Quincenal">Quincenal</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Bimestral">Bimestral</option>
                                <option value="Trimestral">Trimestral</option>
                                <option value="Semestral">Semestral</option>
                                <option value="Anual">Anual</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Imagen de la maquinaria -->
                    <div class="waste-form-group md:col-span-2">
                        <label for="edit_image" class="waste-form-label">Imagen de la maquinaria</label>
                        <div id="currentImage" class="mb-4"></div>
                        <div class="relative">
                            <input type="file" name="image" id="edit_image" 
                                   class="waste-form-input" 
                                   accept="image/*" onchange="previewEditImage(this)">
                            <div id="editImagePreview" class="mt-4 hidden">
                                <p class="text-sm font-medium text-gray-600 mb-2">Nueva imagen:</p>
                                <div class="relative inline-block">
                                    <img id="editPreviewImg" class="w-32 h-32 object-cover rounded-lg border border-gray-200" alt="Preview">
                                    <button type="button" onclick="removeEditImage()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP
                        </p>
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
    const modal = document.getElementById('editModal');
    const closeBtn = document.getElementById('closeEditModal');
    const cancelBtn = document.getElementById('cancelEditModal');
    const form = document.getElementById('editForm');

    function openModal() { 
        modal.classList.remove('hidden'); 
        document.body.style.overflow = 'hidden'; 
    }
    function closeModal() { 
        modal.classList.add('hidden'); 
        document.body.style.overflow = 'auto'; 
    }

    // Función para abrir modal de edición
    function openEditModal(machineryId) {
        // Selecciona explícitamente el botón de editar para evitar
        // tomar el <tr data-id="...">, lo que provocaba valores "undefined"
        const btn = document.querySelector(`button[data-id="${machineryId}"]`);
        if (!btn) return;
        
        const id = btn.dataset.id;
        form.action = `{{ url('admin/machinery/machineries') }}/${id}`;
        
        // Actualizar ID en el header
        document.getElementById('editMachineryId').textContent = id.toString().padStart(3, '0');
        
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
                <label class="block text-sm font-medium text-gray-600 mb-2">Imagen actual:</label>
                <div class="relative inline-block">
                    <img src="${btn.dataset.image}" alt="Imagen actual" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
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
    // Cerrar solo cuando se hace clic en el fondo oscuro, no dentro del contenido
    modal.addEventListener('click', (e) => { 
        if (e.target === modal) {
            closeModal(); 
        }
    });
    
    // Cerrar con Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

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
        
        // Verificar que la tabla exista y que haya registros
        const tableElement = document.querySelector('#machineriesTable');
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
        
        window.machineriesDataTable = new DataTable('#machineriesTable', {
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
                        window.machineriesDataTable.page.len(parseInt(this.value)).draw();
                    });
                }
                
                if (searchInput) {
                    searchInput.addEventListener('keyup', function() {
                        window.machineriesDataTable.search(this.value).draw();
                    });
                }
            }
        });

        document.getElementById('btn-download-all-pdf')?.addEventListener('click', function() {
            let url = '{{ route("admin.machinery.download.all-pdf") }}';
            if (window.machineriesDataTable) {
                const ids = [];
                window.machineriesDataTable.rows({ search: 'applied' }).every(function() {
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

        // Actualizar cronómetros de maquinaria cada segundo
        function formatCountdown(totalSeconds) {
            if (totalSeconds == null || totalSeconds < 0) return '--';
            if (totalSeconds <= 0) return '0d 0h 0m 0s';
            const d = Math.floor(totalSeconds / 86400);
            const h = Math.floor((totalSeconds % 86400) / 3600);
            const m = Math.floor((totalSeconds % 3600) / 60);
            const s = totalSeconds % 60;
            return d + 'd ' + h + 'h ' + m + 'm ' + s + 's';
        }
        function updateMachineryCountdowns() {
            document.querySelectorAll('.machinery-countdown').forEach(function(el) {
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
        updateMachineryCountdowns();
        setInterval(updateMachineryCountdowns, 1000);
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

/* Tabla Registros de Maquinaria más compacta */
.machineries-registros-table {
    table-layout: fixed;
    width: 100%;
    max-width: 100%;
}
.machineries-registros-table th,
.machineries-registros-table td {
    padding: 0.5rem 0.4rem;
    vertical-align: middle;
}
.machineries-registros-table .dataTables_wrapper {
    overflow-x: auto;
}
</style>

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
        const imageModal = document.getElementById('imageModal');
        if (imageModal && !imageModal.classList.contains('hidden')) {
            closeImageModal();
        }
    }
});
</script>

@endsection



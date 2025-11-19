@extends('layouts.master')

@section('title', 'Detalles de Maquinaria')

@section('content')
@vite(['resources/css/waste.css'])

@php
    use Illuminate\Support\Facades\Storage;
@endphp

<!-- Modal para ver detalles de maquinaria -->
<div class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[95vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="waste-title text-xl">
                        <i class="fas fa-eye waste-icon"></i>
                        Detalles de Maquinaria
                    </h3>
                    <p class="waste-subtitle">
                        <i class="fas fa-user-shield text-green-400 mr-2"></i>
                        {{ Auth::user()->name }} - {{ $machinery->name }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>
                    <button onclick="window.location.href='{{ route('admin.machinery.index') }}'" 
                            class="mt-2 text-gray-600 hover:text-gray-800 text-xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

<!-- Modal de edición (reutilizado) -->
<div id="editModal" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <div class="relative max-w-3xl w-full mx-auto mt-20 bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-green-100 border-b border-green-300 px-6 py-4 flex items-center justify-between">
            <h3 class="text-gray-800 font-semibold"><i class="fas fa-edit mr-2 text-green-600"></i>Editar registro de maquinaria</h3>
            <button id="closeEditModal" class="text-gray-600 hover:text-gray-800"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm" method="POST" action="{{ route('admin.machinery.update', $machinery) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Nombre de la maquinaria</label>
                    <input id="edit_name" name="name" type="text" value="{{ $machinery->name }}" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300" />
                </div>
                <div>
                    <label for="edit_location" class="block text-sm font-medium text-gray-700 mb-2">Ubicación</label>
                    <input id="edit_location" name="location" type="text" value="{{ $machinery->location }}" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300" />
                </div>
                <div>
                    <label for="edit_start_func" class="block text-sm font-medium text-gray-700 mb-2">Fecha de inicio de funcionamiento</label>
                    <input id="edit_start_func" name="start_func" type="date" max="{{ date('Y-m-d') }}" value="{{ $machinery->start_func->format('Y-m-d') }}" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300" />
                </div>
                <div>
                    <label for="edit_brand" class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                    <input id="edit_brand" name="brand" type="text" value="{{ $machinery->brand }}" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300" />
                </div>
                <div>
                    <label for="edit_model" class="block text-sm font-medium text-gray-700 mb-2">Modelo</label>
                    <input id="edit_model" name="model" type="text" value="{{ $machinery->model }}" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300" />
                </div>
                <div>
                    <label for="edit_serial" class="block text-sm font-medium text-gray-700 mb-2">Número de serie</label>
                    <input id="edit_serial" name="serial" type="text" value="{{ $machinery->serial }}" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 font-mono" />
                </div>
                <div class="md:col-span-2">
                    <label for="edit_maint_freq" class="block text-sm font-medium text-gray-700 mb-2">Frecuencia de mantenimiento</label>
                    <div class="relative">
                        <select id="edit_maint_freq" name="maint_freq" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 appearance-none bg-white">
                            @php $freq = $machinery->maint_freq; @endphp
                            <option value="Diario" {{ $freq=='Diario'?'selected':'' }}>Diario</option>
                            <option value="Semanal" {{ $freq=='Semanal'?'selected':'' }}>Semanal</option>
                            <option value="Quincenal" {{ $freq=='Quincenal'?'selected':'' }}>Quincenal</option>
                            <option value="Mensual" {{ $freq=='Mensual'?'selected':'' }}>Mensual</option>
                            <option value="Bimestral" {{ $freq=='Bimestral'?'selected':'' }}>Bimestral</option>
                            <option value="Trimestral" {{ $freq=='Trimestral'?'selected':'' }}>Trimestral</option>
                            <option value="Semestral" {{ $freq=='Semestral'?'selected':'' }}>Semestral</option>
                            <option value="Anual" {{ $freq=='Anual'?'selected':'' }}>Anual</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label for="edit_image" class="block text-sm font-medium text-gray-700 mb-2">Imagen de la maquinaria</label>
                    @if($machinery->image)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Imagen actual:</p>
                            <div class="relative inline-block">
                                <img src="{{ Storage::url($machinery->image) }}" alt="Imagen actual" class="w-32 h-32 object-cover rounded-xl border-2 border-gray-300 shadow-lg">
                            </div>
                        </div>
                    @endif
                    <div class="relative">
                        <input type="file" name="image" id="edit_image" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300" 
                               accept="image/*" onchange="previewEditImage(this)">
                        <div id="editImagePreview" class="mt-4 hidden">
                            <div class="relative inline-block">
                                <img id="editPreviewImg" class="w-32 h-32 object-cover rounded-xl border-2 border-gray-300 shadow-lg" alt="Preview">
                                <button type="button" onclick="removeEditImage()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm mt-1 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" id="cancelEditModal" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-green-400 text-green-800 border border-green-500 rounded-lg hover:bg-green-500 transition-all duration-200">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('editModal');
    const openBtn = document.getElementById('openEditModalShow');
    const closeBtn = document.getElementById('closeEditModal');
    const cancelBtn = document.getElementById('cancelEditModal');
    function openModal(){ modal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); }
    function closeModal(){ modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
    // Exponer función global para fallback de onclick
    function openEditModal(){ openModal(); }
    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e)=>{ if(e.target===modal) closeModal(); });
    document.getElementById('edit_serial').addEventListener('input', function(){ this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, ''); });
    
    // Función para previsualizar imagen en el modal de edición
    function previewEditImage(input) {
        const preview = document.getElementById('editImagePreview');
        const previewImg = document.getElementById('editPreviewImg');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
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
</script>
        <!-- Modal Body -->
        <div class="p-6">
            <div class="waste-container animate-fade-in-up animate-delay-1">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-green-400 mr-2"></i>
                    Información del Registro
                </h2>
                
                <!-- Image Section -->
                @if($machinery->image)
                    <div class="mb-8 text-center">
                        <img src="{{ Storage::url($machinery->image) }}" 
                             alt="{{ $machinery->name }}" 
                             class="max-w-full h-64 object-cover rounded-lg shadow-md mx-auto">
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Nombre</label>
                        <div class="waste-form-input bg-gray-50">{{ $machinery->name }}</div>
                    </div>

                    <!-- Ubicación -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Ubicación</label>
                        <div class="waste-form-input bg-gray-50">{{ $machinery->location }}</div>
                    </div>

                    <!-- Fecha de Inicio -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha de Inicio</label>
                        <div class="waste-form-input bg-gray-50">{{ $machinery->start_func->format('d/m/Y') }}</div>
                    </div>

                    <!-- Frecuencia de Mantenimiento -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Frecuencia de Mantenimiento</label>
                        <div class="waste-form-input bg-gray-50">
                            <span class="waste-badge waste-badge-info">
                                {{ $machinery->maint_freq }}
                            </span>
                        </div>
                    </div>

                    <!-- Marca -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Marca</label>
                        <div class="waste-form-input bg-gray-50 font-semibold">{{ $machinery->brand }}</div>
                    </div>

                    <!-- Modelo -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Modelo</label>
                        <div class="waste-form-input bg-gray-50">{{ $machinery->model }}</div>
                    </div>

                    <!-- Número de Serie -->
                    <div class="waste-form-group md:col-span-2">
                        <label class="waste-form-label">Número de Serie</label>
                        <div class="waste-form-input bg-gray-50 font-mono font-semibold">{{ $machinery->serial }}</div>
                    </div>

                    <!-- Estado -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Estado</label>
                        <div class="waste-form-input bg-gray-50">
                            @php
                                $status = $machinery->status;
                                $statusBadge = match($status) {
                                    'Operación' => 'waste-badge-success',
                                    'En mantenimiento' => 'waste-badge-warning',
                                    'Mantenimiento requerido' => 'waste-badge-danger',
                                    'Sin actividad' => 'waste-badge-secondary',
                                    default => 'waste-badge-secondary'
                                };
                            @endphp
                            <span class="waste-badge {{ $statusBadge }}">
                                {{ $status }}
                            </span>
                        </div>
                    </div>

                    <!-- Creado En -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Creado En</label>
                        <div class="waste-form-input bg-gray-50">{{ $machinery->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button onclick="window.location.href='{{ route('admin.machinery.index') }}'" 
                    class="waste-btn">
                <i class="fas fa-times mr-2"></i>
                Cerrar
            </button>
        </div>
    </div>
</div>
@endsection



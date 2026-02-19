@extends('layouts.master')

@section('title', 'Editar Maquinaria')

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
                        <i class="fas fa-edit waste-icon"></i>
                        Editar Maquinaria
                    </h1>
                    <p class="waste-subtitle">
                        <i class="fas fa-user-shield text-green-400 mr-2"></i>
                        {{ Auth::user()->name }} - Maquinaria #{{ str_pad($machinery->id, 3, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-4xl mx-auto">
        <div class="waste-form animate-fade-in-up animate-delay-1">
            <form action="{{ route('admin.machinery.update', $machinery) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Primera fila -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Nombre -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Nombre de la maquinaria *</label>
                        <input type="text" name="name" id="name" maxlength="150" required
                               value="{{ old('name', $machinery->name) }}"
                               placeholder="Nombre"
                               class="waste-form-input @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ubicación -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Ubicación *</label>
                        <input type="text" name="location" id="location" maxlength="150" required
                               value="{{ old('location', $machinery->location) }}"
                               placeholder="Ej: Galpón A - Sector 1"
                               class="waste-form-input @error('location') border-red-500 @enderror">
                        @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha de inicio de funcionamiento *</label>
                        <input type="date" name="start_func" id="start_func" required
                               value="{{ old('start_func', $machinery->start_func->format('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}"
                               class="waste-form-input @error('start_func') border-red-500 @enderror">
                        @error('start_func')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Segunda fila -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Marca -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Marca *</label>
                        <input type="text" name="brand" id="brand" maxlength="100" required
                               value="{{ old('brand', $machinery->brand) }}"
                               placeholder="Ej: jcb"
                               class="waste-form-input @error('brand') border-red-500 @enderror">
                        @error('brand')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Modelo -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Modelo *</label>
                        <input type="text" name="model" id="model" maxlength="100" required
                               value="{{ old('model', $machinery->model) }}"
                               placeholder="Ej: 5075E"
                               class="waste-form-input @error('model') border-red-500 @enderror">
                        @error('model')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tercera fila -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Número de serie -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Número de serie *</label>
                        <input type="text" name="serial" id="serial" maxlength="100" required
                               value="{{ old('serial', $machinery->serial) }}"
                               placeholder="Ej: JD5075E2023001"
                               class="waste-form-input font-mono @error('serial') border-red-500 @enderror">
                        @error('serial')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Frecuencia de mantenimiento -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Frecuencia de mantenimiento *</label>
                        <div>
                            <select name="maint_freq" id="maint_freq" required
                                    class="waste-form-select @error('maint_freq') border-red-500 @enderror">
                                <option value="">Seleccionar frecuencia...</option>
                                <option value="Diario" {{ old('maint_freq', $machinery->maint_freq) == 'Diario' ? 'selected' : '' }}>Diario</option>
                                <option value="Semanal" {{ old('maint_freq', $machinery->maint_freq) == 'Semanal' ? 'selected' : '' }}>Semanal</option>
                                <option value="Quincenal" {{ old('maint_freq', $machinery->maint_freq) == 'Quincenal' ? 'selected' : '' }}>Quincenal</option>
                                <option value="Mensual" {{ old('maint_freq', $machinery->maint_freq) == 'Mensual' ? 'selected' : '' }}>Mensual</option>
                                <option value="Bimestral" {{ old('maint_freq', $machinery->maint_freq) == 'Bimestral' ? 'selected' : '' }}>Bimestral</option>
                                <option value="Trimestral" {{ old('maint_freq', $machinery->maint_freq) == 'Trimestral' ? 'selected' : '' }}>Trimestral</option>
                                <option value="Semestral" {{ old('maint_freq', $machinery->maint_freq) == 'Semestral' ? 'selected' : '' }}>Semestral</option>
                                <option value="Anual" {{ old('maint_freq', $machinery->maint_freq) == 'Anual' ? 'selected' : '' }}>Anual</option>
                            </select>
                        </div>
                        @error('maint_freq')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <!-- Cronómetro hasta próximo mantenimiento -->
                        <div id="countdownEditBlock" class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-xl">
                            <p class="text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-clock text-soft-green-500 mr-2"></i>
                                Próximo mantenimiento (cronómetro)
                            </p>
                            <div id="countdownEditDisplay" class="text-2xl font-mono font-bold text-gray-800">--</div>
                        </div>
                    </div>
                </div>

                <!-- Cuarta fila: Imagen -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Imagen (Obligatoria)</label>
                    
                    <!-- Imagen actual -->
                    @if($machinery->image)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Imagen actual:</label>
                            <div class="relative">
                                <img src="{{ Storage::url($machinery->image) }}" 
                                     alt="Imagen actual de {{ $machinery->name }}" 
                                     class="w-full h-48 object-cover rounded-lg border border-gray-200">
                            </div>
                        </div>
                    @endif
                    
                    <div class="relative">
                        <input type="file" name="image" id="imageInput" 
                               class="waste-form-input @error('image') border-red-500 @enderror" 
                               accept="image/*" onchange="previewImage(this)">
                        <div id="imagePreview" class="mt-4 hidden">
                            <div class="relative inline-block">
                                <p class="text-sm text-gray-600 mb-2">Nueva imagen:</p>
                                <img id="previewImg" class="w-full h-48 object-cover rounded-lg border border-gray-200" alt="Preview">
                                <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP
                    </p>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.machinery.index') }}" 
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
</div>

<script>
    // Validación en tiempo real del número de serie
    document.addEventListener('DOMContentLoaded', function() {
        const serialInput = document.getElementById('serial');
        
        if (serialInput) {
            serialInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            });
        }
        
        // Función para previsualizar imagen (debe ser global para onchange)
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            
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
        
        function removeImage() {
            const input = document.getElementById('imageInput');
            const preview = document.getElementById('imagePreview');
            
            input.value = '';
            preview.classList.add('hidden');
        }
        
        // Exponer funciones globalmente
        window.previewImage = previewImage;
        window.removeImage = removeImage;

        // Cronómetro hasta próximo mantenimiento
        const countdownEl = document.getElementById('countdownEditDisplay');
        const machineryId = {{ $machinery->id }};
        const nextDueUrl = '{{ route("admin.machinery.maintenance.next-due", ["machinery_id" => $machinery->id]) }}';
        if (countdownEl && machineryId) {
            let secondsLeft = null;
            let tick = null;
            function formatCountdown(totalSeconds) {
                if (totalSeconds == null || totalSeconds < 0) return '--';
                if (totalSeconds <= 0) return '0d 0h 0m 0s';
                const d = Math.floor(totalSeconds / 86400);
                const h = Math.floor((totalSeconds % 86400) / 3600);
                const m = Math.floor((totalSeconds % 3600) / 60);
                const s = totalSeconds % 60;
                return d + 'd ' + h + 'h ' + m + 'm ' + s + 's';
            }
            function updateDisplay() {
                if (secondsLeft == null) return;
                countdownEl.textContent = formatCountdown(secondsLeft);
                if (secondsLeft <= 0) {
                    if (tick) clearInterval(tick);
                    tick = null;
                }
            }
            fetch(nextDueUrl)
                .then(r => r.json())
                .then(data => {
                    if (data.seconds_remaining != null) {
                        secondsLeft = data.seconds_remaining;
                        updateDisplay();
                        if (secondsLeft > 0) {
                            tick = setInterval(function() {
                                secondsLeft--;
                                updateDisplay();
                            }, 1000);
                        }
                    } else {
                        countdownEl.textContent = (data.paused ? 'Pausado' : '--');
                    }
                })
                .catch(() => { countdownEl.textContent = '--'; });
        }
        
        // Confirmación antes de enviar el formulario
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const requiredFields = ['name', 'location', 'brand', 'model', 'serial', 'start_func', 'maint_freq'];
                const emptyFields = [];
                
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        emptyFields.push(field);
                    }
                });
                
                if (emptyFields.length > 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Campos incompletos',
                        text: 'Por favor completa todos los campos requeridos antes de continuar.',
                        icon: 'warning',
                        confirmButtonColor: '#16a34a',
                        confirmButtonText: 'Entendido',
                        customClass: {
                            popup: 'rounded-lg',
                            title: 'text-lg font-semibold',
                            content: 'text-sm text-gray-600',
                            confirmButton: 'px-4 py-2 rounded-lg font-medium'
                        }
                    });
                    return false;
                }
                
                e.preventDefault();
                Swal.fire({
                    title: '¿Confirmar actualización?',
                    text: '¿Estás seguro de que deseas actualizar esta maquinaria con los cambios realizados?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        popup: 'rounded-lg',
                        title: 'text-lg font-semibold',
                        content: 'text-sm text-gray-600',
                        confirmButton: 'px-4 py-2 rounded-lg font-medium',
                        cancelButton: 'px-4 py-2 rounded-lg font-medium'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
                
                return false;
            });
        }
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

</script>
@endsection



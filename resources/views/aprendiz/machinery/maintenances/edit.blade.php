@extends('layouts.masteraprendiz')

@section('content')
@vite(['resources/css/waste.css'])

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-6 py-8">
    <!-- Header con colores suaves como la vista de lista -->
    <div class="waste-header animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-edit text-green-500 mr-3"></i>
                Editar Actividad
            </h1>
            <p class="waste-subtitle">
                <i class="fas fa-user-shield text-green-400 mr-2"></i>
                {{ Auth::user()->name }} - Modificar registro
            </p>
        </div>
    </div>

    <!-- Formulario con estilo de tarjeta como la vista de lista -->
    <div class="waste-card animate-fade-in-up animate-delay-1">
        <!-- Header del formulario -->
        <div class="waste-card-header">
            <div class="flex items-center space-x-3">
                <div class="waste-card-icon text-green-600">
                    <i class="fas fa-wrench"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Información de la Actividad</h2>
            </div>
        </div>

        <!-- Cuerpo del formulario -->
        <div class="p-8">
            <form action="{{ route('aprendiz.machinery.maintenance.update', $maintenance) }}" method="POST" class="space-y-8" id="maintenanceForm">
                @csrf
                @method('PUT')
                
                <!-- Primera fila -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Fecha -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-calendar-alt text-soft-green-500 mr-2"></i>
                            Fecha *
                        </label>
                        <input type="date" name="date" required
                               value="{{ old('date', $maintenance->date->format('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Maquinaria -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-cogs text-soft-green-500 mr-2"></i>
                            Maquinaria *
                        </label>
                        <div class="relative">
                            <select name="machinery_id" required
                                    class="w-full px-4 py-4 pr-10 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('machinery_id') border-red-500 @enderror appearance-none bg-white">
                                <option value="">Seleccionar maquinaria</option>
                                @foreach($machineries as $machinery)
                                    <option value="{{ $machinery->id }}" {{ old('machinery_id', $maintenance->machinery_id) == $machinery->id ? 'selected' : '' }}>
                                        {{ $machinery->name }} - {{ $machinery->brand }} {{ $machinery->model }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('machinery_id')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-tasks text-soft-green-500 mr-2"></i>
                            Tipo de Registro *
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border-2 border-gray-300 rounded-xl cursor-pointer hover:bg-green-50 transition-all duration-200 @error('type') border-red-500 @enderror">
                                <input type="radio" name="type" value="M" id="type_maintenance" {{ old('type', $maintenance->type) == 'M' ? 'checked' : '' }}
                                       class="sr-only peer" required>
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-green-700">M: Mantenimiento</span>
                            </label>
                            <label class="flex items-center p-3 border-2 border-gray-300 rounded-xl cursor-pointer hover:bg-green-50 transition-all duration-200 @error('type') border-red-500 @enderror">
                                <input type="radio" name="type" value="O" id="type_operation" {{ old('type', $maintenance->type) == 'O' ? 'checked' : '' }}
                                       class="sr-only peer" required>
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-green-700">O: Operación</span>
                            </label>
                        </div>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Segunda fila -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Responsable -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-user text-soft-green-500 mr-2"></i>
                            Responsable *
                        </label>
                        <input type="text" name="responsible" maxlength="150" required
                               value="{{ old('responsible', $maintenance->responsible) }}"
                               placeholder="Nombre del responsable"
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('responsible') border-red-500 @enderror">
                        @error('responsible')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Campo de fecha de fin (solo para mantenimiento) - Debajo de Tipo de Registro -->
                    <div id="end_date_container" class="hidden space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-calendar-check text-soft-green-500 mr-2"></i>
                            Fecha de Fin de Mantenimiento
                        </label>
                        <input type="date" name="end_date" id="end_date"
                               value="{{ old('end_date', $maintenance->end_date ? $maintenance->end_date->format('Y-m-d') : '') }}"
                               min="{{ old('date', $maintenance->date->format('Y-m-d')) }}"
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Campo opcional. Si no se especifica, la maquinaria quedará "En mantenimiento"
                        </p>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="space-y-2">
                    <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                        <i class="fas fa-sticky-note text-soft-green-500 mr-2"></i>
                        Descripción del Trabajo Realizado *
                    </label>
                    <textarea name="description" rows="4" maxlength="1000" required
                              placeholder="Describe detalladamente el mantenimiento u operación realizada..."
                              class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 resize-none @error('description') border-red-500 @enderror">{{ old('description', $maintenance->description) }}</textarea>
                    <div class="flex justify-between">
                        @error('description')
                            <p class="text-red-500 text-sm flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @else
                            <p class="text-gray-500 text-sm flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Máximo 1000 caracteres
                            </p>
                        @enderror
                        <p class="text-gray-500 text-sm" id="char-count">0/1000</p>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-300">
                    <a href="{{ route('aprendiz.machinery.maintenance.index') }}" 
                       class="flex-1 sm:flex-none px-8 py-4 bg-soft-gray-100 text-soft-gray-700 rounded-xl hover:bg-soft-gray-200 transition-all duration-300 text-center font-semibold flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a la Lista
                    </a>
                    <button type="submit" 
                            class="flex-1 sm:flex-none px-8 py-4 bg-gradient-to-r from-soft-green-400 to-soft-green-500 text-white rounded-xl hover:from-soft-green-500 hover:to-soft-green-600 transition-all duration-300 shadow-lg hover:shadow-xl text-center font-semibold flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Contador de caracteres para descripción
    const descriptionTextarea = document.querySelector('[name="description"]');
    const charCount = document.getElementById('char-count');
    
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

    if (descriptionTextarea && charCount) {
        descriptionTextarea.addEventListener('input', updateCharCount);
        updateCharCount(); // Inicializar contador
    }

    // Mostrar/ocultar campo de fecha de fin según el tipo de registro
    const typeMaintenance = document.getElementById('type_maintenance');
    const typeOperation = document.getElementById('type_operation');
    const endDateContainer = document.getElementById('end_date_container');
    const endDateInput = document.getElementById('end_date');
    const dateInput = document.querySelector('[name="date"]');

    function toggleEndDateField() {
        if (typeMaintenance && typeMaintenance.checked) {
            endDateContainer.classList.remove('hidden');
            if (dateInput && dateInput.value) {
                endDateInput.min = dateInput.value;
            }
        } else {
            // Solo ocultar si no hay valor en end_date
            if (!endDateInput || !endDateInput.value) {
                endDateContainer.classList.add('hidden');
            } else {
                // Si hay valor, mantener visible
                endDateContainer.classList.remove('hidden');
            }
        }
    }

    if (typeMaintenance && typeOperation && endDateContainer) {
        typeMaintenance.addEventListener('change', toggleEndDateField);
        typeOperation.addEventListener('change', toggleEndDateField);
        toggleEndDateField(); // Inicializar estado

        // Actualizar min de end_date cuando cambie la fecha
        if (dateInput) {
            dateInput.addEventListener('change', function() {
                if (endDateInput && typeMaintenance.checked) {
                    endDateInput.min = this.value;
                }
            });
        }

        // Cuando se registre una fecha de fin, cambiar automáticamente el tipo a "Operación"
        if (endDateInput) {
            endDateInput.addEventListener('change', function() {
                if (this.value && typeMaintenance && typeMaintenance.checked) {
                    // Cambiar automáticamente a "Operación"
                    typeOperation.checked = true;
                    typeMaintenance.checked = false;
                    // Mantener el campo de fecha de fin visible con su valor
                    // No ocultar porque la fecha de fin es importante para el registro
                }
            });
        }
    }

    // Confirmación antes de enviar
    const form = document.getElementById('maintenanceForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const machinery = document.querySelector('[name="machinery_id"]');
            const type = document.querySelector('input[name="type"]:checked');
            const description = document.querySelector('[name="description"]');
            const responsible = document.querySelector('[name="responsible"]');

            if (!machinery.value || !type || !description.value.trim() || !responsible.value.trim()) {
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
            const typeName = type.value === 'M' ? 'mantenimiento' : 'operación';
            const machineryName = machinery.options[machinery.selectedIndex].text;
            
            Swal.fire({
                title: '¿Confirmar actualización?',
                text: `¿Estás seguro de que deseas actualizar este ${typeName} para: ${machineryName}?`,
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

// Mostrar errores de validación si existen
@if($errors->any())
    Swal.fire({
        title: 'Errores de validación',
        html: '<ul class="text-left list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
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



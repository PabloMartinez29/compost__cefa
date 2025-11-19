@extends('layouts.master')

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
                Editar Uso del Equipo
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
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Información del Uso del Equipo</h2>
            </div>
        </div>

        <!-- Cuerpo del formulario -->
        <div class="p-8">
            <form action="{{ route('admin.machinery.usage-control.update', $usageControl) }}" method="POST" class="space-y-8" id="usageControlForm">
                @csrf
                @method('PUT')
                
                <!-- Primera fila -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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
                                    <option value="{{ $machinery->id }}" {{ old('machinery_id', $usageControl->machinery_id) == $machinery->id ? 'selected' : '' }}>
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

                    <!-- Responsable -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-user text-soft-green-500 mr-2"></i>
                            Responsable *
                        </label>
                        <input type="text" name="responsible" maxlength="150" required
                               value="{{ old('responsible', $usageControl->responsible) }}"
                               placeholder="Nombre del responsable"
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('responsible') border-red-500 @enderror">
                        @error('responsible')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Segunda fila -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Hora Inicio -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-clock text-soft-green-500 mr-2"></i>
                            Fecha/Hora Inicio *
                        </label>
                        <input type="datetime-local" name="start_date" required
                               value="{{ old('start_date', $usageControl->start_date ? $usageControl->start_date->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Hora Fin -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-clock text-soft-green-500 mr-2"></i>
                            Fecha/Hora Fin
                        </label>
                        <input type="datetime-local" name="end_date"
                               value="{{ old('end_date', $usageControl->end_date ? $usageControl->end_date->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Campo opcional. Se puede completar cuando se entregue la maquinaria
                        </p>
                    </div>

                    <!-- Total Horas -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-hourglass-half text-soft-green-500 mr-2"></i>
                            Total Horas de Uso
                        </label>
                        <input type="number" name="hours" id="hours" min="0" step="0.01"
                               value="{{ old('hours', $usageControl->hours) }}"
                               placeholder="0"
                               readonly
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl bg-gray-100 cursor-not-allowed focus:outline-none @error('hours') border-red-500 @enderror">
                        @error('hours')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Se calcula automáticamente cuando se registran ambas fechas
                        </p>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="space-y-2">
                    <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                        <i class="fas fa-sticky-note text-soft-green-500 mr-2"></i>
                        Observaciones
                    </label>
                    <textarea name="description" rows="4" maxlength="1000"
                              placeholder="Ingrese observaciones sobre el uso del equipo..."
                              class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 resize-none @error('description') border-red-500 @enderror">{{ old('description', $usageControl->description) }}</textarea>
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
                    <a href="{{ route('admin.machinery.usage-control.index') }}" 
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
    // Contador de caracteres para observaciones
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

    // Validar que end_date sea posterior o igual a start_date y calcular horas automáticamente
    const startDateInput = document.querySelector('[name="start_date"]');
    const endDateInput = document.querySelector('[name="end_date"]');
    const hoursInput = document.getElementById('hours');
    
    function calculateHours() {
        if (startDateInput.value && endDateInput.value) {
            const start = new Date(startDateInput.value);
            const end = new Date(endDateInput.value);
            
            if (end < start) {
                Swal.fire({
                    title: 'Error de validación',
                    text: 'La fecha/hora de fin debe ser posterior o igual a la fecha/hora de inicio.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Entendido',
                    customClass: {
                        popup: 'rounded-lg',
                        title: 'text-lg font-semibold',
                        content: 'text-sm text-gray-600',
                        confirmButton: 'px-4 py-2 rounded-lg font-medium'
                    }
                });
                endDateInput.value = startDateInput.value;
                hoursInput.value = 0;
                return;
            }
            
            // Calcular diferencia en horas
            const diffMs = end - start;
            const diffHours = diffMs / (1000 * 60 * 60); // Convertir milisegundos a horas
            
            // Redondear a 2 decimales
            hoursInput.value = Math.round(diffHours * 100) / 100;
        } else {
            // Si no hay fecha de fin, dejar horas en 0 o mantener el valor existente
            if (!endDateInput.value) {
                hoursInput.value = 0;
            }
        }
    }
    
    if (startDateInput && endDateInput && hoursInput) {
        // Calcular horas iniciales si hay valores
        calculateHours();
        
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            calculateHours();
        });
        
        endDateInput.addEventListener('change', function() {
            calculateHours();
        });
    }

    // Confirmación antes de enviar
    const form = document.getElementById('usageControlForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const machinery = document.querySelector('[name="machinery_id"]');
            const startDate = document.querySelector('[name="start_date"]');
            const endDate = document.querySelector('[name="end_date"]');
            const hours = document.getElementById('hours');
            const responsible = document.querySelector('[name="responsible"]');

            // Recalcular horas antes de enviar
            calculateHours();

            // Validar campos requeridos
            if (!machinery.value || !startDate.value || !responsible.value.trim()) {
                e.preventDefault();
                Swal.fire({
                    title: 'Campos incompletos',
                    text: 'Por favor completa todos los campos requeridos (Maquinaria, Fecha/Hora Inicio y Responsable) antes de continuar.',
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

            // Si hay fecha de fin, validar que sea posterior o igual a la de inicio
            if (endDate.value && endDate.value < startDate.value) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error de validación',
                    text: 'La fecha/hora de fin debe ser posterior o igual a la fecha/hora de inicio.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
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
            const machineryName = machinery.options[machinery.selectedIndex].text;
            
            Swal.fire({
                title: '¿Confirmar actualización?',
                text: `¿Estás seguro de que deseas actualizar el uso del equipo: ${machineryName}?`,
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



@extends('layouts.masteraprendiz')

@section('title', 'Nuevo Seguimiento')

@section('content')
<!-- SweetAlert2 para alertas de sesión -->
@if(session('success'))
<script>
    Swal.fire({
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#22c55e',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        title: 'Error',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonColor: '#ef4444'
    });
</script>
@endif

@if(session('warning'))
<script>
    Swal.fire({
        title: 'Advertencia',
        text: '{{ session('warning') }}',
        icon: 'warning',
        confirmButtonColor: '#f59e0b'
    });
</script>
@endif

@if(session('info'))
<script>
    Swal.fire({
        title: 'Información',
        text: '{{ session('info') }}',
        icon: 'info',
        confirmButtonColor: '#3b82f6'
    });
</script>
@endif

<div class="waste-container">
    <!-- Header -->
    <div class="waste-header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-3"></i>
                    Nuevo Seguimiento
                </h1>
                <p class="text-gray-600 mt-2">Registra el progreso de tu pila de compostaje</p>
            </div>
            <a href="{{ route('aprendiz.tracking.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
        <form action="{{ route('aprendiz.tracking.store') }}" method="POST" class="p-8">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Información Básica -->
                <div class="space-y-6">
                    <div class="bg-gradient-to-r from-soft-green-50 to-soft-green-100 rounded-lg p-4 border border-soft-green-200">
                        <h3 class="text-xl font-bold text-soft-gray-800 flex items-center">
                            <div class="bg-soft-green-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-info-circle text-soft-green-600 text-lg"></i>
                            </div>
                            Información Básica
                        </h3>
                        <p class="text-sm text-soft-gray-600 mt-1">Datos principales del seguimiento</p>
                    </div>
                    
                    <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                        <label for="composting_id" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                            <i class="fas fa-layer-group text-soft-green-600 mr-2"></i>
                            Pila de Compostaje <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select name="composting_id" id="composting_id" required
                                class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 @error('composting_id') border-red-500 @enderror">
                            <option value="">Selecciona una pila</option>
                            @foreach($activeCompostings as $composting)
                                <option value="{{ $composting->id }}" 
                                        {{ (request('composting_id') == $composting->id || old('composting_id') == $composting->id) ? 'selected' : '' }}>
                                    {{ $composting->formatted_pile_num }} - {{ $composting->formatted_start_date }}
                                </option>
                            @endforeach
                        </select>
                        @error('composting_id')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                            <label for="day" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                                <i class="fas fa-calendar-day text-soft-green-600 mr-2"></i>
                                Día del Proceso <span class="text-red-500 ml-1">*</span>
                                <span class="text-xs text-soft-gray-500 ml-2">(1-45 días)</span>
                            </label>
                            <input type="number" name="day" id="day" min="1" max="45" required
                                   value="{{ old('day') }}"
                                   class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 @error('day') border-red-500 @enderror"
                                   placeholder="Ej: 1, 2, 3... (máximo 45)"
                                   onchange="updateDayInfo()">
                            @error('day')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                            <div id="dayInfo" class="text-xs font-medium mt-2 p-2 rounded-lg"></div>
                        </div>

                        <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                            <label for="date" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                                <i class="fas fa-calendar-alt text-soft-green-600 mr-2"></i>
                                Fecha <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="date" name="date" id="date" required
                                   value="{{ old('date', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 @error('date') border-red-500 @enderror">
                            @error('date')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                        <label for="activity" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                            <i class="fas fa-tasks text-soft-green-600 mr-2"></i>
                            Actividad Realizada <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea name="activity" id="activity" rows="3" required
                                  class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 resize-none @error('activity') border-red-500 @enderror"
                                  placeholder="Describe las actividades realizadas...">{{ old('activity') }}</textarea>
                        @error('activity')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                        <label for="work_hours" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                            <i class="fas fa-clock text-soft-green-600 mr-2"></i>
                            Horas de Trabajo <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="work_hours" id="work_hours" required
                               value="{{ old('work_hours') }}"
                               class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 @error('work_hours') border-red-500 @enderror"
                               placeholder="Ej: 2 horas, 1.5 horas...">
                        @error('work_hours')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Mediciones -->
                <div class="space-y-6">
                    <div class="bg-gradient-to-r from-soft-green-50 to-soft-green-100 rounded-lg p-4 border border-soft-green-200">
                        <h3 class="text-xl font-bold text-soft-gray-800 flex items-center">
                            <div class="bg-soft-green-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-thermometer-half text-soft-green-600 text-lg"></i>
                            </div>
                            Mediciones
                        </h3>
                        <p class="text-sm text-soft-gray-600 mt-1">Datos técnicos del proceso</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                            <label for="temp_internal" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                                <i class="fas fa-thermometer-full text-soft-green-600 mr-2"></i>
                                Temperatura Interna (°C) <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" name="temp_internal" id="temp_internal" step="0.01" min="0" max="100" required
                                   value="{{ old('temp_internal') }}"
                                   class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 @error('temp_internal') border-red-500 @enderror"
                                   placeholder="Ej: 45.5">
                            @error('temp_internal')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                            <label for="temp_time" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                                <i class="fas fa-clock text-soft-green-600 mr-2"></i>
                                Hora de Medición <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="time" name="temp_time" id="temp_time" required
                                   value="{{ old('temp_time') }}"
                                   class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 @error('temp_time') border-red-500 @enderror">
                            @error('temp_time')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                            <label for="temp_env" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                                <i class="fas fa-sun text-soft-green-600 mr-2"></i>
                                Temperatura Ambiente (°C) <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" name="temp_env" id="temp_env" step="0.01" min="-10" max="50" required
                                   value="{{ old('temp_env') }}"
                                   class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 @error('temp_env') border-red-500 @enderror"
                                   placeholder="Ej: 25.0">
                            @error('temp_env')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                            <label for="hum_pile" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                                <i class="fas fa-tint text-soft-green-600 mr-2"></i>
                                Humedad Pila (%) <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" name="hum_pile" id="hum_pile" step="0.01" min="0" max="100" required
                                   value="{{ old('hum_pile') }}"
                                   class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 @error('hum_pile') border-red-500 @enderror"
                                   placeholder="Ej: 60.0">
                            @error('hum_pile')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                            <label for="hum_env" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                                <i class="fas fa-cloud text-soft-green-600 mr-2"></i>
                                Humedad Ambiente (%) <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" name="hum_env" id="hum_env" step="0.01" min="0" max="100" required
                                   value="{{ old('hum_env') }}"
                                   class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 @error('hum_env') border-red-500 @enderror"
                                   placeholder="Ej: 70.0">
                            @error('hum_env')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                            <label for="ph" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                                <i class="fas fa-flask text-soft-green-600 mr-2"></i>
                                pH <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" name="ph" id="ph" step="0.01" min="0" max="14" required
                                   value="{{ old('ph') }}"
                                   class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 @error('ph') border-red-500 @enderror"
                                   placeholder="Ej: 6.5">
                            @error('ph')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                            <label for="water" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                                <i class="fas fa-tint text-soft-green-600 mr-2"></i>
                                Agua Agregada (L) <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" name="water" id="water" step="0.01" min="0" required
                                   value="{{ old('water') }}"
                                   class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 @error('water') border-red-500 @enderror"
                                   placeholder="Ej: 5.0">
                            @error('water')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                            <label for="lime" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                                <i class="fas fa-mountain text-soft-green-600 mr-2"></i>
                                Cal Agregada (Kg) <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" name="lime" id="lime" step="0.01" min="0" required
                                   value="{{ old('lime') }}"
                                   class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 @error('lime') border-red-500 @enderror"
                                   placeholder="Ej: 2.5">
                            @error('lime')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200">
                        <label for="others" class="block text-sm font-semibold text-soft-gray-700 mb-3 flex items-center">
                            <i class="fas fa-sticky-note text-soft-green-600 mr-2"></i>
                            Observaciones Adicionales
                        </label>
                        <textarea name="others" id="others" rows="3"
                                  class="w-full px-4 py-3 border-2 border-soft-gray-300 rounded-lg focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 resize-none @error('others') border-red-500 @enderror"
                                  placeholder="Observaciones, notas adicionales...">{{ old('others') }}</textarea>
                        @error('others')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="bg-gradient-to-r from-soft-gray-50 to-soft-gray-100 rounded-lg p-6 border border-soft-gray-200 mt-8">
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('aprendiz.tracking.index') }}" 
                       class="bg-soft-gray-600 hover:bg-soft-gray-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-r from-soft-green-600 to-soft-green-700 hover:from-soft-green-700 hover:to-soft-green-800 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Seguimiento
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Si hay un composting_id en la URL, seleccionarlo automáticamente
    const urlParams = new URLSearchParams(window.location.search);
    const compostingId = urlParams.get('composting_id');
    if (compostingId) {
        document.getElementById('composting_id').value = compostingId;
    }
    
    // Inicializar información del día
    updateDayInfo();
});

function updateDayInfo() {
    const dayInput = document.getElementById('day');
    const dayInfo = document.getElementById('dayInfo');
    const day = parseInt(dayInput.value);
    
    if (day && day >= 1 && day <= 45) {
        let phase = '';
        let bgColor = '';
        let textColor = '';
        let icon = '';
        
        if (day <= 7) {
            phase = 'Fase Inicial (Mesófila)';
            bgColor = 'bg-soft-green-100';
            textColor = 'text-soft-green-800';
            icon = '🌱';
        } else if (day <= 21) {
            phase = 'Fase Termófila (Alta temperatura)';
            bgColor = 'bg-soft-green-200';
            textColor = 'text-soft-green-800';
            icon = '🔥';
        } else if (day <= 35) {
            phase = 'Fase de Enfriamiento';
            bgColor = 'bg-soft-gray-100';
            textColor = 'text-soft-gray-800';
            icon = '❄️';
        } else {
            phase = 'Fase de Maduración';
            bgColor = 'bg-soft-green-300';
            textColor = 'text-soft-green-900';
            icon = '🌿';
        }
        
        dayInfo.innerHTML = `
            <div class="${bgColor} ${textColor} p-3 rounded-lg border-2 border-current">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="text-lg mr-2">${icon}</span>
                        <span class="font-bold">${phase}</span>
                    </div>
                    <span class="font-semibold">Día ${day} de 45</span>
                </div>
            </div>
        `;
    } else if (day > 45) {
        dayInfo.innerHTML = `
            <div class="bg-red-100 text-red-800 p-3 rounded-lg border-2 border-red-300">
                <div class="flex items-center">
                    <span class="text-lg mr-2">⚠️</span>
                    <span class="font-bold">El proceso de compostaje dura máximo 45 días</span>
                </div>
            </div>
        `;
    } else {
        dayInfo.innerHTML = '';
    }
}

// Validación en tiempo real
document.getElementById('day').addEventListener('input', function() {
    const day = parseInt(this.value);
    if (day > 45) {
        this.setCustomValidity('El proceso de compostaje dura máximo 45 días');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endsection

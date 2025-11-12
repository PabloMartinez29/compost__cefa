@extends('layouts.master')

@section('title', 'Registrar Mantenimiento de Maquinaria')

@section('content')
<div class="min-h-screen bg-soft-gray-50 py-8">
    <!-- Contenedor más ancho -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-soft-green-500 to-soft-green-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-wrench text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-soft-gray-900">Registro de Mantenimiento</h1>
                    <p class="text-soft-gray-600">Registra mantenimientos y operaciones de maquinaria</p>
                </div>
            </div>
        </div>

        <!-- Mensajes de estado -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Por favor corrige los siguientes errores:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden w-full">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-soft-green-500 to-soft-green-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-edit mr-3"></i>
                    Formulario de Mantenimiento
                </h2>
            </div>

            <!-- Form Body -->
            <form action="{{ route('admin.machinery.maintenance.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Primera fila: Fecha, Maquinaria, Tipo -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Fecha -->
                    <div class="space-y-2">
                        <label for="date" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-soft-green-600"></i>
                            Fecha
                        </label>
                        <input type="date" name="date" id="date" required
                               value="{{ old('date', date('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-soft-gray-300 rounded-xl focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 bg-soft-gray-50 hover:bg-white">
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Maquinaria -->
                    <div class="space-y-2">
                        <label for="machinery_id" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-cogs mr-2 text-soft-green-600"></i>
                            Maquinaria
                        </label>
                        <select name="machinery_id" id="machinery_id" required
                                class="w-full px-4 py-3 border border-soft-gray-300 rounded-xl focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 bg-soft-gray-50 hover:bg-white">
                            <option value="">Seleccionar maquinaria...</option>
                            @foreach($machineries as $machinery)
                                <option value="{{ $machinery->id }}" {{ old('machinery_id') == $machinery->id ? 'selected' : '' }}>
                                    {{ $machinery->name }} - {{ $machinery->brand }} {{ $machinery->model }}
                                </option>
                            @endforeach
                        </select>
                        @error('machinery_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-tasks mr-2 text-soft-green-600"></i>
                            Tipo de Registro
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border border-soft-gray-300 rounded-xl cursor-pointer hover:bg-soft-green-50 transition-all duration-200">
                                <input type="radio" name="type" value="M" {{ old('type') == 'M' ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-4 h-4 border-2 border-soft-gray-300 rounded-full peer-checked:border-soft-green-500 peer-checked:bg-soft-green-500 mr-3 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-medium text-soft-gray-700 peer-checked:text-soft-green-700">Mantenimiento</span>
                            </label>
                            <label class="flex items-center p-3 border border-soft-gray-300 rounded-xl cursor-pointer hover:bg-soft-green-50 transition-all duration-200">
                                <input type="radio" name="type" value="O" {{ old('type') == 'O' ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-4 h-4 border-2 border-soft-gray-300 rounded-full peer-checked:border-soft-green-500 peer-checked:bg-soft-green-500 mr-3 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-medium text-soft-gray-700 peer-checked:text-soft-green-700">Operación</span>
                            </label>
                        </div>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Segunda fila: Responsable -->
                <div class="grid grid-cols-1 gap-6">
                    <!-- Responsable -->
                    <div class="space-y-2">
                        <label for="responsible" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-user mr-2 text-soft-green-600"></i>
                            Responsable
                        </label>
                        <input type="text" name="responsible" id="responsible" maxlength="150" required
                               value="{{ old('responsible') }}"
                               placeholder="Nombre del responsable del mantenimiento/operación"
                               class="w-full px-4 py-3 border border-soft-gray-300 rounded-xl focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 bg-soft-gray-50 hover:bg-white">
                        @error('responsible')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                        <i class="fas fa-sticky-note mr-2 text-soft-green-600"></i>
                        Descripción del Trabajo Realizado
                    </label>
                    <textarea name="description" id="description" rows="4" maxlength="1000" required
                              placeholder="Describe detalladamente el mantenimiento u operación realizada..."
                              class="w-full px-4 py-3 border border-soft-gray-300 rounded-xl focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 bg-soft-gray-50 hover:bg-white resize-none">{{ old('description') }}</textarea>
                    <div class="flex justify-between">
                        @error('description')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @else
                            <p class="text-soft-gray-500 text-xs">Máximo 1000 caracteres</p>
                        @enderror
                        <p class="text-soft-gray-500 text-xs" id="char-count">0/1000</p>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-soft-gray-200 justify-center">
                    <button type="button" onclick="window.history.back()"
                            class="px-6 py-2.5 border border-soft-gray-300 text-soft-gray-700 rounded-xl hover:bg-soft-gray-50 transition-all duration-200 font-medium flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-soft-green-500 to-soft-green-600 text-white rounded-xl hover:from-soft-green-600 hover:to-soft-green-700 transition-all duration-200 font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Registrar Mantenimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-completar fecha actual
        const dateInput = document.getElementById('date');
        if (!dateInput.value) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }

        // Contador de caracteres para descripción
        const descriptionTextarea = document.getElementById('description');
        const charCount = document.getElementById('char-count');
        
        function updateCharCount() {
            const count = descriptionTextarea.value.length;
            charCount.textContent = `${count}/1000`;
            
            if (count > 900) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        }

        descriptionTextarea.addEventListener('input', updateCharCount);
        updateCharCount(); // Inicializar contador

        // Confirmación antes de enviar
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const machinery = document.getElementById('machinery_id');
            const type = document.querySelector('input[name="type"]:checked');
            const description = document.getElementById('description');
            const responsible = document.getElementById('responsible');

            if (!machinery.value || !type || !description.value.trim() || !responsible.value.trim()) {
                e.preventDefault();
                alert('Por favor completa todos los campos requeridos.');
                return false;
            }

            const typeName = type.value === 'M' ? 'mantenimiento' : 'operación';
            const machineryName = machinery.options[machinery.selectedIndex].text;
            
            return confirm(`¿Confirmar registro de ${typeName} para: ${machineryName}?`);
        });
    });
</script>
@endsection






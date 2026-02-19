@extends('layouts.masteraprendiz')

@section('content')
@vite(['resources/css/waste.css'])

<style>
    main {
        padding: 0.75rem !important;
    }
</style>

<div class="container mx-auto px-4 py-2">
    <!-- Header con colores suaves como la vista de lista -->
    <div class="waste-header animate-fade-in-up mb-3 py-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-plus text-green-500 mr-2"></i>
                Registrar Entrega de Abono Terminado
            </h1>
            <p class="waste-subtitle text-sm">
                <i class="fas fa-user-shield text-green-400 mr-2"></i>
                {{ Auth::user()->name }} - Crear nuevo registro
            </p>
        </div>
    </div>

    <!-- Formulario con estilo de tarjeta como la vista de lista -->
    <div class="waste-card animate-fade-in-up animate-delay-1">
        <!-- Header del formulario -->
        <div class="waste-card-header">
            <div class="flex items-center space-x-3">
                <div class="waste-card-icon text-green-600">
                    <i class="fas fa-seedling"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Información de la Entrega</h2>
            </div>
        </div>

            <!-- Cuerpo del formulario -->
            <div class="p-5">
                <form action="{{ route('aprendiz.fertilizer.store') }}" method="POST" class="space-y-5">
                @csrf

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                    <!-- Primera fila: Fecha, Hora, Pila -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <!-- Fecha -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-calendar-alt text-soft-green-500 mr-2"></i>
                                Fecha *
                            </label>
                            <input type="date" name="date" id="date" 
                                   class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('date') border-red-500 @enderror" 
                                   value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Hora -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-clock text-soft-green-500 mr-2"></i>
                                Hora *
                            </label>
                            <input type="time" name="time" id="time" 
                                   class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('time') border-red-500 @enderror" 
                                   value="{{ old('time', date('H:i')) }}" required>
                            @error('time')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Pila (Compostaje) -->
                        <div class="space-y-2 lg:col-span-3">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-mountain text-soft-green-500 mr-2"></i>
                                Pila *
                            </label>
                            <div class="relative">
                                <select name="composting_id" id="composting_id" 
                                        class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 text-sm @error('composting_id') border-red-500 @enderror appearance-none bg-white" 
                                        required>
                                    <option value="">Seleccionar pila completada...</option>
                                    @foreach($completedCompostings ?? [] as $composting)
                                        <option value="{{ $composting->id }}" 
                                                data-total-kg="{{ $composting->total_kg }}"
                                                {{ old('composting_id') == $composting->id ? 'selected' : '' }}>
                                            {{ $composting->formatted_pile_num }} - {{ $composting->status }} 
                                            ({{ $composting->formatted_start_date }} → {{ $composting->formatted_end_date }}) — Disponible: {{ number_format($composting->total_kg ?? 0, 2) }} Kg
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p id="selectedPileText" class="text-xs text-gray-600 mt-1 italic hidden"></p>
                            @error('composting_id')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                            @if(empty($completedCompostings) || $completedCompostings->isEmpty())
                                <p class="text-yellow-600 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    No hay pilas completadas disponibles. Las pilas deben tener 45 seguimientos o fecha de finalización.
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Segunda fila: Solicitante, Destino -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <!-- Solicitante -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-user text-soft-green-500 mr-2"></i>
                                Solicitante *
                            </label>
                            <input type="text" name="requester" id="requester" maxlength="150" 
                                   class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('requester') border-red-500 @enderror" 
                                   placeholder="Nombre del solicitante" 
                                   value="{{ old('requester') }}" required>
                            @error('requester')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Destino -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-map-marker-alt text-soft-green-500 mr-2"></i>
                                Destino *
                            </label>
                            <input type="text" name="destination" id="destination" maxlength="150" 
                                   class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('destination') border-red-500 @enderror" 
                                   placeholder="Lugar de destino" 
                                   value="{{ old('destination') }}" required>
                            @error('destination')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tercera fila: Quién recibe, Quién entrega -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <!-- Quién recibe -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-hand-holding text-soft-green-500 mr-2"></i>
                                Quién Recibe *
                            </label>
                            <input type="text" name="received_by" id="received_by" maxlength="150" 
                                   class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('received_by') border-red-500 @enderror" 
                                   placeholder="Nombre de quien recibe" 
                                   value="{{ old('received_by') }}" required>
                            @error('received_by')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Quién entrega -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-shipping-fast text-soft-green-500 mr-2"></i>
                                Quién Entrega *
                            </label>
                            <input type="text" name="delivered_by" id="delivered_by" maxlength="150" 
                                   class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('delivered_by') border-red-500 @enderror" 
                                   placeholder="Nombre de quien entrega" 
                                   value="{{ old('delivered_by', Auth::user()->name) }}" required>
                            @error('delivered_by')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Cuarta fila: Tipo de abono, Cantidad -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <!-- Tipo de abono -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-seedling text-soft-green-500 mr-2"></i>
                                Tipo de Abono *
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:bg-green-50 transition-all duration-300 @error('type') border-red-500 @enderror">
                                    <input type="radio" name="type" value="Liquid" {{ old('type') == 'Liquid' ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 flex items-center justify-center transition-all duration-300">
                                        <div class="w-2.5 h-2.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 peer-checked:text-green-700">Líquido</span>
                                </label>
                                <label class="flex items-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:bg-green-50 transition-all duration-300 @error('type') border-red-500 @enderror">
                                    <input type="radio" name="type" value="Solid" {{ old('type') == 'Solid' ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 flex items-center justify-center transition-all duration-300">
                                        <div class="w-2.5 h-2.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 peer-checked:text-green-700">Sólido</span>
                                </label>
                            </div>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Cantidad -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-weight text-soft-green-500 mr-2"></i>
                                Cantidad (KG/L) *
                            </label>
                            <div class="relative">
                                <input type="number" name="amount" id="amount" step="0.01" min="0.01" 
                                       class="w-full px-3 py-2.5 pr-16 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('amount') border-red-500 @enderror" 
                                       placeholder="0.00" 
                                       value="{{ old('amount') }}" 
                                       required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-semibold" id="amountUnit">Kg</span>
                                </div>
                            </div>
                            <p class="text-gray-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Ingrese la cantidad a entregar. Se sugerirá el total disponible de la pila seleccionada.
                            </p>
                            <p id="availableAmount" class="text-blue-600 text-sm mt-1 hidden">
                                <i class="fas fa-info-circle mr-1"></i>
                                Disponible: <span id="availableAmountValue">0.00</span> Kg
                            </p>
                            @error('amount')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notas -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-sticky-note text-soft-green-500 mr-2"></i>
                            Notas Adicionales
                        </label>
                        <textarea name="notes" id="notes" 
                                  class="w-full px-3 py-2 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 resize-none @error('notes') border-red-500 @enderror" 
                                  rows="2" 
                                  placeholder="Ingrese notas adicionales sobre la entrega...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-300">
                        <a href="{{ route('aprendiz.fertilizer.index') }}" 
                           class="flex-1 sm:flex-none px-4 py-2 bg-soft-gray-100 text-soft-gray-700 rounded-lg hover:bg-soft-gray-200 transition-all duration-200 text-center font-medium flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="flex-1 sm:flex-none px-4 py-2 bg-soft-green-400 text-white rounded-lg hover:bg-soft-green-500 transition-all duration-200 shadow-md hover:shadow-lg text-center font-medium flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            Guardar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-completar fecha y hora actual
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('date');
        const timeInput = document.getElementById('time');
        const compostingSelect = document.getElementById('composting_id');
        const amountInput = document.getElementById('amount');
        const amountUnit = document.getElementById('amountUnit');
        const selectedPileText = document.getElementById('selectedPileText');
        
        if (!dateInput.value) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }
        
        if (!timeInput.value) {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            timeInput.value = `${hours}:${minutes}`;
        }

        // Si ya hay una pila seleccionada (por old() después de validación), mostrarla completa abajo
        if (compostingSelect && selectedPileText && compostingSelect.value) {
            const selectedOption = compostingSelect.options[compostingSelect.selectedIndex];
            selectedPileText.textContent = selectedOption.text.trim();
            selectedPileText.classList.remove('hidden');
            // Mostrar texto completo en tooltip al pasar el mouse
            compostingSelect.title = selectedOption.text.trim();
        }

        // Actualizar cantidad disponible cuando se selecciona una pila
        compostingSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const availableAmountElement = document.getElementById('availableAmount');
            const availableAmountValue = document.getElementById('availableAmountValue');
            
            if (selectedOption.value) {
                const totalKg = parseFloat(selectedOption.getAttribute('data-total-kg'));
                if (totalKg && !isNaN(totalKg)) {
                    // Solo mostrar cantidad disponible, NO llenar el campo automáticamente
                    if (availableAmountElement && availableAmountValue) {
                        availableAmountValue.textContent = totalKg.toFixed(2);
                        availableAmountElement.classList.remove('hidden');
                    }
                } else {
                    if (availableAmountElement) {
                        availableAmountElement.classList.add('hidden');
                    }
                }

                // Mostrar texto completo de la pila seleccionada debajo del select
                if (selectedPileText) {
                    selectedPileText.textContent = selectedOption.text.trim();
                    selectedPileText.classList.remove('hidden');
                }

                // Actualizar tooltip del select con el texto completo
                this.title = selectedOption.text.trim();
            } else {
                if (selectedPileText) {
                    selectedPileText.textContent = '';
                    selectedPileText.classList.add('hidden');
                }
                if (availableAmountElement) {
                    availableAmountElement.classList.add('hidden');
                }
            }
        });

        // Actualizar unidad según el tipo de abono
        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'Liquid') {
                    amountUnit.textContent = 'L';
                } else {
                    amountUnit.textContent = 'Kg';
                }
            });
        });
    });
</script>
@endsection


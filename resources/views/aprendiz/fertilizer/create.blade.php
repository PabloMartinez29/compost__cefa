@extends('layouts.masteraprendiz')

@section('content')
<div class="min-h-screen bg-soft-gray-50 py-8">
    <!-- Contenedor más ancho -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-soft-green-500 to-soft-green-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-seedling text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-soft-gray-900">Entrega de Abono Terminado</h1>
                    <p class="text-soft-gray-600">Registra la información de entrega del abono</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden w-full">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-soft-green-500 to-soft-green-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-edit mr-3"></i>
                    Formulario de Registro
                </h2>
            </div>

            <!-- Form Body -->
            <form action="{{ route('aprendiz.fertilizer.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Primera fila: Fecha, Hora, Pila -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Fecha -->
                    <div class="space-y-2">
                        <label for="date" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-soft-green-600"></i>
                            Fecha
                        </label>
                        <input type="date" name="date" id="date" required
                               value="{{ old('date', date('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-soft-gray-300 rounded-xl focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 bg-soft-gray-50 hover:bg-white">
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hora -->
                    <div class="space-y-2">
                        <label for="time" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-clock mr-2 text-soft-green-600"></i>
                            Hora
                        </label>
                        <input type="time" name="time" id="time" required
                               value="{{ old('time', date('H:i')) }}"
                               class="w-full px-4 py-3 border border-soft-gray-300 rounded-xl focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 bg-soft-gray-50 hover:bg-white">
                        @error('time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pila (Compostaje) -->
                    <div class="space-y-2">
                        <label for="composting_id" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-mountain mr-2 text-soft-green-600"></i>
                            Pila *
                        </label>
                        <select name="composting_id" id="composting_id" required
                                class="w-full px-4 py-3 border border-soft-gray-300 rounded-xl focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 bg-soft-gray-50 hover:bg-white">
                            <option value="">Seleccionar pila completada...</option>
                            @foreach($completedCompostings ?? [] as $composting)
                                <option value="{{ $composting->id }}" 
                                        data-total-kg="{{ $composting->total_kg }}"
                                        {{ old('composting_id') == $composting->id ? 'selected' : '' }}>
                                    {{ $composting->formatted_pile_num }} - {{ $composting->formatted_total_kg }} ({{ $composting->formatted_start_date }})
                                </option>
                            @endforeach
                        </select>
                        @error('composting_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @if(empty($completedCompostings) || $completedCompostings->isEmpty())
                            <p class="text-yellow-600 text-xs mt-1">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                No hay pilas completadas disponibles. Las pilas deben tener 45 seguimientos o fecha de finalización.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Segunda fila: Solicitante, Destino -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Solicitante -->
                    <div class="space-y-2">
                        <label for="requester" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-user mr-2 text-soft-green-600"></i>
                            Solicitante
                        </label>
                        <input type="text" name="requester" id="requester" maxlength="150" required
                               value="{{ old('requester') }}"
                               placeholder="Nombre del solicitante"
                               class="w-full px-4 py-3 border border-soft-gray-300 rounded-xl focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 bg-soft-gray-50 hover:bg-white">
                        @error('requester')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Destino -->
                    <div class="space-y-2">
                        <label for="destination" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-soft-green-600"></i>
                            Destino
                        </label>
                        <input type="text" name="destination" id="destination" maxlength="150" required
                               value="{{ old('destination') }}"
                               placeholder="Lugar de destino"
                               class="w-full px-4 py-3 border border-soft-gray-300 rounded-xl focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 bg-soft-gray-50 hover:bg-white">
                        @error('destination')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tercera fila: Quién recibe, Quién entrega -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Quién recibe -->
                    <div class="space-y-2">
                        <label for="received_by" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-hand-holding mr-2 text-soft-green-600"></i>
                            Quién Recibe
                        </label>
                        <input type="text" name="received_by" id="received_by" maxlength="150" required
                               value="{{ old('received_by') }}"
                               placeholder="Nombre de quien recibe"
                               class="w-full px-4 py-3 border border-soft-gray-300 rounded-xl focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 bg-soft-gray-50 hover:bg-white">
                        @error('received_by')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quién entrega -->
                    <div class="space-y-2">
                        <label for="delivered_by" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-shipping-fast mr-2 text-soft-green-600"></i>
                            Quién Entrega
                        </label>
                        <input type="text" name="delivered_by" id="delivered_by" maxlength="150" required
                               value="{{ old('delivered_by') }}"
                               placeholder="Nombre de quien entrega"
                               class="w-full px-4 py-3 border border-soft-gray-300 rounded-xl focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 bg-soft-gray-50 hover:bg-white">
                        @error('delivered_by')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Cuarta fila: Tipo de abono, Cantidad -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tipo de abono -->
                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-seedling mr-2 text-soft-green-600"></i>
                            Tipo de Abono
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border border-soft-gray-300 rounded-xl cursor-pointer hover:bg-soft-green-50 transition-all duration-200">
                                <input type="radio" name="type" value="Liquid" {{ old('type') == 'Liquid' ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-4 h-4 border-2 border-soft-gray-300 rounded-full peer-checked:border-soft-green-500 peer-checked:bg-soft-green-500 mr-3 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-medium text-soft-gray-700 peer-checked:text-soft-green-700">Líquido</span>
                            </label>
                            <label class="flex items-center p-3 border border-soft-gray-300 rounded-xl cursor-pointer hover:bg-soft-green-50 transition-all duration-200">
                                <input type="radio" name="type" value="Solid" {{ old('type') == 'Solid' ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-4 h-4 border-2 border-soft-gray-300 rounded-full peer-checked:border-soft-green-500 peer-checked:bg-soft-green-500 mr-3 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-medium text-soft-gray-700 peer-checked:text-soft-green-700">Sólido</span>
                            </label>
                        </div>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cantidad -->
                    <div class="space-y-2">
                        <label for="amount" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                            <i class="fas fa-weight mr-2 text-soft-green-600"></i>
                            Cantidad (KG/L) *
                        </label>
                        <div class="relative">
                            <input type="number" name="amount" id="amount" step="0.01" min="0.01" required
                                   value="{{ old('amount') }}"
                                   placeholder="0.00"
                                   readonly
                                   class="w-full px-4 py-3 pr-16 border border-soft-gray-300 rounded-xl bg-gray-100 cursor-not-allowed">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <span class="text-soft-gray-500 text-sm font-medium" id="amountUnit">Kg</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            La cantidad se establece automáticamente según el total de kilos de la pila seleccionada.
                        </p>
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notas -->
                <div class="space-y-2">
                    <label for="notes" class="block text-sm font-semibold text-soft-gray-700 flex items-center">
                        <i class="fas fa-sticky-note mr-2 text-soft-green-600"></i>
                        Notas (Opcional)
                    </label>
                    <textarea name="notes" id="notes" rows="4"
                              placeholder="Observaciones adicionales sobre la entrega..."
                              class="w-full px-4 py-3 border border-soft-gray-300 rounded-xl focus:ring-2 focus:ring-soft-green-500 focus:border-soft-green-500 transition-all duration-200 bg-soft-gray-50 hover:bg-white resize-none">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones de acción -->
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
        Registrar Entrega
    </button>
</div>

            </form>
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
        
        if (!dateInput.value) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }
        
        if (!timeInput.value) {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            timeInput.value = `${hours}:${minutes}`;
        }

        // Actualizar cantidad cuando se selecciona una pila
        compostingSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const totalKg = parseFloat(selectedOption.getAttribute('data-total-kg'));
                if (totalKg && !isNaN(totalKg)) {
                    amountInput.value = totalKg.toFixed(2);
                } else {
                    amountInput.value = '';
                }
            } else {
                amountInput.value = '';
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


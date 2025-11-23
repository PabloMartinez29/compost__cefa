@extends('layouts.master')

@section('content')
@vite(['resources/css/waste.css'])

<div class="min-h-screen bg-soft-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="waste-header animate-fade-in-up mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="waste-title">
                        <i class="fas fa-edit waste-icon"></i>
                        Editar Registro de Abono Terminado
                    </h1>
                    <p class="waste-subtitle">
                        <i class="fas fa-user-shield text-green-400 mr-2"></i>
                        {{ Auth::user()->name }} - Registro #{{ str_pad($fertilizer->id, 3, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="waste-form animate-fade-in-up animate-delay-1">
            <form action="{{ route('admin.fertilizer.update', $fertilizer) }}" method="POST">
                @csrf
                @method('PUT')

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Primera fila: Fecha, Hora, Pila (solo lectura) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Fecha -->
                    <div class="waste-form-group">
                        <label for="date" class="waste-form-label">Fecha *</label>
                        <input type="date" name="date" id="date" required
                               value="{{ old('date', $fertilizer->date->format('Y-m-d')) }}"
                               class="waste-form-input @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hora -->
                    <div class="waste-form-group">
                        <label for="time" class="waste-form-label">Hora *</label>
                        <input type="time" name="time" id="time" required
                               value="{{ old('time', $fertilizer->time) }}"
                               class="waste-form-input @error('time') border-red-500 @enderror">
                        @error('time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pila (solo lectura) -->
                    <div class="waste-form-group">
                        <label for="composting_id" class="waste-form-label">Pila</label>
                        <input type="text" 
                               value="{{ $fertilizer->composting ? $fertilizer->composting->formatted_pile_num : 'N/A' }}" 
                               readonly
                               class="waste-form-input bg-gray-50 cursor-not-allowed">
                        <input type="hidden" name="composting_id" value="{{ $fertilizer->composting_id }}">
                        <p class="text-gray-500 text-sm mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            La pila no se puede modificar después de crear el registro.
                        </p>
                    </div>
                </div>

                <!-- Segunda fila: Solicitante, Destino -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Solicitante -->
                    <div class="waste-form-group">
                        <label for="requester" class="waste-form-label">Solicitante *</label>
                        <input type="text" name="requester" id="requester" maxlength="150" required
                               value="{{ old('requester', $fertilizer->requester) }}"
                               placeholder="Nombre del solicitante"
                               class="waste-form-input @error('requester') border-red-500 @enderror">
                        @error('requester')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Destino -->
                    <div class="waste-form-group">
                        <label for="destination" class="waste-form-label">Destino *</label>
                        <input type="text" name="destination" id="destination" maxlength="150" required
                               value="{{ old('destination', $fertilizer->destination) }}"
                               placeholder="Lugar de destino"
                               class="waste-form-input @error('destination') border-red-500 @enderror">
                        @error('destination')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tercera fila: Quién recibe, Quién entrega -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Quién recibe -->
                    <div class="waste-form-group">
                        <label for="received_by" class="waste-form-label">Quién Recibe *</label>
                        <input type="text" name="received_by" id="received_by" maxlength="150" required
                               value="{{ old('received_by', $fertilizer->received_by) }}"
                               placeholder="Nombre de quien recibe"
                               class="waste-form-input @error('received_by') border-red-500 @enderror">
                        @error('received_by')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quién entrega -->
                    <div class="waste-form-group">
                        <label for="delivered_by" class="waste-form-label">Quién Entrega *</label>
                        <input type="text" name="delivered_by" id="delivered_by" maxlength="150" required
                               value="{{ old('delivered_by', $fertilizer->delivered_by) }}"
                               placeholder="Nombre de quien entrega"
                               class="waste-form-input @error('delivered_by') border-red-500 @enderror">
                        @error('delivered_by')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Cuarta fila: Tipo de abono, Cantidad -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tipo de abono -->
                    <div class="waste-form-group">
                        <label for="type" class="waste-form-label">Tipo de Abono *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-green-50 transition-all duration-200">
                                <input type="radio" name="type" value="Liquid" {{ old('type', $fertilizer->type) == 'Liquid' ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-green-700">Líquido</span>
                            </label>
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-green-50 transition-all duration-200">
                                <input type="radio" name="type" value="Solid" {{ old('type', $fertilizer->type) == 'Solid' ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-green-700">Sólido</span>
                            </label>
                        </div>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cantidad -->
                    <div class="waste-form-group">
                        <label for="amount" class="waste-form-label">Cantidad (KG/L) *</label>
                        <div class="relative">
                            <input type="number" name="amount" id="amount" step="0.01" min="0.01" required
                                   value="{{ old('amount', $fertilizer->amount) }}"
                                   placeholder="0.00"
                                   class="waste-form-input pr-16 @error('amount') border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <span class="text-gray-500 text-sm font-medium" id="amountUnit">{{ $fertilizer->type == 'Liquid' ? 'L' : 'Kg' }}</span>
                            </div>
                        </div>
                        @error('amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notas -->
                <div class="waste-form-group">
                    <label for="notes" class="waste-form-label">Notas</label>
                    <textarea name="notes" id="notes" rows="4"
                              placeholder="Observaciones adicionales sobre la entrega..."
                              class="waste-form-textarea @error('notes') border-red-500 @enderror">{{ old('notes', $fertilizer->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.fertilizer.index') }}" 
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
    // Actualizar unidad según el tipo de abono
    document.addEventListener('DOMContentLoaded', function() {
        const amountUnit = document.getElementById('amountUnit');
        
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


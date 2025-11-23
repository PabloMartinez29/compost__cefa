@extends('layouts.master')

@section('content')
@vite(['resources/css/waste.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header con colores suaves como la vista de lista -->
    <div class="waste-header animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-plus text-green-500 mr-3"></i>
                Registrar Residuo Orgánico
            </h1>
            <p class="waste-subtitle">
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
                    <i class="fas fa-recycle"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Información del Residuo</h2>
            </div>
        </div>

            <!-- Cuerpo del formulario -->
            <div class="p-8">
                <form action="{{ route('admin.organic.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Primera fila -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Fecha -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-calendar-alt text-soft-green-500 mr-2"></i>
                                Fecha del Registro *
                            </label>
                            <input type="date" name="date" 
                                   class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('date') border-red-500 @enderror" 
                                   value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Tipo de Residuo -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-recycle text-soft-green-500 mr-2"></i>
                                Tipo de Residuo *
                            </label>
                            <div class="relative">
                                <select name="type" 
                                        class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('type') border-red-500 @enderror appearance-none bg-white" 
                                        required>
                                    <option value="">Seleccionar tipo de residuo</option>
                                    <option value="Kitchen" {{ old('type') == 'Kitchen' ? 'selected' : '' }}>🍽️ Cocina</option>
                                    <option value="Beds" {{ old('type') == 'Beds' ? 'selected' : '' }}>🛏️ Camas</option>
                                    <option value="Leaves" {{ old('type') == 'Leaves' ? 'selected' : '' }}>🍃 Hojas</option>
                                    <option value="CowDung" {{ old('type') == 'CowDung' ? 'selected' : '' }}>🐄 Estiércol de Vaca</option>
                                    <option value="ChickenManure" {{ old('type') == 'ChickenManure' ? 'selected' : '' }}>🐔 Estiércol de Pollo</option>
                                    <option value="PigManure" {{ old('type') == 'PigManure' ? 'selected' : '' }}>🐷 Estiércol de Cerdo</option>
                                    <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>📦 Otro</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
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
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Peso -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-weight text-soft-green-500 mr-2"></i>
                                Peso (Kilogramos) *
                            </label>
                            <div class="relative">
                                <input type="number" name="weight" 
                                       class="w-full px-4 py-4 pr-12 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('weight') border-red-500 @enderror" 
                                       placeholder="0.00" step="0.01" min="0.01" 
                                       value="{{ old('weight') }}" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-semibold">Kg</span>
                                </div>
                            </div>
                            @error('weight')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Entregado Por -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-user text-soft-green-500 mr-2"></i>
                                Entregado Por *
                            </label>
                            <input type="text" name="delivered_by" 
                                   class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('delivered_by') border-red-500 @enderror" 
                                   placeholder="Nombre del entregador" 
                                   value="{{ old('delivered_by') }}" required>
                            @error('delivered_by')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tercera fila -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Recibido Por -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-user-check text-soft-green-500 mr-2"></i>
                                Recibido Por *
                            </label>
                            <input type="text" name="received_by" 
                                   class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('received_by') border-red-500 @enderror" 
                                   placeholder="Nombre del receptor" 
                                   value="{{ old('received_by', Auth::user()->name) }}" required>
                            @error('received_by')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Imagen -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-image text-soft-green-500 mr-2"></i>
                                Imagen (Opcional)
                            </label>
                            <div class="relative">
                                <input type="file" name="img" id="imageInput" 
                                       class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('img') border-red-500 @enderror" 
                                       accept="image/*" onchange="previewImage(this)">
                                <div id="imagePreview" class="mt-4 hidden">
                                    <div class="relative inline-block">
                                        <img id="previewImg" class="w-32 h-32 object-cover rounded-xl border-2 border-gray-300 shadow-lg" alt="Preview">
                                        <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('img')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Tamaño máximo: 2MB. Formatos: JPEG, PNG, JPG, GIF
                            </p>
                        </div>
                    </div>

                    <!-- Notas -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-sticky-note text-soft-green-500 mr-2"></i>
                            Notas Adicionales
                        </label>
                        <textarea name="notes" 
                                  class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 resize-none @error('notes') border-red-500 @enderror" 
                                  rows="4" placeholder="Ingrese notas adicionales sobre el residuo orgánico...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-300">
                        <a href="{{ route('admin.organic.index') }}" 
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
</script>
@endsection

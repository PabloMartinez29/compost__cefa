@extends('layouts.masteraprendiz')

@section('content')
@vite(['resources/css/waste.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-plus waste-icon"></i>
                    Registrar Residuo Orgánico
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-graduate text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Nuevo Registro
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="waste-container animate-fade-in-up animate-delay-2">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit text-green-600 mr-2"></i>
                Formulario de Registro
            </h2>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('aprendiz.organic.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Fecha -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-green-500 mr-2"></i>
                        Fecha del Registro *
                    </label>
                    <input type="date" name="date" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('date') border-red-500 @enderror"
                           value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo de Residuo -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-recycle text-green-500 mr-2"></i>
                        Tipo de Residuo *
                    </label>
                    <select name="type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('type') border-red-500 @enderror" 
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
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Peso -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-weight text-green-500 mr-2"></i>
                        Peso (Kilogramos) *
                    </label>
                    <input type="number" name="weight" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('weight') border-red-500 @enderror" 
                           placeholder="0.00" step="0.01" min="0.01" 
                           value="{{ old('weight') }}" required>
                    @error('weight')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Entregado Por -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-green-500 mr-2"></i>
                        Entregado Por *
                    </label>
                    <input type="text" name="delivered_by" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('delivered_by') border-red-500 @enderror" 
                           placeholder="Nombre del entregador" 
                           value="{{ old('delivered_by') }}" required>
                    @error('delivered_by')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Recibido Por -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user-check text-green-500 mr-2"></i>
                        Recibido Por *
                    </label>
                    <input type="text" name="received_by" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('received_by') border-red-500 @enderror" 
                           placeholder="Nombre del receptor" 
                           value="{{ old('received_by', Auth::user()->name) }}" required>
                    @error('received_by')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Imagen -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-image text-green-500 mr-2"></i>
                        Imagen (Obligatoria)
                    </label>
                    <div class="relative">
                        <input type="file" name="img" id="imageInput" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('img') border-red-500 @enderror" 
                               accept="image/*" onchange="previewImage(this)" required>
                        <div id="imagePreview" class="mt-3 hidden">
                            <img id="previewImg" class="w-32 h-32 object-cover rounded-lg border border-gray-200" alt="Preview">
                        </div>
                    </div>
                    @error('img')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Tamaño máximo: 2MB. Formatos: JPEG, PNG, JPG, GIF</p>
                </div>

                <!-- Notas Adicionales -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-green-500 mr-2"></i>
                        Notas Adicionales
                    </label>
                    <textarea name="notes" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 resize-none @error('notes') border-red-500 @enderror" 
                              rows="4" placeholder="Ingrese notas adicionales sobre el residuo orgánico...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-300">
                <a href="{{ route('aprendiz.organic.index') }}" 
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
</script>
@endsection

@extends('layouts.masteraprendiz')

@section('content')
@vite(['resources/css/waste.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-edit waste-icon"></i>
                    Editar Pila de Compostaje
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-graduate text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Pila {{ $composting->formatted_pile_num }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="waste-container animate-fade-in-up animate-delay-2">
        <form action="{{ route('aprendiz.composting.update', $composting) }}" method="POST" id="compostingForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Datos Generales -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-green-600 mr-2"></i>
                    Datos Generales de la Pila
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Número de Pila -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Número de Pila *</label>
                        <input type="number" name="pile_num" id="pile_num" 
                               class="waste-form-input @error('pile_num') border-red-500 @enderror" 
                               placeholder="Ej: 1, 2, 3..." 
                               value="{{ old('pile_num', $composting->pile_num) }}" required>
                        @error('pile_num')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Inicio -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha de Inicio *</label>
                        <input type="date" name="start_date" id="start_date" 
                               class="waste-form-input @error('start_date') border-red-500 @enderror" 
                               value="{{ old('start_date', $composting->start_date->format('Y-m-d')) }}" required>
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Fin -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha de Fin</label>
                        <input type="date" name="end_date" id="end_date" 
                               class="waste-form-input @error('end_date') border-red-500 @enderror" 
                               value="{{ old('end_date', $composting->end_date ? $composting->end_date->format('Y-m-d') : ($composting->status === 'Completada' && $composting->start_date ? $composting->start_date->copy()->addDays(44)->format('Y-m-d') : '')) }}">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Opcional - Dejar vacío si está en proceso</p>
                    </div>

                    <!-- Peso Total -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Kilogramos Beneficiados</label>
                        <input type="number" name="total_kg" id="total_kg" 
                               class="waste-form-input @error('total_kg') border-red-500 @enderror" 
                               placeholder="0.00" step="0.01" min="0.01" 
                               value="{{ old('total_kg', $composting->total_kg) }}">
                        @error('total_kg')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Opcional - Kilogramos beneficiados (se registra al finalizar la pila)</p>
                    </div>

                    <!-- Eficiencia -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Eficiencia (%)</label>
                        <input type="number" name="efficiency" id="efficiency" 
                               class="waste-form-input @error('efficiency') border-red-500 @enderror" 
                               placeholder="0.00" step="0.01" min="0" max="100" 
                               value="{{ old('efficiency', $composting->efficiency) }}">
                        @error('efficiency')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Opcional - Porcentaje de eficiencia</p>
                    </div>

                    <!-- Imagen de la Pila -->
                    <div class="waste-form-group md:col-span-3">
                        <label class="waste-form-label">Imagen de la Pila</label>
                        @if($composting->image)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Imagen actual:</p>
                                <div class="relative inline-block">
                                    <img src="{{ asset('storage/'.$composting->image) }}" alt="Imagen actual" class="w-32 h-32 object-cover rounded-xl border-2 border-gray-300 shadow-lg">
                                    <button type="button" onclick="removeCurrentImage()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="remove_image" id="remove_image" value="0">
                            </div>
                        @endif
                        <div class="relative">
                            <input type="file" name="image" id="image" 
                                   class="waste-form-input @error('image') border-red-500 @enderror" 
                                   accept="image/*" onchange="previewImage(this)">
                            <div id="imagePreview" class="mt-4 hidden">
                                <p class="text-sm font-medium text-gray-600 mb-2">Nueva imagen:</p>
                                <div class="relative inline-block">
                                    <img id="previewImg" class="w-32 h-32 object-cover rounded-xl border-2 border-gray-300 shadow-lg" alt="Preview">
                                    <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ingredientes -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-leaf text-green-600 mr-2"></i>
                            Ingredientes de la Pila
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Total: <span id="ingredientCount" class="font-semibold text-green-600">{{ $composting->ingredients->count() }}</span> ingredientes</p>
                        <p class="text-sm text-yellow-600 mt-1 flex items-center">
                            <i class="fas fa-lock mr-1"></i>
                            Los ingredientes no se pueden modificar una vez creada la pila
                        </p>
                    </div>
                </div>

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div id="ingredients-container" class="space-y-4">
                    <!-- Los ingredientes se cargarán dinámicamente aquí -->
                </div>

                @error('ingredients')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Resumen de Ingredientes -->
            <div class="mb-8 bg-green-50 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calculator text-green-600 mr-2"></i>
                    Resumen de Ingredientes
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg p-4 border border-green-200">
                        <div class="text-sm font-medium text-gray-600">Total de Ingredientes</div>
                        <div class="text-2xl font-bold text-green-600" id="totalIngredientsCount">{{ $composting->ingredients->count() }}</div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-green-200">
                        <div class="text-sm font-medium text-gray-600">Total de Kilogramos</div>
                        <div class="text-2xl font-bold text-green-600" id="totalKilograms">{{ number_format($composting->ingredients->sum('amount'), 2) }} Kg</div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-green-200">
                        <div class="text-sm font-medium text-gray-600">Promedio por Ingrediente</div>
                        <div class="text-2xl font-bold text-green-600" id="averagePerIngredient">{{ $composting->ingredients->count() > 0 ? number_format($composting->ingredients->avg('amount'), 2) : '0.00' }} Kg</div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('aprendiz.composting.index') }}" 
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

<script>
let ingredientIndex = 0;

// Datos de residuos orgánicos disponibles
const availableOrganics = @json($availableOrganics);

// Ingredientes existentes con información del residuo orgánico
const existingIngredients = @json($existingIngredients);

function addIngredient(ingredient = null) {
    const container = document.getElementById('ingredients-container');
    const ingredientDiv = document.createElement('div');
    ingredientDiv.className = 'ingredient-item bg-gray-50 p-4 rounded-lg border border-gray-200';
    
    const organicId = ingredient ? ingredient.organic_id : '';
    const amount = ingredient ? ingredient.amount : '';
    const notes = ingredient ? ingredient.notes : '';
    
    // Obtener el nombre del residuo orgánico para mostrar
    const organicName = ingredient && ingredient.organic ? ingredient.organic.type_in_spanish : '';
    const displayAmount = amount ? parseFloat(amount).toFixed(2) : '';
    const displayNotes = notes || '';
    
    ingredientDiv.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-800">Ingrediente ${ingredientIndex + 1}</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="waste-form-group">
                <label class="waste-form-label">Residuo Orgánico *</label>
                <input type="hidden" name="ingredients[${ingredientIndex}][organic_id]" value="${organicId}">
                <input type="text" 
                       class="waste-form-input bg-gray-100 cursor-not-allowed" 
                       value="${organicName}" 
                       readonly>
            </div>
            
            <div class="waste-form-group">
                <label class="waste-form-label">Cantidad (Kg) *</label>
                <input type="hidden" name="ingredients[${ingredientIndex}][amount]" value="${amount}">
                <input type="text" 
                       class="waste-form-input bg-gray-100 cursor-not-allowed" 
                       value="${displayAmount}" 
                       readonly>
            </div>
            
            <div class="waste-form-group">
                <label class="waste-form-label">Notas</label>
                <input type="hidden" name="ingredients[${ingredientIndex}][notes]" value="${displayNotes}">
                <input type="text" 
                       class="waste-form-input bg-gray-100 cursor-not-allowed" 
                       value="${displayNotes}" 
                       readonly>
            </div>
        </div>
    `;
    
    container.appendChild(ingredientDiv);
    ingredientIndex++;
    updateIngredientCount();
    
    // No se necesita event listener porque los campos están bloqueados
}

function removeIngredient(button) {
    // Los ingredientes no se pueden eliminar en edición
    return false;
}

function updateIngredientCount() {
    const count = document.querySelectorAll('.ingredient-item').length;
    document.getElementById('ingredientCount').textContent = count;
    updateSummary();
}

function updateSummary() {
    const ingredients = document.querySelectorAll('.ingredient-item');
    let totalKg = 0;
    
    ingredients.forEach(ingredient => {
        const hiddenAmountInput = ingredient.querySelector('input[type="hidden"][name*="[amount]"]');
        if (hiddenAmountInput && hiddenAmountInput.value) {
            totalKg += parseFloat(hiddenAmountInput.value) || 0;
        }
    });
    
    const count = ingredients.length;
    const average = count > 0 ? totalKg / count : 0;
    
    // Actualizar contadores
    document.getElementById('totalIngredientsCount').textContent = count;
    document.getElementById('totalKilograms').textContent = totalKg.toFixed(2) + ' Kg';
    document.getElementById('averagePerIngredient').textContent = average.toFixed(2) + ' Kg';
}

// Cargar ingredientes existentes al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    if (existingIngredients.length > 0) {
        existingIngredients.forEach(ingredient => {
            addIngredient(ingredient);
        });
    }
    updateIngredientCount();
});

// Validación del formulario
document.getElementById('compostingForm').addEventListener('submit', function(e) {
    const ingredients = document.querySelectorAll('.ingredient-item');
    if (ingredients.length === 0) {
        e.preventDefault();
        Swal.fire({
            title: 'Error',
            text: 'Debe agregar al menos un ingrediente',
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
        return;
    }
});

// Función para previsualizar imagen
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
    const input = document.getElementById('image');
    const preview = document.getElementById('imagePreview');
    
    input.value = '';
    preview.classList.add('hidden');
}

function removeCurrentImage() {
    document.getElementById('remove_image').value = '1';
    document.querySelector('.relative.inline-block img').parentElement.parentElement.style.display = 'none';
}
</script>
@endsection

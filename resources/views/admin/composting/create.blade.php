@extends('layouts.master')

@section('title', 'Crear Nueva Pila - Administrador')

@section('content')
@vite(['resources/css/waste.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-plus-circle waste-icon"></i>
                    Nueva Pila de Compostaje
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Registro de Nueva Pila
                </p>
            </div>
            <div class="text-right">
                <a href="{{ route('admin.composting.index') }}" class="bg-gray-400 text-gray-800 border border-gray-500 hover:bg-gray-500 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="waste-container animate-fade-in-up animate-delay-2">
        <form action="{{ route('admin.composting.store') }}" method="POST" id="compostingForm">
            @csrf
            
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
                               value="{{ old('pile_num') }}" required>
                        @error('pile_num')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Inicio -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha de Inicio *</label>
                        <input type="date" name="start_date" id="start_date" 
                               class="waste-form-input @error('start_date') border-red-500 @enderror" 
                               value="{{ old('start_date', date('Y-m-d')) }}" required>
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Fin -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha de Fin</label>
                        <input type="date" name="end_date" id="end_date" 
                               class="waste-form-input @error('end_date') border-red-500 @enderror" 
                               value="{{ old('end_date') }}">
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
                               value="{{ old('total_kg') }}">
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
                               value="{{ old('efficiency') }}">
                        @error('efficiency')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Opcional - Porcentaje de eficiencia</p>
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
                        <p class="text-sm text-gray-600 mt-1">Total: <span id="ingredientCount" class="font-semibold text-green-600">0</span> ingredientes</p>
                    </div>
                    <button type="button" onclick="addIngredient()" class="bg-green-400 text-green-800 border border-green-500 hover:bg-green-500 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Agregar Ingrediente
                    </button>
                </div>

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div id="ingredients-container" class="space-y-4">
                    <!-- Los ingredientes se agregarán dinámicamente aquí -->
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
                        <div class="text-2xl font-bold text-green-600" id="totalIngredientsCount">0</div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-green-200">
                        <div class="text-sm font-medium text-gray-600">Total de Kilogramos</div>
                        <div class="text-2xl font-bold text-green-600" id="totalKilograms">0.00 Kg</div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-green-200">
                        <div class="text-sm font-medium text-gray-600">Promedio por Ingrediente</div>
                        <div class="text-2xl font-bold text-green-600" id="averagePerIngredient">0.00 Kg</div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.composting.index') }}" class="waste-btn-secondary">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" class="waste-btn">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Pila
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let ingredientIndex = 0;

// Datos de residuos orgánicos disponibles
const availableOrganics = @json($availableOrganics);

function addIngredient() {
    const container = document.getElementById('ingredients-container');
    const ingredientDiv = document.createElement('div');
    ingredientDiv.className = 'ingredient-item bg-gray-50 p-4 rounded-lg border border-gray-200';
    ingredientDiv.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-800">Ingrediente ${ingredientIndex + 1}</h3>
            <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="waste-form-group">
                <label class="waste-form-label">Residuo Orgánico *</label>
                <select name="ingredients[${ingredientIndex}][organic_id]" 
                        class="waste-form-select @error('ingredients.*.organic_id') border-red-500 @enderror" required>
                    <option value="">Seleccionar residuo</option>
                    ${availableOrganics.map(organic => `
                        <option value="${organic.id}">${organic.type_in_spanish} - ${organic.formatted_weight} (Disponible: ${organic.available_quantity} Kg)</option>
                    `).join('')}
                </select>
            </div>
            
            <div class="waste-form-group">
                <label class="waste-form-label">Cantidad (Kg) *</label>
                <input type="number" name="ingredients[${ingredientIndex}][amount]" 
                       class="waste-form-input @error('ingredients.*.amount') border-red-500 @enderror" 
                       placeholder="0.00" step="0.01" min="0.01" required>
            </div>
            
            <div class="waste-form-group">
                <label class="waste-form-label">Notas</label>
                <input type="text" name="ingredients[${ingredientIndex}][notes]" 
                       class="waste-form-input" 
                       placeholder="Notas adicionales (opcional)">
            </div>
        </div>
    `;
    
    container.appendChild(ingredientDiv);
    ingredientIndex++;
    updateIngredientCount();
    
    // Agregar event listener para actualizar el resumen cuando cambie la cantidad
    const amountInput = ingredientDiv.querySelector('input[name*="[amount]"]');
    if (amountInput) {
        amountInput.addEventListener('input', updateSummary);
    }
}

function removeIngredient(button) {
    button.closest('.ingredient-item').remove();
    updateIngredientCount();
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
        const amountInput = ingredient.querySelector('input[name*="[amount]"]');
        if (amountInput && amountInput.value) {
            totalKg += parseFloat(amountInput.value) || 0;
        }
    });
    
    const count = ingredients.length;
    const average = count > 0 ? totalKg / count : 0;
    
    // Actualizar contadores
    document.getElementById('totalIngredientsCount').textContent = count;
    document.getElementById('totalKilograms').textContent = totalKg.toFixed(2) + ' Kg';
    document.getElementById('averagePerIngredient').textContent = average.toFixed(2) + ' Kg';
}

// Agregar primer ingrediente al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    addIngredient();
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
</script>
@endsection

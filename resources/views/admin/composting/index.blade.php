@extends('layouts.master')

@section('content')

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-6 border border-green-200 shadow-sm mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-mountain text-green-500 mr-3"></i>
                    Registro de Pilas de Compostaje
                </h1>
                <p class="text-gray-600 text-lg flex items-center">
                    <i class="fas fa-user-shield text-green-500 mr-2"></i>
                    {{ Auth::user()->name }} - Gestión de Pilas
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-600 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Pilas -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-400 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Pilas</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $compostings->total() }}</div>
                </div>
                <div class="text-3xl text-blue-500">
                    <i class="fas fa-mountain"></i>
                </div>
            </div>
        </div>

        <!-- Pilas Activas -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-400 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Pilas Activas</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $compostings->whereNull('end_date')->count() }}</div>
                </div>
                <div class="text-3xl text-green-500">
                    <i class="fas fa-play-circle"></i>
                </div>
            </div>
        </div>

        <!-- Pilas Completadas -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-400 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Completadas</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $compostings->whereNotNull('end_date')->count() }}</div>
                </div>
                <div class="text-3xl text-yellow-500">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Total Ingredientes -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-cyan-400 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Ingredientes</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $compostings->sum(function($composting) { return $composting->ingredients->count(); }) }}</div>
                </div>
                <div class="text-3xl text-cyan-500">
                    <i class="fas fa-leaf"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-green-500 mr-3"></i>
                Pilas de Compostaje Registradas
            </h2>
            <a href="{{ route('admin.composting.create') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl hover:from-green-600 hover:to-green-700">
                <i class="fas fa-plus mr-2"></i>
                Nueva Pila
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full bg-white rounded-lg shadow-sm overflow-hidden">
                <thead>
                    <tr class="bg-green-50">
                        <th class="text-green-800 font-semibold py-4 px-6 text-left border-b border-green-200">Pila</th>
                        <th class="text-green-800 font-semibold py-4 px-6 text-left border-b border-green-200">Fecha Inicio</th>
                        <th class="text-green-800 font-semibold py-4 px-6 text-left border-b border-green-200">Fecha Fin</th>
                        <th class="text-green-800 font-semibold py-4 px-6 text-left border-b border-green-200">Kilogramos Beneficiados</th>
                        <th class="text-green-800 font-semibold py-4 px-6 text-left border-b border-green-200">Eficiencia</th>
                        <th class="text-green-800 font-semibold py-4 px-6 text-left border-b border-green-200">Ingredientes</th>
                        <th class="text-green-800 font-semibold py-4 px-6 text-left border-b border-green-200">Estado</th>
                        <th class="text-green-800 font-semibold py-4 px-6 text-left border-b border-green-200">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compostings as $composting)
                        <tr class="hover:bg-green-50 transition-colors duration-200">
                            <td class="py-4 px-6 border-b border-gray-100 font-mono text-lg font-semibold text-gray-800">{{ $composting->formatted_pile_num }}</td>
                            <td class="py-4 px-6 border-b border-gray-100 text-gray-700">{{ $composting->formatted_start_date }}</td>
                            <td class="py-4 px-6 border-b border-gray-100 text-gray-700">{{ $composting->formatted_end_date }}</td>
                            <td class="py-4 px-6 border-b border-gray-100 font-semibold">
                                @if($composting->total_kg)
                                    <span class="text-green-600">{{ $composting->formatted_total_kg }}</span>
                                @elseif($composting->end_date)
                                    <span class="text-gray-400">No registrado</span>
                                @else
                                    <span class="text-yellow-600 font-medium">En proceso</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 border-b border-gray-100 text-gray-700">{{ $composting->formatted_efficiency }}</td>
                            <td class="py-4 px-6 border-b border-gray-100">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-list mr-2"></i>
                                    {{ $composting->ingredients->count() }} ingredientes
                                </span>
                            </td>
                            <td class="py-4 px-6 border-b border-gray-100">
                                @if($composting->end_date)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-2"></i>
                                        Completada
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-2"></i>
                                        En Proceso
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 border-b border-gray-100">
                                <div class="flex space-x-2 items-center">
                                    <button onclick="openViewModal({{ $composting->id }})" 
                                       class="inline-flex items-center justify-center w-10 h-10 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all duration-200" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <a href="{{ route('admin.composting.edit', $composting) }}" 
                                       class="inline-flex items-center justify-center w-10 h-10 text-green-500 hover:text-green-700 hover:bg-green-50 rounded-lg transition-all duration-200" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button onclick="confirmDelete({{ $composting->id }})" 
                                       class="inline-flex items-center justify-center w-10 h-10 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-mountain text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-semibold text-gray-600 mb-2">No hay pilas registradas</h3>
                                    <p class="text-gray-500">Comienza creando tu primera pila de compostaje</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($compostings->hasPages())
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    Mostrando {{ $compostings->firstItem() }} a {{ $compostings->lastItem() }} de {{ $compostings->total() }} resultados
                </div>
                <div class="flex space-x-2">
                    {{ $compostings->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal para ver detalles -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-t-xl p-6 border-b border-green-200">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-gray-800 mb-2 flex items-center justify-center">
                    <i class="fas fa-eye text-green-500 mr-3"></i>
                    Detalles de la Pila de Compostaje
                </h3>
                <p class="text-gray-600 text-lg flex items-center justify-center">
                    <i class="fas fa-user-shield text-green-500 mr-2"></i>
                    <span id="viewPileInfo">Pila #<span id="viewPileNum"></span></span>
                </p>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="space-y-8">
                <!-- Información General con diseño mejorado -->
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-info-circle text-green-600 mr-3"></i>
                        Información General
                    </h3>
                    
                    <!-- Primera fila - Información básica -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <!-- Número de Pila -->
                        <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-hashtag text-soft-green-600 mr-2"></i>
                                <span class="text-sm font-medium text-soft-gray-600">Número de Pila</span>
                            </div>
                            <div class="text-xl font-bold text-soft-green-800 font-mono" id="viewPileNumber"></div>
                        </div>

                        <!-- Fecha de Inicio -->
                        <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-calendar-plus text-soft-green-600 mr-2"></i>
                                <span class="text-sm font-medium text-soft-gray-600">Fecha de Inicio</span>
                            </div>
                            <div class="text-lg font-semibold text-soft-green-800" id="viewStartDate"></div>
                        </div>

                        <!-- Fecha de Fin -->
                        <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-calendar-check text-soft-green-600 mr-2"></i>
                                <span class="text-sm font-medium text-soft-gray-600">Fecha de Fin</span>
                            </div>
                            <div class="text-lg font-semibold text-soft-green-800" id="viewEndDate"></div>
                        </div>
                    </div>

                    <!-- Segunda fila - Métricas importantes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Kilogramos Beneficiados -->
                        <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-weight text-soft-green-600 mr-2"></i>
                                <span class="text-sm font-medium text-soft-gray-600">Kg Beneficiados</span>
                            </div>
                            <div class="text-lg font-bold text-soft-green-800" id="viewTotalKg"></div>
                        </div>

                        <!-- Eficiencia -->
                        <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-percentage text-soft-green-600 mr-2"></i>
                                <span class="text-sm font-medium text-soft-gray-600">Eficiencia</span>
                            </div>
                            <div class="text-lg font-bold text-soft-green-800" id="viewEfficiency"></div>
                        </div>

                        <!-- Total Ingredientes -->
                        <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-leaf text-soft-green-600 mr-2"></i>
                                <span class="text-sm font-medium text-soft-gray-600">Total Ingredientes</span>
                            </div>
                            <div class="text-lg font-bold text-soft-green-800" id="viewTotalIngredients"></div>
                        </div>

                        <!-- Total Kg Ingredientes -->
                        <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-balance-scale text-soft-green-600 mr-2"></i>
                                <span class="text-sm font-medium text-soft-gray-600">Total Kg Ingredientes</span>
                            </div>
                            <div class="text-lg font-bold text-soft-green-800" id="viewTotalIngredientKg"></div>
                        </div>
                    </div>
                </div>

                <!-- Ingredientes con diseño mejorado -->
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-leaf text-green-600 mr-3"></i>
                        Ingredientes de la Pila
                    </h3>
                    <div id="viewIngredients" class="space-y-4"></div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button onclick="closeViewModal()" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-8 py-3 rounded-xl transition-all duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-times mr-2"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function openViewModal(compostingId) {
    console.log('Opening modal for composting ID:', compostingId);
    
    // Mostrar modal inmediatamente con datos de prueba
    document.getElementById('viewPileNum').textContent = 'P-' + compostingId.toString().padStart(3, '0');
    document.getElementById('viewPileNumber').textContent = 'P-' + compostingId.toString().padStart(3, '0');
    document.getElementById('viewStartDate').textContent = 'Cargando...';
    document.getElementById('viewEndDate').textContent = 'Cargando...';
    document.getElementById('viewTotalKg').textContent = 'Cargando...';
    document.getElementById('viewEfficiency').textContent = 'Cargando...';
    document.getElementById('viewTotalIngredients').textContent = 'Cargando...';
    document.getElementById('viewTotalIngredientKg').textContent = 'Cargando...';
    
    // Mostrar modal
    document.getElementById('viewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Intentar cargar datos
    fetch(`/admin/composting/${compostingId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            // Llenar información general
            document.getElementById('viewPileNum').textContent = data.formatted_pile_num || 'N/A';
            document.getElementById('viewPileNumber').textContent = data.formatted_pile_num || 'N/A';
            document.getElementById('viewStartDate').textContent = data.formatted_start_date || 'N/A';
            document.getElementById('viewEndDate').textContent = data.formatted_end_date || 'N/A';
            document.getElementById('viewTotalKg').textContent = data.formatted_total_kg || 'N/A';
            document.getElementById('viewEfficiency').textContent = data.formatted_efficiency || 'N/A';
            document.getElementById('viewTotalIngredients').textContent = data.formatted_total_ingredients || 'N/A';
            document.getElementById('viewTotalIngredientKg').textContent = data.formatted_total_ingredient_kg || 'N/A';
            
            console.log('Modal fields populated');
            
            // Llenar ingredientes
            const ingredientsContainer = document.getElementById('viewIngredients');
            ingredientsContainer.innerHTML = '';
            
            if (data.ingredients && data.ingredients.length > 0) {
                data.ingredients.forEach((ingredient, index) => {
                    const ingredientDiv = document.createElement('div');
                    ingredientDiv.className = 'bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden';
                    ingredientDiv.innerHTML = `
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-leaf text-green-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-lg">${ingredient.ingredient_name}</h4>
                                        <p class="text-sm text-gray-500">Ingrediente #${index + 1}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="bg-gradient-to-r from-green-100 to-green-200 px-4 py-2 rounded-full">
                                        <span class="text-green-800 font-bold text-lg">${ingredient.formatted_amount}</span>
                                    </div>
                                </div>
                            </div>
                            ${ingredient.notes ? `
                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-sticky-note text-gray-400 mr-2 mt-1"></i>
                                        <p class="text-sm text-gray-600">${ingredient.notes}</p>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    `;
                    ingredientsContainer.appendChild(ingredientDiv);
                });
            } else {
                ingredientsContainer.innerHTML = `
                    <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                        <i class="fas fa-leaf text-6xl text-gray-300 mb-4 block"></i>
                        <h3 class="text-lg font-semibold text-gray-500 mb-2">No hay ingredientes registrados</h3>
                        <p class="text-gray-400">Esta pila no tiene ingredientes asociados</p>
                    </div>
                `;
            }
            
            // Mostrar modal
            document.getElementById('viewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            console.error('Error details:', error.message);
            // En lugar de mostrar alerta, mostrar datos de prueba
            document.getElementById('viewStartDate').textContent = 'Error al cargar';
            document.getElementById('viewEndDate').textContent = 'Error al cargar';
            document.getElementById('viewTotalKg').textContent = 'Error al cargar';
            document.getElementById('viewEfficiency').textContent = 'Error al cargar';
            document.getElementById('viewTotalIngredients').textContent = 'Error al cargar';
            document.getElementById('viewTotalIngredientKg').textContent = 'Error al cargar';
            
            // Mostrar ingredientes de prueba
            const ingredientsContainer = document.getElementById('viewIngredients');
            ingredientsContainer.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-exclamation-triangle text-4xl mb-2 block"></i><p>Error al cargar ingredientes</p></div>';
        });
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function confirmDelete(compostingId) {
    Swal.fire({
        title: '¿Eliminar pila?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear formulario para eliminar
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/composting/${compostingId}`;
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            const tokenField = document.createElement('input');
            tokenField.type = 'hidden';
            tokenField.name = '_token';
            tokenField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            form.appendChild(methodField);
            form.appendChild(tokenField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Cerrar modal al hacer clic fuera
document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeViewModal();
    }
});

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewModal();
    }
});
</script>
@endsection
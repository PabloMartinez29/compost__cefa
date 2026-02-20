@extends('layouts.masteraprendiz')
@vite(['resources/css/waste.css'])

@section('content')
<div class="min-h-screen bg-soft-gray-50 py-4 sm:py-8">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-soft-green-500 to-soft-green-600 rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                    <i class="fas fa-warehouse text-white text-xl"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-3xl font-bold text-soft-gray-900">Bodega de Clasificación</h1>
                    <p class="text-sm sm:text-base text-soft-gray-600">Inventario general de residuos orgánicos</p>
                </div>
            </div>
        </div>

        <!-- Total Inventory Summary -->
        @php
            $totalInventory = array_sum(array_map(function($balance) { return max(0, $balance); }, $inventory));
        @endphp
        <div class="bg-gradient-to-r from-soft-green-100 to-soft-green-200 rounded-2xl p-6 mb-8 border border-soft-green-300">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-soft-gray-900">Inventario Total</h2>
                    <p class="text-soft-gray-600">Residuos orgánicos clasificados</p>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold text-soft-green-700">
                        {{ number_format($totalInventory, 1) }} kg
                    </div>
                    <div class="text-sm text-soft-gray-600">Total en bodega</div>
                </div>
            </div>
        </div>

        <!-- Inventory Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @php
                $types = [
                    'Kitchen' => ['name' => 'Cocina', 'icon' => 'fa-utensils', 'color' => 'from-orange-200 to-orange-300', 'border' => 'border-orange-200'],
                    'Beds' => ['name' => 'Camas', 'icon' => 'fa-bed', 'color' => 'from-blue-200 to-blue-300', 'border' => 'border-blue-200'],
                    'Leaves' => ['name' => 'Hojas', 'icon' => 'fa-leaf', 'color' => 'from-green-200 to-green-300', 'border' => 'border-green-200'],
                    'CowDung' => ['name' => 'Estiércol de Vaca', 'icon' => 'fa-cow', 'color' => 'from-yellow-200 to-yellow-300', 'border' => 'border-yellow-200'],
                    'ChickenManure' => ['name' => 'Estiércol de Pollo', 'icon' => 'fa-egg', 'color' => 'from-red-200 to-red-300', 'border' => 'border-red-200'],
                    'PigManure' => ['name' => 'Estiércol de Cerdo', 'icon' => 'fa-piggy-bank', 'color' => 'from-pink-200 to-pink-300', 'border' => 'border-pink-200'],
                    'Other' => ['name' => 'Otros', 'icon' => 'fa-box', 'color' => 'from-gray-200 to-gray-300', 'border' => 'border-gray-200']
                ];
            @endphp

            @foreach($types as $type => $info)
                @php
                    $currentBalance = $inventory[$type] ?? 0;
                    $isNegative = $currentBalance < 0;
                    $displayBalance = max(0, $currentBalance); // Mostrar 0 si es negativo
                @endphp
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 {{ $isNegative ? 'border-red-400' : $info['border'] }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br {{ $info['color'] }} rounded-xl flex items-center justify-center">
                                <i class="fas {{ $info['icon'] }} text-gray-600 text-lg"></i>
                            </div>
                            <span class="text-2xl font-bold {{ $isNegative ? 'text-red-600' : 'text-soft-gray-900' }}">
                                {{ number_format($displayBalance, 1) }} kg
                            </span>
                        </div>
                        @if($isNegative)
                        <div class="mb-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Balance Negativo
                            </span>
                        </div>
                        @endif
                        <h3 class="text-lg font-semibold text-soft-gray-900 mb-2">{{ $info['name'] }}</h3>
                        <a href="{{ route('aprendiz.warehouse.inventory', $type) }}" 
                           class="text-soft-green-600 hover:text-soft-green-700 font-medium text-sm flex items-center">
                            Ver detalles
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Recent Movements -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-soft-gray-200">
                <h2 class="text-xl font-semibold text-soft-gray-900 flex items-center">
                    <i class="fas fa-history mr-3 text-soft-green-600"></i>
                    Movimientos Recientes
                </h2>
            </div>
            <div class="p-6">
                @if($recentMovements->count() > 0)
                    <!-- Vista móvil: tarjetas -->
                    <div class="block lg:hidden space-y-4">
                        @foreach($recentMovements as $movement)
                            <div class="bg-soft-gray-50 border border-soft-gray-200 rounded-xl p-4">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="min-w-0">
                                        <p class="font-medium text-soft-gray-900">{{ $movement->formatted_date }}</p>
                                        <p class="text-sm text-soft-gray-600">{{ $movement->type_in_spanish }}</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $movement->movement_type === 'entry' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $movement->movement_type_in_spanish }}</span>
                                        <span class="ml-2 text-sm font-semibold">{{ $movement->formatted_weight }}</span>
                                        <p class="text-xs text-soft-gray-500 mt-1">{{ $movement->processed_by }}</p>
                                    </div>
                                    <button type="button" onclick="openMovementModal({{ $movement->id }})" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg flex-shrink-0" title="Ver detalles"><i class="fas fa-eye"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full min-w-[700px]">
                            <thead>
                                <tr class="text-left border-b border-soft-gray-200">
                                    <th class="pb-3 text-sm font-semibold text-soft-gray-700">Fecha</th>
                                    <th class="pb-3 text-sm font-semibold text-soft-gray-700">Tipo</th>
                                    <th class="pb-3 text-sm font-semibold text-soft-gray-700">Movimiento</th>
                                    <th class="pb-3 text-sm font-semibold text-soft-gray-700">Peso</th>
                                    <th class="pb-3 text-sm font-semibold text-soft-gray-700">Procesado por</th>
                                    <th class="pb-3 text-sm font-semibold text-soft-gray-700">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMovements as $movement)
                                    <tr class="border-b border-soft-gray-100">
                                        <td class="py-4 text-sm text-soft-gray-900">{{ $movement->formatted_date }}</td>
                                        <td class="py-4 text-sm text-soft-gray-900">{{ $movement->type_in_spanish }}</td>
                                        <td class="py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $movement->movement_type === 'entry' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $movement->movement_type_in_spanish }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-sm text-soft-gray-900">{{ $movement->formatted_weight }}</td>
                                        <td class="py-4 text-sm text-soft-gray-900">{{ $movement->processed_by }}</td>
                                        <td class="py-4">
                                            <button onclick="openMovementModal({{ $movement->id }})" 
                                                    class="text-blue-500 hover:text-blue-600 transition-colors duration-200"
                                                    title="Ver detalles">
                                                <i class="fas fa-eye text-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Paginación -->
                    <div class="mt-6">
                        {{ $recentMovements->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-warehouse text-soft-gray-300 text-4xl mb-4"></i>
                        <p class="text-soft-gray-500">No hay movimientos registrados</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles del Movimiento -->
<div id="movementModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-eye waste-icon"></i>
                    Detalles del Movimiento
                </h3>
                <p class="waste-subtitle">
                    <i class="fas fa-warehouse text-green-400 mr-2"></i>
                    <span id="viewMovementInfo">Movimiento #<span id="viewMovementId"></span></span>
                </p>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div id="modalContent">
                <!-- El contenido se cargará dinámicamente -->
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-green-500 text-3xl"></i>
                    <p class="text-gray-600 mt-4">Cargando...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openMovementModal(movementId) {
    document.getElementById('movementModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Cargar los detalles del movimiento
    fetch(`/aprendiz/warehouse/${movementId}`)
        .then(response => response.text())
        .then(html => {
            // Extraer solo el contenido del card de detalles
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const card = doc.querySelector('.bg-white.rounded-2xl');
            
            if (card) {
                // Extraer información del header
                const headerTitle = card.querySelector('h2');
                const movementIdText = headerTitle ? headerTitle.textContent.match(/#(\d+)/) : null;
                if (movementIdText) {
                    document.getElementById('viewMovementId').textContent = movementIdText[1].padStart(4, '0');
                }
                
                // Extraer tipo de movimiento del header
                const header = card.querySelector('.bg-gradient-to-r');
                let movementType = '';
                if (header) {
                    const movementTypeSpan = header.querySelector('span');
                    if (movementTypeSpan) {
                        // Extraer solo el texto sin el icono
                        const clone = movementTypeSpan.cloneNode(true);
                        const icon = clone.querySelector('i');
                        if (icon) icon.remove();
                        movementType = clone.textContent.trim();
                    }
                }
                
                // Extraer el contenido del card
                const content = card.querySelector('.p-6');
                if (content) {
                    // Convertir el contenido al formato del modal
                    const modalContent = convertToModalFormat(content, movementType);
                    document.getElementById('modalContent').innerHTML = modalContent;
                } else {
                    document.getElementById('modalContent').innerHTML = '<div class="text-center py-8"><p class="text-red-500">Error al cargar los detalles</p></div>';
                }
            } else {
                document.getElementById('modalContent').innerHTML = '<div class="text-center py-8"><p class="text-red-500">Error al cargar los detalles</p></div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalContent').innerHTML = '<div class="text-center py-8"><p class="text-red-500">Error al cargar los detalles</p></div>';
        });
}

function convertToModalFormat(content, movementType) {
    const grid = content.querySelector('.grid');
    if (!grid) return content.innerHTML;
    
    let html = '<div class="space-y-6">';
    
    // Información básica
    const basicInfo = grid.querySelector('.space-y-4');
    if (basicInfo) {
        const items = basicInfo.querySelectorAll('.space-y-3 > div');
        html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
        items.forEach(item => {
            const label = item.querySelector('label');
            const value = item.querySelector('p');
            if (label && value) {
                html += `
                    <div class="waste-form-group">
                        <label class="waste-form-label">${label.textContent}</label>
                        <div class="waste-form-input bg-gray-50">${value.textContent}</div>
                    </div>
                `;
            }
        });
        
        // Agregar tipo de movimiento
        if (movementType) {
            html += `
                <div class="waste-form-group">
                    <label class="waste-form-label">Tipo de Movimiento</label>
                    <div class="waste-form-input bg-gray-50">${movementType}</div>
                </div>
            `;
        }
        
        html += '</div>';
    }
    
    html += `
        <!-- Form Actions -->
        <div class="flex justify-end pt-6 border-t border-gray-200">
            <button onclick="closeMovementModal()" class="waste-btn-secondary">
                <i class="fas fa-times mr-2"></i>
                Cerrar
            </button>
        </div>
    </div>
    `;
    
    return html;
}

function closeMovementModal() {
    document.getElementById('movementModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Cerrar modal al hacer clic fuera
document.getElementById('movementModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMovementModal();
    }
});

// Cerrar modal con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMovementModal();
    }
});

// Modal de imagen (si se necesita)
function openImageModal(imageSrc) {
    // Crear modal de imagen si no existe
    let imageModal = document.getElementById('imageModal');
    if (!imageModal) {
        imageModal = document.createElement('div');
        imageModal.id = 'imageModal';
        imageModal.className = 'fixed inset-0 bg-black bg-opacity-75 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4';
        imageModal.innerHTML = `
            <div class="relative max-w-4xl max-h-[90vh] w-auto h-auto">
                <button onclick="closeImageModal()" class="absolute top-4 right-4 z-10 bg-black bg-opacity-50 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75 transition-all">
                    <i class="fas fa-times"></i>
                </button>
                <img id="modalImage" src="" alt="Imagen ampliada" class="max-w-full max-h-full rounded-lg shadow-2xl">
            </div>
        `;
        document.body.appendChild(imageModal);
    }
    document.getElementById('modalImage').src = imageSrc;
    imageModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Cerrar modal de imagen al hacer clic fuera
document.addEventListener('click', function(e) {
    const imageModal = document.getElementById('imageModal');
    if (imageModal && e.target === imageModal) {
        closeImageModal();
    }
});
</script>
@endsection

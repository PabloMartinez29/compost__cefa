@extends('layouts.masteraprendiz')
@vite(['resources/css/waste.css'])

@section('content')
<div class="container mx-auto px-4 py-2">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('aprendiz.warehouse.index') }}" 
                   class="text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="waste-title text-2xl">
                        <i class="fas fa-warehouse waste-icon"></i>
                        {{ $typeInSpanish[$type] }}
                    </h1>
                    <p class="waste-subtitle">
                        <i class="fas fa-boxes text-green-400 mr-2"></i>
                        Inventario y movimientos detallados
                    </p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold {{ $inventory < 0 ? 'text-red-600' : 'text-green-600' }}">
                    {{ number_format(max(0, $inventory), 1) }} kg
                </div>
                <div class="text-sm text-gray-600">Inventario actual</div>
            </div>
        </div>
    </div>

    <!-- Advertencia de Balance Negativo -->
    @if($inventory < 0)
    <div class="waste-card animate-fade-in-up mb-4" style="border-left-color: #ef4444; background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);">
        <div class="flex items-center">
            <div class="text-3xl text-red-500 mr-4">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-red-800 mb-1">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Advertencia: Balance Negativo
                </h3>
                <p class="text-red-700">
                    El inventario actual es <strong>{{ number_format($inventory, 2) }} kg</strong>, lo que indica que las salidas registradas exceden las entradas. 
                    Esto puede indicar un error en los registros. Por favor, verifique el historial de movimientos.
                </p>
                <p class="text-sm text-red-600 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Nota:</strong> No se podrán registrar nuevas salidas hasta que el balance sea positivo o igual a cero.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        @php
            $entries = $movements->where('movement_type', 'entry')->sum('weight');
            $exits = $movements->where('movement_type', 'exit')->sum('weight');
        @endphp
        
        <div class="waste-card waste-card-success animate-fade-in-up">
            <div class="flex items-center">
                <div class="waste-card-icon text-green-600">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Entradas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($entries, 1) }} kg</p>
                </div>
            </div>
        </div>

        <div class="waste-card animate-fade-in-up animate-delay-1" style="border-left-color: #fca5a5;">
            <div class="flex items-center">
                <div class="text-2xl text-red-500">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Salidas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($exits, 1) }} kg</p>
                </div>
            </div>
        </div>

        <div class="waste-card animate-fade-in-up animate-delay-2" style="border-left-color: {{ $inventory < 0 ? '#ef4444' : '#93c5fd' }};">
            <div class="flex items-center">
                <div class="text-2xl {{ $inventory < 0 ? 'text-red-500' : 'text-blue-500' }}">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Balance</p>
                    <p class="text-2xl font-bold {{ $inventory < 0 ? 'text-red-600' : 'text-gray-900' }}">{{ number_format(max(0, $inventory), 1) }} kg</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="waste-card animate-fade-in-up animate-delay-3">
        <div class="waste-card-header">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-list text-green-600 mr-3"></i>
                Historial de Movimientos
            </h2>
        </div>
        <div class="p-5">
            @if($movements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="waste-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Movimiento</th>
                                <th>Peso</th>
                                <th>Procesado por</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements as $movement)
                                <tr>
                                    <td>{{ $movement->formatted_date }}</td>
                                    <td>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $movement->movement_type === 'entry' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i class="fas {{ $movement->movement_type === 'entry' ? 'fa-arrow-down' : 'fa-arrow-up' }} mr-1"></i>
                                            {{ $movement->movement_type_in_spanish }}
                                        </span>
                                    </td>
                                    <td class="font-medium">{{ $movement->formatted_weight }}</td>
                                    <td>{{ $movement->processed_by }}</td>
                                    <td>
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
                <div class="mt-4">
                    {{ $movements->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-warehouse text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">No hay movimientos registrados para este tipo</p>
                </div>
            @endif
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
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const card = doc.querySelector('.bg-white.rounded-2xl');
            
            if (card) {
                const headerTitle = card.querySelector('h2');
                const movementIdText = headerTitle ? headerTitle.textContent.match(/#(\d+)/) : null;
                if (movementIdText) {
                    document.getElementById('viewMovementId').textContent = movementIdText[1].padStart(4, '0');
                }
                
                const header = card.querySelector('.bg-gradient-to-r');
                let movementType = '';
                if (header) {
                    const movementTypeSpan = header.querySelector('span');
                    if (movementTypeSpan) {
                        const clone = movementTypeSpan.cloneNode(true);
                        const icon = clone.querySelector('i');
                        if (icon) icon.remove();
                        movementType = clone.textContent.trim();
                    }
                }
                
                const content = card.querySelector('.p-6');
                if (content) {
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

document.getElementById('movementModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMovementModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMovementModal();
    }
});
</script>
@endsection

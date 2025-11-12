@extends('layouts.master')

@section('title', 'Lista de Mantenimientos')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-soft-gray-800 mb-2">
                <i class="fas fa-wrench text-soft-green-600 mr-3"></i>
                Registros de Mantenimiento
            </h1>
            <p class="text-soft-gray-600">Historial completo de mantenimientos y operaciones de maquinaria</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.machinery.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-soft-green-600 to-soft-green-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Nuevo Registro
            </a>
        </div>
    </div>

    <!-- Mensajes de estado -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-soft-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-soft-gray-600">Total Registros</p>
                    <p class="text-2xl font-bold text-soft-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border border-soft-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-wrench text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-soft-gray-600">Mantenimientos</p>
                    <p class="text-2xl font-bold text-soft-gray-900">{{ $stats['maintenance'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border border-soft-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-play text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-soft-gray-600">Operaciones</p>
                    <p class="text-2xl font-bold text-soft-gray-900">{{ $stats['operations'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-soft-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-soft-gray-600">Este Mes</p>
                    <p class="text-2xl font-bold text-soft-gray-900">{{ $stats['this_month'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros rápidos -->
    <div class="bg-white rounded-xl shadow-lg p-4 mb-6 border border-soft-gray-100">
        <div class="flex flex-wrap gap-3 items-center">
            <span class="text-sm font-medium text-soft-gray-700">Filtrar por:</span>
            <button onclick="filterByType('all')" 
                    class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                    data-type="all">
                <i class="fas fa-list mr-1"></i> Todos
            </button>
            <button onclick="filterByType('M')" 
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                    data-type="M">
                <i class="fas fa-wrench mr-1"></i> Mantenimientos
            </button>
            <button onclick="filterByType('O')" 
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                    data-type="O">
                <i class="fas fa-play mr-1"></i> Operaciones
            </button>
        </div>
    </div>

    <!-- Tabla de mantenimientos -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-soft-gray-100">
        @if($maintenances->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-soft-gray-200">
                    <thead class="bg-soft-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-soft-gray-600 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-soft-gray-600 uppercase tracking-wider">
                                Maquinaria
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-soft-gray-600 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-soft-gray-600 uppercase tracking-wider">
                                Responsable
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-soft-gray-600 uppercase tracking-wider">
                                Descripción
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-soft-gray-600 uppercase tracking-wider">
                                Registro
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-soft-gray-200">
                        @foreach($maintenances as $maintenance)
                            <tr class="hover:bg-soft-gray-50 transition-colors duration-150 maintenance-row" 
                                data-type="{{ $maintenance->type }}">
                                <!-- Fecha -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-soft-blue-100 rounded-lg mr-3">
                                            <i class="fas fa-calendar text-soft-blue-600"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-soft-gray-900">
                                                {{ $maintenance->date->format('d/m/Y') }}
                                            </div>
                                            <div class="text-sm text-soft-gray-500">
                                                {{ $maintenance->date->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Maquinaria -->
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-soft-gray-900">
                                            {{ $maintenance->machinery->name }}
                                        </div>
                                        <div class="text-sm text-soft-gray-500">
                                            {{ $maintenance->machinery->brand }} {{ $maintenance->machinery->model }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Tipo -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $typeConfig = match($maintenance->type) {
                                            'M' => [
                                                'class' => 'bg-red-100 text-red-800',
                                                'icon' => 'fas fa-wrench',
                                                'name' => 'Mantenimiento'
                                            ],
                                            'O' => [
                                                'class' => 'bg-green-100 text-green-800',
                                                'icon' => 'fas fa-play',
                                                'name' => 'Operación'
                                            ],
                                            default => [
                                                'class' => 'bg-gray-100 text-gray-800',
                                                'icon' => 'fas fa-question',
                                                'name' => 'Desconocido'
                                            ]
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeConfig['class'] }}">
                                        <i class="{{ $typeConfig['icon'] }} mr-1"></i>
                                        {{ $typeConfig['name'] }}
                                    </span>
                                </td>

                                <!-- Responsable -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-soft-gray-900 flex items-center">
                                        <i class="fas fa-user text-soft-gray-400 mr-2"></i>
                                        {{ $maintenance->responsible }}
                                    </div>
                                </td>

                                <!-- Descripción -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-soft-gray-900 max-w-xs">
                                        <p class="truncate" title="{{ $maintenance->description }}">
                                            {{ Str::limit($maintenance->description, 60) }}
                                        </p>
                                    </div>
                                </td>

                                <!-- Registro -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-soft-gray-500">
                                        {{ $maintenance->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($maintenances->hasPages())
                <div class="px-6 py-4 border-t border-soft-gray-200">
                    {{ $maintenances->links() }}
                </div>
            @endif
        @else
            <!-- Estado vacío -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-soft-gray-100 rounded-full mb-4">
                    <i class="fas fa-wrench text-2xl text-soft-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-soft-gray-900 mb-2">No hay registros de mantenimiento</h3>
                <p class="text-soft-gray-600 mb-6">Comienza registrando el primer mantenimiento u operación.</p>
                <a href="{{ route('admin.machinery.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-soft-green-600 text-white font-medium rounded-lg hover:bg-soft-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Registrar Primer Mantenimiento
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.filter-btn {
    background-color: #f3f4f6;
    color: #6b7280;
    border: 1px solid #e5e7eb;
}

.filter-btn:hover {
    background-color: #e5e7eb;
    color: #374151;
}

.filter-btn.active {
    background-color: #10b981;
    color: white;
    border-color: #10b981;
}
</style>

<script>
function filterByType(type) {
    const rows = document.querySelectorAll('.maintenance-row');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Actualizar botones
    buttons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.type === type) {
            btn.classList.add('active');
        }
    });
    
    // Filtrar filas
    rows.forEach(row => {
        if (type === 'all' || row.dataset.type === type) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Inicializar tooltips para descripciones largas
document.addEventListener('DOMContentLoaded', function() {
    const truncatedElements = document.querySelectorAll('[title]');
    truncatedElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            // Aquí podrías agregar un tooltip más elaborado si lo deseas
        });
    });
});
</script>
@endsection




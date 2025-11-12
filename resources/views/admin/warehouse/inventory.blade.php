@extends('layouts.master')

@section('content')
<div class="min-h-screen bg-soft-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.warehouse.index') }}" 
                       class="text-soft-gray-500 hover:text-soft-gray-700 transition-colors duration-200">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="w-12 h-12 bg-gradient-to-br from-soft-green-500 to-soft-green-600 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-warehouse text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-soft-gray-900">{{ $typeInSpanish[$type] }}</h1>
                        <p class="text-soft-gray-600">Inventario y movimientos detallados</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-soft-green-600">
                        {{ number_format($inventory, 1) }} kg
                    </div>
                    <div class="text-sm text-soft-gray-500">Inventario actual</div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @php
                $entries = $movements->where('movement_type', 'entry')->sum('weight');
                $exits = $movements->where('movement_type', 'exit')->sum('weight');
            @endphp
            
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-arrow-down text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-soft-gray-500">Entradas</p>
                        <p class="text-2xl font-bold text-soft-gray-900">{{ number_format($entries, 1) }} kg</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-arrow-up text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-soft-gray-500">Salidas</p>
                        <p class="text-2xl font-bold text-soft-gray-900">{{ number_format($exits, 1) }} kg</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-balance-scale text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-soft-gray-500">Balance</p>
                        <p class="text-2xl font-bold text-soft-gray-900">{{ number_format($inventory, 1) }} kg</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Movements Table -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-soft-gray-200">
                <h2 class="text-xl font-semibold text-soft-gray-900 flex items-center">
                    <i class="fas fa-list mr-3 text-soft-green-600"></i>
                    Historial de Movimientos
                </h2>
            </div>
            <div class="p-6">
                @if($movements->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b border-soft-gray-200">
                                    <th class="pb-3 text-sm font-semibold text-soft-gray-700">Fecha</th>
                                    <th class="pb-3 text-sm font-semibold text-soft-gray-700">Movimiento</th>
                                    <th class="pb-3 text-sm font-semibold text-soft-gray-700">Peso</th>
                                    <th class="pb-3 text-sm font-semibold text-soft-gray-700">Procesado por</th>
                                    <th class="pb-3 text-sm font-semibold text-soft-gray-700">Notas</th>
                                    <th class="pb-3 text-sm font-semibold text-soft-gray-700">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movements as $movement)
                                    <tr class="border-b border-soft-gray-100">
                                        <td class="py-4 text-sm text-soft-gray-900">{{ $movement->formatted_date }}</td>
                                        <td class="py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $movement->movement_type === 'entry' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                <i class="fas {{ $movement->movement_type === 'entry' ? 'fa-arrow-down' : 'fa-arrow-up' }} mr-1"></i>
                                                {{ $movement->movement_type_in_spanish }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-sm text-soft-gray-900 font-medium">
                                            {{ $movement->formatted_weight }}
                                        </td>
                                        <td class="py-4 text-sm text-soft-gray-900">{{ $movement->processed_by }}</td>
                                        <td class="py-4 text-sm text-soft-gray-900">
                                            @if($movement->notes)
                                                <span class="truncate max-w-xs block" title="{{ $movement->notes }}">
                                                    {{ Str::limit($movement->notes, 30) }}
                                                </span>
                                            @else
                                                <span class="text-soft-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.warehouse.show', $movement) }}" 
                                                   class="text-soft-green-600 hover:text-soft-green-700 text-sm font-medium">
                                                    Ver
                                                </a>
                                                <a href="" 
                                                   class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                                    Editar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-warehouse text-soft-gray-300 text-4xl mb-4"></i>
                        <p class="text-soft-gray-500">No hay movimientos registrados para este tipo</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

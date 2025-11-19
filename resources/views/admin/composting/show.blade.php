@extends('layouts.master')

@section('content')
@vite(['resources/css/waste.css'])

@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-eye waste-icon"></i>
                    Detalles de la Pila de Compostaje
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Pila {{ $composting->formatted_pile_num }}
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.composting.edit', $composting) }}" class="bg-green-400 text-green-800 border border-green-500 hover:bg-green-500 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('admin.composting.index') }}" class="bg-gray-400 text-gray-800 border border-gray-500 hover:bg-gray-500 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="waste-container animate-fade-in-up animate-delay-2">
        <!-- Imagen de la Pila -->
        @if($composting->image)
            <div class="mb-8 text-center">
                <img src="{{ Storage::url($composting->image) }}" 
                     alt="Pila {{ $composting->formatted_pile_num }}" 
                     class="max-w-full h-64 object-cover rounded-lg shadow-md mx-auto">
            </div>
        @endif
        
        <!-- Información General -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle text-green-600 mr-2"></i>
                Información General
            </h2>
            
            <!-- Primera fila - Información básica -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Número de Pila -->
                <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-hashtag text-soft-green-600 mr-2"></i>
                        <span class="text-sm font-medium text-soft-gray-600">Número de Pila</span>
                    </div>
                    <div class="text-xl font-bold text-soft-green-800 font-mono">{{ $composting->formatted_pile_num }}</div>
                </div>

                <!-- Fecha de Inicio -->
                <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-calendar-plus text-soft-green-600 mr-2"></i>
                        <span class="text-sm font-medium text-soft-gray-600">Fecha de Inicio</span>
                    </div>
                    <div class="text-lg font-semibold text-soft-green-800">{{ $composting->formatted_start_date }}</div>
                </div>

                <!-- Fecha de Fin -->
                <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-calendar-check text-soft-green-600 mr-2"></i>
                        <span class="text-sm font-medium text-soft-gray-600">Fecha de Fin</span>
                    </div>
                    <div class="text-lg font-semibold text-soft-green-800">{{ $composting->formatted_end_date }}</div>
                </div>
            </div>

            <!-- Segunda fila - Métricas importantes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Kilogramos Beneficiados -->
                <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-weight text-soft-green-600 mr-2"></i>
                        <span class="text-sm font-medium text-soft-gray-600">Kg Beneficiados</span>
                    </div>
                    <div class="text-lg font-bold text-soft-green-800">
                        @if($composting->total_kg)
                            {{ $composting->formatted_total_kg }}
                        @else
                            <span class="text-soft-gray-400">No registrado</span>
                        @endif
                    </div>
                </div>

                <!-- Eficiencia -->
                <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-percentage text-soft-green-600 mr-2"></i>
                        <span class="text-sm font-medium text-soft-gray-600">Eficiencia</span>
                    </div>
                    <div class="text-lg font-bold text-soft-green-800">{{ $composting->formatted_efficiency }}</div>
                </div>

                <!-- Total Ingredientes -->
                <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-leaf text-soft-green-600 mr-2"></i>
                        <span class="text-sm font-medium text-soft-gray-600">Total Ingredientes</span>
                    </div>
                    <div class="text-lg font-bold text-soft-green-800">{{ $composting->formatted_total_ingredients }}</div>
                </div>

                <!-- Total Kg Ingredientes -->
                <div class="bg-gradient-to-br from-soft-green-50 to-soft-green-100 p-4 rounded-xl border border-soft-green-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-balance-scale text-soft-green-600 mr-2"></i>
                        <span class="text-sm font-medium text-soft-gray-600">Total Kg Ingredientes</span>
                    </div>
                    <div class="text-lg font-bold text-soft-green-800">{{ number_format($composting->ingredients->sum('amount'), 2) }} Kg</div>
                </div>
            </div>

            <!-- Estado -->
            <div class="mb-6">
                <div class="bg-gradient-to-br from-soft-gray-50 to-soft-gray-100 p-4 rounded-xl border border-soft-gray-200 shadow-sm">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-info-circle text-soft-gray-600 mr-2"></i>
                        <span class="text-sm font-medium text-soft-gray-600">Estado de la Pila</span>
                    </div>
                    <div class="text-lg font-semibold">
                        @if($composting->end_date)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-soft-green-100 text-soft-green-800">
                                <i class="fas fa-check mr-2"></i>
                                Completada
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-2"></i>
                                En Proceso
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingredientes -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-leaf text-green-600 mr-2"></i>
                Ingredientes ({{ $composting->ingredients->count() }})
            </h2>
            
            @if($composting->ingredients->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($composting->ingredients as $index => $ingredient)
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center mr-4">
                                            <i class="fas fa-leaf text-green-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-lg">{{ $ingredient->ingredient_name }}</h4>
                                            <p class="text-sm text-gray-500">Ingrediente #{{ $index + 1 }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="bg-gradient-to-r from-green-100 to-green-200 px-4 py-2 rounded-full">
                                            <span class="text-green-800 font-bold text-lg">{{ $ingredient->formatted_amount }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($ingredient->notes)
                                    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-start">
                                            <i class="fas fa-sticky-note text-gray-400 mr-2 mt-1"></i>
                                            <p class="text-sm text-gray-600">{{ $ingredient->notes }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                    <i class="fas fa-leaf text-6xl text-gray-300 mb-4 block"></i>
                    <h3 class="text-lg font-semibold text-gray-500 mb-2">No hay ingredientes registrados</h3>
                    <p class="text-gray-400">Esta pila no tiene ingredientes asociados</p>
                </div>
            @endif
        </div>

        <!-- Seguimientos -->
        @if($composting->trackings->count() > 0)
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Seguimientos ({{ $composting->trackings->count() }})
                </h2>
                
                <div>
                    <table class="waste-table w-full">
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Fecha</th>
                                <th>Actividad</th>
                                <th>Temp. Interna</th>
                                <th>Temp. Ambiente</th>
                                <th>Humedad Pila</th>
                                <th>pH</th>
                                <th>Agua (L)</th>
                                <th>Cal (Kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($composting->trackings as $tracking)
                                <tr>
                                    <td class="font-mono">Día {{ $tracking->day }}</td>
                                    <td>{{ $tracking->date->format('d/m/Y') }}</td>
                                    <td class="max-w-xs truncate">{{ $tracking->activity }}</td>
                                    <td>{{ $tracking->temp_internal }}°C</td>
                                    <td>{{ $tracking->temp_env }}°C</td>
                                    <td>{{ $tracking->hum_pile }}%</td>
                                    <td>{{ $tracking->ph }}</td>
                                    <td>{{ $tracking->water }}L</td>
                                    <td>{{ $tracking->lime }}Kg</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Acciones -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.composting.index') }}" class="waste-btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a la Lista
            </a>
            <a href="{{ route('admin.composting.download.pdf', $composting) }}" class="bg-red-500 text-white border border-red-600 hover:bg-red-600 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                <i class="fas fa-file-pdf mr-2"></i>
                Descargar PDF
            </a>
            <a href="{{ route('admin.composting.edit', $composting) }}" class="waste-btn">
                <i class="fas fa-edit mr-2"></i>
                Editar Pila
            </a>
        </div>
    </div>
</div>
@endsection
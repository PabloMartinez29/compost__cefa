@extends('layouts.master')

@section('title', 'Detalles de la Actividad')

@section('content')
@vite(['resources/css/waste.css'])

<!-- Modal para ver detalles de la actividad -->
<div class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[95vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="waste-title text-xl">
                        <i class="fas fa-eye waste-icon"></i>
                        Detalles de la Actividad
                    </h3>
                    <p class="waste-subtitle">
                        <i class="fas fa-user-shield text-green-400 mr-2"></i>
                        {{ Auth::user()->name }} - {{ $maintenance->type_name }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>
                    <button onclick="window.location.href='{{ route('admin.machinery.maintenance.index') }}'" 
                            class="mt-2 text-gray-600 hover:text-gray-800 text-xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="waste-container animate-fade-in-up animate-delay-1">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-green-400 mr-2"></i>
                    Información del Registro
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Maquinaria -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Maquinaria</label>
                        <div class="waste-form-input bg-gray-50">
                            {{ $maintenance->machinery->name ?? 'N/A' }}
                            @if($maintenance->machinery)
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $maintenance->machinery->brand }} {{ $maintenance->machinery->model }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Fecha -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha</label>
                        <div class="waste-form-input bg-gray-50">{{ $maintenance->date->format('d/m/Y') }}</div>
                    </div>

                    <!-- Tipo -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Tipo</label>
                        <div class="waste-form-input bg-gray-50">
                            <span class="waste-badge {{ $maintenance->type == 'M' ? 'waste-badge-danger' : 'waste-badge-success' }}">
                                {{ $maintenance->type_name }}
                            </span>
                        </div>
                    </div>

                    <!-- Responsable -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Responsable</label>
                        <div class="waste-form-input bg-gray-50">{{ $maintenance->responsible }}</div>
                    </div>

                    <!-- Descripción -->
                    <div class="waste-form-group md:col-span-2">
                        <label class="waste-form-label">Descripción</label>
                        <div class="waste-form-textarea bg-gray-50" style="min-height: 100px;">{{ $maintenance->description }}</div>
                    </div>

                    <!-- Creado En -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Creado En</label>
                        <div class="waste-form-input bg-gray-50">{{ $maintenance->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button onclick="window.location.href='{{ route('admin.machinery.maintenance.index') }}'" 
                    class="waste-btn">
                <i class="fas fa-times mr-2"></i>
                Cerrar
            </button>
        </div>
    </div>
</div>
@endsection




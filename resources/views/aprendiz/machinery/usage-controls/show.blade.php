@extends('layouts.masteraprendiz')

@section('title', 'Detalles del Uso del Equipo')

@section('content')
@vite(['resources/css/waste.css'])

@php
    use Illuminate\Support\Facades\Storage;
@endphp

<!-- Modal para ver detalles del uso del equipo -->
<div class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[95vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="waste-title text-xl">
                        <i class="fas fa-eye waste-icon"></i>
                        Detalles del Uso del Equipo
                    </h3>
                    <p class="waste-subtitle">
                        <i class="fas fa-user-shield text-green-400 mr-2"></i>
                        {{ Auth::user()->name }} - {{ $usageControl->machinery->name ?? 'N/A' }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>
                    <button onclick="window.location.href='{{ route('aprendiz.machinery.usage-control.index') }}'" 
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
                
                <!-- Image Section -->
                @if($usageControl->machinery && $usageControl->machinery->image)
                    <div class="mb-8 text-center">
                        <img src="{{ Storage::url($usageControl->machinery->image) }}" 
                             alt="{{ $usageControl->machinery->name }}" 
                             class="max-w-full h-64 object-cover rounded-lg shadow-md mx-auto">
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Maquinaria -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Maquinaria</label>
                        <div class="waste-form-input bg-gray-50">{{ $usageControl->machinery->name ?? 'N/A' }}</div>
                        @if($usageControl->machinery)
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $usageControl->machinery->brand }} {{ $usageControl->machinery->model }}
                            </div>
                        @endif
                    </div>

                    <!-- Hora Inicio -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha/Hora Inicio</label>
                        <div class="waste-form-input bg-gray-50">{{ $usageControl->start_date ? $usageControl->start_date->setTimezone('America/Bogota')->format('d/m/Y h:i A') : 'N/A' }}</div>
                    </div>

                    <!-- Hora Fin -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha/Hora Fin</label>
                        <div class="waste-form-input bg-gray-50">{{ $usageControl->end_date ? $usageControl->end_date->setTimezone('America/Bogota')->format('d/m/Y h:i A') : 'N/A' }}</div>
                    </div>

                    <!-- Total Horas -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Total Horas de Uso</label>
                        <div class="waste-form-input bg-gray-50 font-semibold">{{ $usageControl->hours }} horas</div>
                    </div>

                    <!-- Responsable -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Responsable</label>
                        <div class="waste-form-input bg-gray-50">{{ $usageControl->responsible }}</div>
                    </div>

                    <!-- Observaciones -->
                    @if($usageControl->description)
                        <div class="waste-form-group md:col-span-2">
                            <label class="waste-form-label">Observaciones</label>
                            <div class="waste-form-textarea bg-gray-50" style="min-height: 100px;">{{ $usageControl->description }}</div>
                        </div>
                    @endif

                    <!-- Creado En -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Creado En</label>
                        <div class="waste-form-input bg-gray-50">{{ $usageControl->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button onclick="window.location.href='{{ route('aprendiz.machinery.usage-control.index') }}'" 
                    class="waste-btn">
                <i class="fas fa-times mr-2"></i>
                Cerrar
            </button>
        </div>
    </div>
</div>
@endsection



@extends('layouts.masteradmin')

@section('content')
@vite(['resources/css/waste.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-eye waste-icon"></i>
                    Detalles del Residuo Orgánico
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Registro #{{ str_pad($organic->id, 3, '0', STR_PAD_LEFT) }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Details Container -->
    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Details -->
            <div class="lg:col-span-2">
                <div class="waste-container animate-fade-in-up animate-delay-1">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-info-circle text-green-400 mr-2"></i>
                        Información del Registro
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Fecha</label>
                            <div class="waste-form-input bg-gray-50">{{ $organic->formatted_date }}</div>
                        </div>

                        <!-- Type -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Tipo de Residuo</label>
                            <div class="waste-form-input bg-gray-50">
                                <span class="waste-badge 
                                    @if($organic->type == 'Kitchen') waste-badge-success
                                    @elseif($organic->type == 'Beds') waste-badge-info
                                    @elseif($organic->type == 'Leaves') waste-badge-warning
                                    @else waste-badge-primary
                                    @endif">
                                    {{ $organic->type_in_spanish }}
                                </span>
                            </div>
                        </div>

                        <!-- Weight -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Peso</label>
                            <div class="waste-form-input bg-gray-50 font-semibold text-lg">{{ $organic->formatted_weight }}</div>
                        </div>

                        <!-- Delivered By -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Entregado Por</label>
                            <div class="waste-form-input bg-gray-50">{{ $organic->delivered_by }}</div>
                        </div>

                        <!-- Received By -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Recibido Por</label>
                            <div class="waste-form-input bg-gray-50">{{ $organic->received_by }}</div>
                        </div>

                        <!-- Created At -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Creado En</label>
                            <div class="waste-form-input bg-gray-50">{{ $organic->created_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($organic->notes)
                        <div class="waste-form-group mt-6">
                            <label class="waste-form-label">Notas</label>
                            <div class="waste-form-textarea bg-gray-50 min-h-[100px]">{{ $organic->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Image -->
                @if($organic->img)
                    <div class="waste-container animate-fade-in-up animate-delay-2 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Imagen</h3>
                        <div class="relative">
                            <img src="{{ asset($organic->img) }}" 
                                 alt="Organic waste image" 
                                 class="w-full h-48 object-cover rounded-lg border border-gray-200">
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="waste-container animate-fade-in-up animate-delay-3">
                                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.organic.edit', $organic) }}" class="waste-btn-secondary">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Registro
                        </a>
                        <a href="{{ route('admin.organic.index') }}" class="waste-btn">
                            <i class="fas fa-list mr-2"></i>
                            Volver a la Lista
                        </a>
                        <form action="{{ route('admin.organic.destroy', $organic) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this record?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="waste-btn-warning w-full">
                                <i class="fas fa-trash mr-2"></i>
                                Eliminar Registro
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="waste-container animate-fade-in-up animate-delay-4 mt-6">
                                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Estadísticas Rápidas</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">ID del Registro:</span>
                            <span class="font-mono font-semibold">#{{ str_pad($organic->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Peso:</span>
                            <span class="font-semibold">{{ $organic->formatted_weight }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tipo:</span>
                            <span class="font-semibold">{{ $organic->type_in_spanish }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Última Actualización:</span>
                            <span class="text-sm">{{ $organic->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

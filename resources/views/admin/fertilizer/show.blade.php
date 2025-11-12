@extends('layouts.master')

@section('content')
@vite(['resources/css/waste.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-eye waste-icon"></i>
                    Detalles del Abono Terminado
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Registro #{{ str_pad($fertilizer->id, 3, '0', STR_PAD_LEFT) }}
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
                            <div class="waste-form-input bg-gray-50">{{ $fertilizer->formatted_date }}</div>
                        </div>

                        <!-- Time -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Hora</label>
                            <div class="waste-form-input bg-gray-50">{{ $fertilizer->time }}</div>
                        </div>

                        <!-- Pila -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Pila</label>
                            <div class="waste-form-input bg-gray-50">
                                @if($fertilizer->composting)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-mountain mr-1"></i>
                                        {{ $fertilizer->composting->formatted_pile_num }}
                                    </span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Tipo de Abono</label>
                            <div class="waste-form-input bg-gray-50">
                                <span class="waste-badge 
                                    @if($fertilizer->type == 'Liquid') waste-badge-info
                                    @else waste-badge-success
                                    @endif">
                                    {{ $fertilizer->type_in_spanish }}
                                </span>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Cantidad</label>
                            <div class="waste-form-input bg-gray-50 font-semibold text-lg">{{ $fertilizer->formatted_amount }}</div>
                        </div>

                        <!-- Requester -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Solicitante</label>
                            <div class="waste-form-input bg-gray-50">{{ $fertilizer->requester }}</div>
                        </div>

                        <!-- Destination -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Destino</label>
                            <div class="waste-form-input bg-gray-50">{{ $fertilizer->destination }}</div>
                        </div>

                        <!-- Received By -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Recibido Por</label>
                            <div class="waste-form-input bg-gray-50">{{ $fertilizer->received_by }}</div>
                        </div>

                        <!-- Delivered By -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Entregado Por</label>
                            <div class="waste-form-input bg-gray-50">{{ $fertilizer->delivered_by }}</div>
                        </div>

                        <!-- Created At -->
                        <div class="waste-form-group">
                            <label class="waste-form-label">Creado En</label>
                            <div class="waste-form-input bg-gray-50">{{ $fertilizer->created_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($fertilizer->notes)
                        <div class="waste-form-group mt-6">
                            <label class="waste-form-label">Notas</label>
                            <div class="waste-form-textarea bg-gray-50 min-h-[100px]">{{ $fertilizer->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Actions -->
                <div class="waste-container animate-fade-in-up animate-delay-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.fertilizer.edit', $fertilizer) }}" class="waste-btn-secondary">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Registro
                        </a>
                        <a href="{{ route('admin.fertilizer.index') }}" class="waste-btn">
                            <i class="fas fa-list mr-2"></i>
                            Volver a la Lista
                        </a>
                        <form action="{{ route('admin.fertilizer.destroy', $fertilizer) }}" method="POST" 
                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
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
                <div class="waste-container animate-fade-in-up animate-delay-3 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Estadísticas Rápidas</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">ID del Registro:</span>
                            <span class="font-mono font-semibold">#{{ str_pad($fertilizer->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Cantidad:</span>
                            <span class="font-semibold">{{ $fertilizer->formatted_amount }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tipo:</span>
                            <span class="font-semibold">{{ $fertilizer->type_in_spanish }}</span>
                        </div>
                        @if($fertilizer->composting)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Pila:</span>
                            <span class="font-semibold">{{ $fertilizer->composting->formatted_pile_num }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Última Actualización:</span>
                            <span class="text-sm">{{ $fertilizer->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


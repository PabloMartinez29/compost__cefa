@extends('layouts.master')

@section('title', 'Detalles del Proveedor')

@section('content')
@vite(['resources/css/waste.css'])

@php
    use Illuminate\Support\Facades\Storage;
@endphp

<!-- Modal para ver detalles del proveedor -->
<div class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[95vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="waste-title text-xl">
                        <i class="fas fa-eye waste-icon"></i>
                        Detalles del Proveedor
                    </h3>
                    <p class="waste-subtitle">
                        <i class="fas fa-user-shield text-green-400 mr-2"></i>
                        {{ Auth::user()->name }} - {{ $supplier->supplier }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>
                    <button onclick="window.location.href='{{ route('admin.machinery.supplier.index') }}'" 
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
                @if($supplier->machinery && $supplier->machinery->image)
                    <div class="mb-8 text-center">
                        <img src="{{ Storage::url($supplier->machinery->image) }}" 
                             alt="{{ $supplier->machinery->name }}" 
                             class="max-w-full h-64 object-cover rounded-lg shadow-md mx-auto">
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Maquinaria -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Maquinaria</label>
                        <div class="waste-form-input bg-gray-50">{{ $supplier->machinery->name ?? 'N/A' }}</div>
                    </div>

                    <!-- Fabricante -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Fabricante</label>
                        <div class="waste-form-input bg-gray-50">{{ $supplier->maker }}</div>
                    </div>

                    <!-- Proveedor -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Proveedor</label>
                        <div class="waste-form-input bg-gray-50">{{ $supplier->supplier }}</div>
                    </div>

                    <!-- Origen -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Origen</label>
                        <div class="waste-form-input bg-gray-50">{{ $supplier->origin }}</div>
                    </div>

                    <!-- Fecha de Compra -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha de Compra</label>
                        <div class="waste-form-input bg-gray-50">{{ $supplier->purchase_date->format('d/m/Y') }}</div>
                    </div>

                    <!-- Teléfono -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Teléfono</label>
                        <div class="waste-form-input bg-gray-50">{{ $supplier->phone }}</div>
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="waste-form-group md:col-span-2">
                        <label class="waste-form-label">Correo Electrónico</label>
                        <div class="waste-form-input bg-gray-50">{{ $supplier->email }}</div>
                    </div>

                    <!-- Creado En -->
                    <div class="waste-form-group">
                        <label class="waste-form-label">Creado En</label>
                        <div class="waste-form-input bg-gray-50">{{ $supplier->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button onclick="window.location.href='{{ route('admin.machinery.supplier.index') }}'" 
                    class="waste-btn">
                <i class="fas fa-times mr-2"></i>
                Cerrar
            </button>
        </div>
    </div>
</div>
@endsection

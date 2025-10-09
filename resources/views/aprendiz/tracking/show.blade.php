@extends('layouts.masteraprendiz')

@section('title', 'Detalle del Seguimiento')

@section('content')
<!-- SweetAlert2 para alertas de sesión -->
@if(session('success'))
<script>
    Swal.fire({
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#22c55e',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        title: 'Error',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonColor: '#ef4444'
    });
</script>
@endif

@if(session('warning'))
<script>
    Swal.fire({
        title: 'Advertencia',
        text: '{{ session('warning') }}',
        icon: 'warning',
        confirmButtonColor: '#f59e0b'
    });
</script>
@endif

@if(session('info'))
<script>
    Swal.fire({
        title: 'Información',
        text: '{{ session('info') }}',
        icon: 'info',
        confirmButtonColor: '#3b82f6'
    });
</script>
@endif

<div class="waste-container">
    <!-- Header -->
    <div class="waste-header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-3"></i>
                    Detalle del Seguimiento
                </h1>
                <p class="text-gray-600 mt-2">Información completa del seguimiento de la pila {{ $tracking->composting->formatted_pile_num }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('aprendiz.tracking.edit', $tracking) }}" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg transition-colors duration-200" title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="{{ route('aprendiz.tracking.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white p-3 rounded-lg transition-colors duration-200" title="Volver">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Información del Seguimiento -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Información Básica -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Información Básica
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Pila de Compostaje</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $tracking->composting->formatted_pile_num }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Día</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $tracking->formatted_day }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Fecha</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $tracking->formatted_date }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Actividad Realizada</label>
                    <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $tracking->activity }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Horas de Trabajo</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $tracking->work_hours }}</p>
                </div>

                @if($tracking->others)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Observaciones Adicionales</label>
                        <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $tracking->others }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Mediciones -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-thermometer-half text-orange-600 mr-2"></i>
                    Mediciones
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-red-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-red-600">Temperatura Interna</label>
                        <p class="text-2xl font-bold text-red-700">{{ $tracking->formatted_temp_internal }}</p>
                        <p class="text-sm text-red-600">Medida a las {{ $tracking->formatted_temp_time }}</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-blue-600">Temperatura Ambiente</label>
                        <p class="text-2xl font-bold text-blue-700">{{ $tracking->formatted_temp_env }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-green-600">Humedad Pila</label>
                        <p class="text-2xl font-bold text-green-700">{{ $tracking->formatted_hum_pile }}</p>
                    </div>
                    <div class="bg-cyan-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-cyan-600">Humedad Ambiente</label>
                        <p class="text-2xl font-bold text-cyan-700">{{ $tracking->formatted_hum_env }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-purple-600">pH</label>
                        <p class="text-2xl font-bold text-purple-700">{{ $tracking->formatted_ph }}</p>
                    </div>
                    <div class="bg-indigo-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-indigo-600">Agua Agregada</label>
                        <p class="text-2xl font-bold text-indigo-700">{{ $tracking->formatted_water }}</p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-yellow-600">Cal Agregada</label>
                        <p class="text-2xl font-bold text-yellow-700">{{ $tracking->formatted_lime }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de la Pila -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-layer-group text-green-600 mr-2"></i>
                Información de la Pila
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Fecha de Inicio</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $tracking->composting->formatted_start_date }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Estado</label>
                    <p class="text-lg font-semibold text-gray-900">
                        @if($tracking->composting->end_date)
                            <span class="text-green-600">Completada</span>
                        @else
                            <span class="text-orange-600">En Proceso</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Peso Total</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $tracking->composting->formatted_total_kg }}</p>
                </div>
            </div>

            @if($tracking->composting->ingredients->count() > 0)
                <div class="mt-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-3">Ingredientes de la Pila</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($tracking->composting->ingredients as $ingredient)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900">{{ $ingredient->organic->type_in_spanish }}</h5>
                                <p class="text-sm text-gray-600">{{ number_format($ingredient->amount, 2) }} Kg</p>
                                @if($ingredient->notes)
                                    <p class="text-xs text-gray-500 mt-1">{{ $ingredient->notes }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

@endsection

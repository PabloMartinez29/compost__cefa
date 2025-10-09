@extends('layouts.masteraprendiz')

@section('title', 'Seguimiento de Pilas')

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
                    Seguimiento de Pilas
                </h1>
                <p class="text-gray-600 mt-2">Registra y monitorea el progreso de tus pilas de compostaje</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-layer-group text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pilas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPiles }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-full">
                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pilas Activas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activePiles }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Seguimientos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalTrackings }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pilas con Seguimientos -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Pilas de Compostaje</h2>
        </div>

        @if($compostings->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($compostings as $composting)
                    <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-layer-group text-green-600 text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $composting->formatted_pile_num }}
                                        </h3>
                                        <div class="flex items-center space-x-4 mt-1">
                                            <span class="text-sm text-gray-600">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                Inicio: {{ $composting->formatted_start_date }}
                                            </span>
                                            <span class="text-sm text-gray-600">
                                                <i class="fas fa-weight-hanging mr-1"></i>
                                                {{ $composting->formatted_total_kg }}
                                            </span>
                                            <span class="text-sm text-gray-600">
                                                <i class="fas fa-chart-line mr-1"></i>
                                                {{ $composting->trackings->count() }} seguimientos
                                            </span>
                                            <span class="text-sm text-gray-600">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Estado: {{ $composting->status }}
                                            </span>
                                        </div>
                                        
                                        <!-- Progreso del proceso basado en seguimientos -->
                                        <div class="mt-3">
                                            <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                                <span>Progreso del proceso (45 seguimientos)</span>
                                                <span>{{ $composting->tracking_progress }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full transition-all duration-300" 
                                                     style="width: {{ $composting->process_progress }}%"></div>
                                            </div>
                                            <div class="flex items-center justify-between mt-1">
                                                @if($composting->is_process_completed_by_trackings)
                                                    <span class="text-xs text-green-600 font-medium">✅ Proceso completado (45 seguimientos)</span>
                                                @else
                                                    <span class="text-xs text-blue-600">{{ 45 - $composting->trackings->count() }} seguimientos restantes</span>
                                                @endif
                                                <span class="text-xs text-gray-500">{{ $composting->current_phase }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($composting->trackings->count() > 0)
                                    <button onclick="openTrackingModal({{ $composting->id }})" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver Seguimientos
                                    </button>
                                @endif
                                @if($composting->status !== 'Completada')
                                    <a href="{{ route('aprendiz.tracking.create', ['composting_id' => $composting->id]) }}" 
                                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                                        <i class="fas fa-plus mr-1"></i>
                                        Nuevo Seguimiento
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Seguimientos recientes -->
                        @if($composting->trackings->count() > 0)
                            <div class="mt-4 ml-16">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span class="font-medium">Últimos seguimientos:</span>
                                    @foreach($composting->trackings->take(3) as $tracking)
                                        <span class="bg-gray-100 px-2 py-1 rounded">
                                            Día {{ $tracking->day }} ({{ $tracking->formatted_date }})
                                        </span>
                                    @endforeach
                                    @if($composting->trackings->count() > 3)
                                        <span class="text-gray-500">+{{ $composting->trackings->count() - 3 }} más</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay pilas de compostaje</h3>
                <p class="text-gray-600 mb-6">Primero debes crear una pila de compostaje para poder registrar seguimientos.</p>
                <a href="{{ route('aprendiz.composting.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Pila de Compostaje
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal para ver seguimientos -->
<div id="trackingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 transition-opacity duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95" id="modalContainer">
            <!-- Header del Modal -->
            <div class="bg-gradient-to-r from-green-600 to-blue-600 px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold" id="modalTitle">Seguimientos de la Pila</h3>
                            <p class="text-green-100 text-sm">Monitorea el progreso de tu pila de compostaje</p>
                        </div>
                    </div>
                    <button onclick="closeTrackingModal()" 
                            class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Contenido del Modal -->
            <div class="p-6 overflow-y-auto max-h-[70vh] bg-gray-50" id="modalContent">
                <!-- Contenido se carga dinámicamente -->
            </div>
        </div>
    </div>
</div>

<script>
function openTrackingModal(compostingId) {
    const modal = document.getElementById('trackingModal');
    const modalContent = document.getElementById('modalContent');
    const modalTitle = document.getElementById('modalTitle');
    const modalContainer = document.getElementById('modalContainer');
    
    // Mostrar loading
    modalContent.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto mb-4"></div>
                <p class="text-gray-600 font-medium">Cargando seguimientos...</p>
                <p class="text-gray-500 text-sm mt-1">Por favor espera un momento</p>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
    
    // Animar la aparición del modal
    setTimeout(() => {
        modalContainer.classList.remove('scale-95');
        modalContainer.classList.add('scale-100');
    }, 10);
    
    // Cargar datos
    fetch(`/aprendiz/tracking/composting/${compostingId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            modalTitle.textContent = `Seguimientos de ${data.composting.formatted_pile_num}`;
            
            if (data.trackings && data.trackings.length > 0) {
                modalContent.innerHTML = `
                    <div class="space-y-6">
                        <!-- Resumen de seguimientos -->
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-green-100 p-3 rounded-full">
                                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800">Resumen de Seguimientos</h4>
                                        <p class="text-sm text-gray-600">${data.trackings.length} seguimiento${data.trackings.length !== 1 ? 's' : ''} registrado${data.trackings.length !== 1 ? 's' : ''}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-green-600">${data.trackings.length}/45</div>
                                    <div class="text-sm text-gray-500">Seguimientos</div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de seguimientos -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Día</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividad</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temperaturas</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Humedad</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recursos</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        ${data.trackings.map(tracking => `
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                            Día ${tracking.day}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ${new Date(tracking.date).toLocaleDateString('es-ES')}
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm text-gray-900 max-w-xs truncate" title="${tracking.activity}">
                                                        ${tracking.activity}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="space-y-1">
                                                        <div class="flex items-center text-sm">
                                                            <i class="fas fa-thermometer-half text-red-500 mr-1"></i>
                                                            <span class="text-red-600 font-medium">${tracking.temp_internal}°C</span>
                                                        </div>
                                                        <div class="flex items-center text-sm text-gray-500">
                                                            <i class="fas fa-sun text-orange-500 mr-1"></i>
                                                            <span>${tracking.temp_env}°C</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="space-y-1">
                                                        <div class="flex items-center text-sm">
                                                            <i class="fas fa-tint text-blue-500 mr-1"></i>
                                                            <span class="text-blue-600 font-medium">${tracking.hum_pile}%</span>
                                                        </div>
                                                        <div class="flex items-center text-sm text-gray-500">
                                                            <i class="fas fa-cloud text-gray-400 mr-1"></i>
                                                            <span>${tracking.hum_env}%</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="space-y-1">
                                                        <div class="flex items-center text-sm">
                                                            <i class="fas fa-flask text-purple-500 mr-1"></i>
                                                            <span class="text-purple-600 font-medium">pH ${tracking.ph}</span>
                                                        </div>
                                                        <div class="flex items-center text-sm text-gray-500">
                                                            <i class="fas fa-tint text-blue-400 mr-1"></i>
                                                            <span>${tracking.water}L</span>
                                                        </div>
                                                        <div class="flex items-center text-sm text-gray-500">
                                                            <i class="fas fa-mountain text-gray-400 mr-1"></i>
                                                            <span>${tracking.lime}Kg</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <a href="/aprendiz/tracking/${tracking.id}" 
                                                           class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition-colors duration-200"
                                                           title="Ver">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="/aprendiz/tracking/${tracking.id}/edit" 
                                                           class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors duration-200"
                                                           title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button onclick="confirmDelete(${tracking.id})" 
                                                                class="bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-lg transition-colors duration-200"
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                modalContent.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-chart-line text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600">No hay seguimientos registrados para esta pila.</p>
                        <a href="/aprendiz/tracking/create?composting_id=${compostingId}" class="mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Registrar Primer Seguimiento
                        </a>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalContent.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                    <p class="text-red-600">Error al cargar los seguimientos.</p>
                    <p class="text-sm text-gray-500 mt-2">${error.message}</p>
                </div>
            `;
        });
}

function closeTrackingModal() {
    const modal = document.getElementById('trackingModal');
    const modalContainer = document.getElementById('modalContainer');
    
    // Animar el cierre del modal
    modalContainer.classList.remove('scale-100');
    modalContainer.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function confirmDelete(trackingId) {
    Swal.fire({
        title: '¿Eliminar seguimiento?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/aprendiz/tracking/${trackingId}`;
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            const tokenField = document.createElement('input');
            tokenField.type = 'hidden';
            tokenField.name = '_token';
            tokenField.value = '{{ csrf_token() }}';
            
            form.appendChild(methodField);
            form.appendChild(tokenField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection

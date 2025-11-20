@extends('layouts.master')

@section('title', 'Seguimiento de Pilas')

@section('content')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

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

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-chart-line waste-icon"></i>
                    Seguimiento de Pilas
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Panel de Administración
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="waste-card waste-card-primary animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Pilas</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalPiles }}</div>
                </div>
                <div class="waste-card-icon text-blue-600">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
        </div>

        <div class="waste-card waste-card-warning animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Pilas Activas</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $activePiles }}</div>
                </div>
                <div class="waste-card-icon text-yellow-600">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="waste-card waste-card-success animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Seguimientos</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalTrackings }}</div>
                </div>
                <div class="waste-card-icon text-green-600">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pilas con Seguimientos -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Table Header -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-chart-line text-green-600 mr-2"></i>
                Pilas de Compostaje
            </h2>
        </div>

        @if($compostings->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($compostings as $composting)
                    <div class="p-6 hover:bg-green-50 transition-colors duration-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($composting->image)
                                            <img src="{{ Storage::url($composting->image) }}" 
                                                 alt="{{ $composting->formatted_pile_num }}" 
                                                 class="w-16 h-16 object-cover rounded-lg border-2 border-green-200 shadow-sm cursor-pointer hover:opacity-80 transition-opacity"
                                                 onclick="openImageModal('{{ Storage::url($composting->image) }}')"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center border-2 border-green-200" style="display: none;">
                                                <i class="fas fa-layer-group text-green-600 text-lg"></i>
                                            </div>
                                        @else
                                            <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center border-2 border-green-200">
                                                <i class="fas fa-layer-group text-green-600 text-lg"></i>
                                            </div>
                                        @endif
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
                                        
                                        <!-- Progreso del proceso basado en días transcurridos -->
                                        <div class="mt-3">
                                            <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                                <span>Progreso del proceso (45 días)</span>
                                                <span>{{ $composting->tracking_progress }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="{{ $composting->progress_bar_color }} h-2 rounded-full transition-all duration-300" 
                                                     style="width: {{ $composting->process_progress }}%"></div>
                                            </div>
                                            <div class="flex items-center justify-between mt-1">
                                                @if($composting->is_process_completed)
                                                    <span class="text-xs text-green-600 font-medium">✅ Proceso completado (45 días)</span>
                                                @else
                                                    <span class="text-xs text-blue-600">{{ $composting->days_remaining }} días restantes</span>
                                                @endif
                                                <span class="text-xs text-gray-500">{{ $composting->current_phase }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 mt-8">
                                @if($composting->days_elapsed >= 1 || $composting->status === 'Completada' || $composting->days_elapsed >= 45)
                                    <button onclick="openTrackingModal({{ $composting->id }})" 
                                            class="inline-flex items-center text-blue-400 hover:text-blue-500"
                                            title="Ver Seguimientos">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @endif
                                @if($composting->status !== 'Completada' && $composting->days_elapsed < 45 && $composting->days_elapsed >= 0)
                                    <a href="{{ route('admin.tracking.create', ['composting_id' => $composting->id]) }}" 
                                       class="inline-flex items-center text-green-500 hover:text-green-700"
                                       title="Nuevo Seguimiento">
                                        <i class="fas fa-plus"></i>
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
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-chart-line text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay pilas de compostaje</h3>
                <p class="text-gray-600">Primero debes crear una pila de compostaje para poder registrar seguimientos.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal para ver seguimientos -->
<div id="trackingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 transition-opacity duration-300" onclick="if(event.target === this) closeTrackingModal();">
    <div class="flex items-center justify-center min-h-screen p-4" onclick="event.stopPropagation();">
        <div class="bg-white rounded-xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95" id="modalContainer" onclick="event.stopPropagation();">
            <!-- Header del Modal -->
            <div class="bg-green-100 px-6 py-4 border-b border-green-300">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-green-200 p-2 rounded-lg">
                            <i class="fas fa-chart-line text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Seguimientos de la Pila</h3>
                            <p class="text-gray-600 text-sm">Monitorea el progreso de la pila de compostaje</p>
                        </div>
                    </div>
                    <button onclick="closeTrackingModal()" 
                            class="text-gray-600 hover:bg-green-200 hover:text-gray-800 p-2 rounded-lg transition-colors duration-200">
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
// Definir funciones globalmente
function openImageModal(imageUrl) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 modal-backdrop-blur z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="relative max-w-6xl max-h-[90vh] w-full flex items-center justify-center">
            <button onclick="this.closest('.fixed').remove(); document.body.style.overflow = 'auto';" 
                    class="absolute top-4 right-4 z-10 bg-black bg-opacity-50 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75 transition-all">
                <i class="fas fa-times text-xl"></i>
            </button>
            <img src="${imageUrl}" alt="Imagen de la pila" 
                 class="max-w-4xl max-h-[80vh] w-auto h-auto object-contain rounded-lg shadow-2xl mx-auto">
        </div>
    `;
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
            document.body.style.overflow = 'auto';
        }
    });
}

function openTrackingModal(compostingId) {
    const modal = document.getElementById('trackingModal');
    const modalContent = document.getElementById('modalContent');
    const modalTitle = document.getElementById('modalTitle');
    const modalContainer = document.getElementById('modalContainer');
    
    if (!modal || !modalContent || !modalTitle || !modalContainer) {
        console.error('Modal elements not found');
        return;
    }
    
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
    fetch(`/admin/tracking/composting/${compostingId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            modalTitle.textContent = `Seguimientos de ${data.composting.formatted_pile_num}`;
            
            // Combinar seguimientos registrados y días faltantes
            const allDays = [];
            
            // Crear un mapa de seguimientos por día
            const trackingsMap = {};
            if (data.trackings && data.trackings.length > 0) {
                data.trackings.forEach(tracking => {
                    trackingsMap[tracking.day] = tracking;
                });
            }
            
            // Crear un mapa de días faltantes por día
            const missingDaysMap = {};
            if (data.missing_days && data.missing_days.length > 0) {
                data.missing_days.forEach(missingDay => {
                    missingDaysMap[missingDay.day] = missingDay;
                });
            }
            
            // Determinar cuántos días mostrar
            let daysElapsed = 0;
            
            // Si la pila está completada o han pasado 45 días, mostrar los 45 días completos
            if (data.composting.status === 'Completada' || (data.composting.days_elapsed && data.composting.days_elapsed >= 45)) {
                daysElapsed = 45;
            } else {
                // Calcular el día máximo de los seguimientos
                let maxTrackingDay = 0;
                if (data.trackings && data.trackings.length > 0) {
                    maxTrackingDay = Math.max(...data.trackings.map(t => t.day));
                }
                
                // Calcular el día máximo de los días faltantes
                let maxMissingDay = 0;
                if (data.missing_days && data.missing_days.length > 0) {
                    maxMissingDay = Math.max(...data.missing_days.map(d => d.day));
                }
                
                // Usar el día máximo entre seguimientos, días faltantes y días transcurridos
                const maxDay = Math.max(
                    maxTrackingDay,
                    maxMissingDay,
                    (data.composting.days_elapsed && data.composting.days_elapsed > 0) ? data.composting.days_elapsed : 0
                );
                
                // Si hay seguimientos registrados, asegurar que se muestren todos los días desde el 1 hasta el máximo
                if (maxTrackingDay > 0) {
                    daysElapsed = Math.max(maxDay, maxTrackingDay);
                } else if (maxMissingDay > 0) {
                    daysElapsed = maxMissingDay;
                } else if (data.composting.days_elapsed && data.composting.days_elapsed > 0) {
                    daysElapsed = data.composting.days_elapsed;
                } else {
                    daysElapsed = 0;
                }
                
                daysElapsed = Math.min(daysElapsed, 45);
            }
            
            // Si no hay días para mostrar, mostrar mensaje
            if (daysElapsed === 0) {
                modalContent.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-alt text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600">El proceso de compostaje aún no ha comenzado.</p>
                        <p class="text-sm text-gray-500 mt-2">Los seguimientos aparecerán aquí una vez que transcurran los primeros días.</p>
                    </div>
                `;
                return;
            }
            
            // Combinar todos los días (1 hasta daysElapsed)
            for (let day = 1; day <= daysElapsed; day++) {
                if (trackingsMap[day]) {
                    allDays.push({ day: day, type: 'tracking', data: trackingsMap[day] });
                } else if (missingDaysMap[day]) {
                    allDays.push({ day: day, type: 'missing', data: missingDaysMap[day] });
                } else {
                    // Si no está en ninguno de los mapas, crear un día faltante
                    const startDate = new Date(data.composting.start_date);
                    const dayDate = new Date(startDate);
                    dayDate.setDate(startDate.getDate() + (day - 1));
                    allDays.push({ 
                        day: day, 
                        type: 'missing', 
                        data: {
                            day: day,
                            date: dayDate.toISOString().split('T')[0],
                            date_formatted: dayDate.toLocaleDateString('es-ES')
                        }
                    });
                }
            }
            
            // Si no hay días generados, mostrar mensaje
            if (allDays.length === 0) {
                modalContent.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-4xl mb-4"></i>
                        <p class="text-gray-600">No se pudieron generar los días para mostrar.</p>
                        <p class="text-sm text-gray-500 mt-2">Por favor, verifica que haya seguimientos registrados o días transcurridos.</p>
                    </div>
                `;
                return;
            }
            
            // Ordenar por día
            allDays.sort((a, b) => a.day - b.day);
            
            const totalTrackings = data.trackings ? data.trackings.length : 0;
            const totalMissing = allDays.filter(d => d.type === 'missing').length;
            
            modalContent.innerHTML = `
                <div class="space-y-6">
                    <!-- Resumen de seguimientos -->
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800">Resumen de Seguimientos</h4>
                                    <p class="text-sm text-gray-600">${totalTrackings} seguimiento${totalTrackings !== 1 ? 's' : ''} registrado${totalTrackings !== 1 ? 's' : ''} | ${totalMissing} día${totalMissing !== 1 ? 's' : ''} sin registro</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="text-right mr-3">
                                    <div class="text-2xl font-bold text-green-600">${daysElapsed}/45</div>
                                    <div class="text-sm text-gray-500">Días transcurridos</div>
                                </div>
                                <a href="/admin/tracking/download/all-pdf" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors duration-200" title="Descargar PDF General">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
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
                                    ${allDays.map(item => {
                                        if (item.type === 'tracking') {
                                            const tracking = item.data;
                                            return `
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
                                                            <button type="button" 
                                                                    onclick="(function(e){ e.preventDefault(); e.stopPropagation(); const modal = document.getElementById('trackingModal'); if(modal) modal.classList.add('hidden'); setTimeout(function(){ window.location.href='/admin/tracking/${tracking.id}'; }, 100); })(event);" 
                                                                    class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition-colors duration-200 cursor-pointer"
                                                                    title="Ver">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <a href="/admin/tracking/${tracking.id}/edit" 
                                                               class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors duration-200"
                                                               title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="/admin/tracking/${tracking.id}/download/pdf" 
                                                               class="bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-lg transition-colors duration-200"
                                                               title="Descargar PDF">
                                                                <i class="fas fa-file-pdf"></i>
                                                            </a>
                                                            <button type="button" onclick="confirmDelete(${tracking.id})" 
                                                                    class="bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-lg transition-colors duration-200"
                                                                    title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            `;
                                        } else {
                                            const missingDay = item.data;
                                            const dateFormatted = missingDay.date || missingDay.date_formatted || new Date(missingDay.date_raw || missingDay.date).toLocaleDateString('es-ES');
                                            return `
                                                <tr class="hover:bg-yellow-50 transition-colors duration-200 bg-yellow-50">
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                                Día ${missingDay.day}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        ${dateFormatted}
                                                    </td>
                                                    <td class="px-4 py-4">
                                                        <div class="text-sm text-yellow-700 italic">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                                            No se realizó seguimiento
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400">
                                                        <span class="italic">-</span>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400">
                                                        <span class="italic">-</span>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400">
                                                        <span class="italic">-</span>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="/admin/tracking/create?composting_id=${data.composting.id}&day=${missingDay.day}" 
                                                           class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors duration-200"
                                                           title="Registrar Seguimiento">
                                                            <i class="fas fa-plus"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            `;
                                        }
                                    }).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
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
    
    if (!modal || !modalContainer) return;
    
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
            form.action = `/admin/tracking/${trackingId}`;
            
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

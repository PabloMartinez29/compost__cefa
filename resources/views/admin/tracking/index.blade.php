@extends('layouts.master')

@section('title', 'Seguimiento de Pilas')

@section('content')
@vite(['resources/css/waste.css'])

<script>
// Definir funciones globalmente antes de que se use el HTML
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
        .then(response => response.json().then(data => ({ ok: response.ok, status: response.status, data })))
        .then(({ ok, status, data }) => {
            if (!ok || data.error) {
                const msg = data && data.message ? data.message : (data && data.error ? data.error : 'No se pudieron cargar los seguimientos.');
                modalContent.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                        <p class="text-gray-700 font-medium">${typeof msg === 'string' ? msg : 'Error al cargar los seguimientos.'}</p>
                        <p class="text-sm text-gray-500 mt-2">Vuelve a intentar o recarga la página.</p>
                    </div>
                `;
                return;
            }
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
                <div class="space-y-4 sm:space-y-6 min-w-0">
                    <!-- Resumen de seguimientos (responsive: se apila en móvil) -->
                    <div class="bg-green-50 rounded-lg p-3 sm:p-4 border border-green-200 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 min-w-0">
                            <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                                <div class="bg-green-100 p-2 sm:p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-chart-line text-green-600 text-lg sm:text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-base sm:text-lg font-semibold text-gray-800 break-words">Resumen de Seguimientos</h4>
                                    <p class="text-xs sm:text-sm text-gray-600 break-words">${totalTrackings} seguimiento${totalTrackings !== 1 ? 's' : ''} registrado${totalTrackings !== 1 ? 's' : ''} | ${totalMissing} día${totalMissing !== 1 ? 's' : ''} sin registro</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-2 flex-shrink-0">
                                <div class="text-left sm:text-right">
                                    <div class="text-xl sm:text-2xl font-bold text-green-600">${daysElapsed}/45 <span class="text-sm font-normal text-gray-500">Días</span></div>
                                    <div class="text-xs sm:text-sm text-gray-500">transcurridos</div>
                                </div>
                                <a href="/admin/tracking/composting/${data.composting.id}/download/pdf" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors duration-200 flex-shrink-0" title="Descargar PDF de esta Pila">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de seguimientos (scroll horizontal en móvil) -->
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden min-w-0">
                        <div class="overflow-x-auto -mx-1">
                            <table class="w-full min-w-[640px]">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Día</th>
                                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Fecha</th>
                                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actividad</th>
                                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Temp.</th>
                                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Humedad</th>
                                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Recursos</th>
                                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    ${allDays.map(item => {
                                        if (item.type === 'tracking') {
                                            const tracking = item.data;
                                            return `
                                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                                Día ${tracking.day}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                                        ${new Date(tracking.date).toLocaleDateString('es-ES')}
                                                    </td>
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4">
                                                        <div class="text-sm text-gray-900 max-w-[140px] sm:max-w-xs truncate" title="${tracking.activity}">
                                                            ${tracking.activity}
                                                        </div>
                                                    </td>
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap">
                                                        <div class="space-y-0.5">
                                                            <div class="flex items-center text-xs sm:text-sm">
                                                                <i class="fas fa-thermometer-half text-red-500 mr-1 flex-shrink-0"></i>
                                                                <span class="text-red-600 font-medium">${tracking.temp_internal}°C</span>
                                                            </div>
                                                            <div class="flex items-center text-xs sm:text-sm text-gray-500">
                                                                <i class="fas fa-sun text-orange-500 mr-1 flex-shrink-0"></i>
                                                                <span>${tracking.temp_env}°C</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap">
                                                        <div class="space-y-0.5">
                                                            <div class="flex items-center text-xs sm:text-sm">
                                                                <i class="fas fa-tint text-blue-500 mr-1 flex-shrink-0"></i>
                                                                <span class="text-blue-600 font-medium">${tracking.hum_pile}%</span>
                                                            </div>
                                                            <div class="flex items-center text-xs sm:text-sm text-gray-500">
                                                                <i class="fas fa-cloud text-gray-400 mr-1 flex-shrink-0"></i>
                                                                <span>${tracking.hum_env}%</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap">
                                                        <div class="space-y-0.5">
                                                            <div class="flex items-center text-xs sm:text-sm">
                                                                <i class="fas fa-flask text-purple-500 mr-1 flex-shrink-0"></i>
                                                                <span class="text-purple-600 font-medium">pH ${tracking.ph}</span>
                                                            </div>
                                                            <div class="flex items-center text-xs sm:text-sm text-gray-500">
                                                                <i class="fas fa-tint text-blue-400 mr-1 flex-shrink-0"></i>
                                                                <span>${tracking.water}L</span>
                                                            </div>
                                                            <div class="flex items-center text-xs sm:text-sm text-gray-500">
                                                                <i class="fas fa-mountain text-gray-400 mr-1 flex-shrink-0"></i>
                                                                <span>${tracking.lime}Kg</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                                        <div class="flex flex-wrap gap-1 sm:space-x-2">
                                                            <button type="button" 
                                                                    onclick="openViewTrackingModal(${tracking.id})" 
                                                                    class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition-colors duration-200 cursor-pointer"
                                                                    title="Ver">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button type="button" 
                                                                    onclick="openEditTrackingModal(${tracking.id})" 
                                                                    class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors duration-200 cursor-pointer"
                                                                    title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" onclick="confirmDelete(${tracking.id})" 
                                                                    class="bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-lg transition-colors duration-200"
                                                                    title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                            <a href="/admin/tracking/${tracking.id}/download/pdf" 
                                                               class="bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-lg transition-colors duration-200"
                                                               title="Descargar PDF">
                                                                <i class="fas fa-file-pdf"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            `;
                                        } else {
                                            const missingDay = item.data;
                                            const dateFormatted = missingDay.date || missingDay.date_formatted || new Date(missingDay.date_raw || missingDay.date).toLocaleDateString('es-ES');
                                            return `
                                                <tr class="hover:bg-yellow-50 transition-colors duration-200 bg-yellow-50">
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                                Día ${missingDay.day}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                                        ${dateFormatted}
                                                    </td>
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4">
                                                        <div class="text-xs sm:text-sm text-yellow-700 italic">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                                            Sin seguimiento
                                                        </div>
                                                    </td>
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-400">
                                                        <span class="italic">-</span>
                                                    </td>
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-400">
                                                        <span class="italic">-</span>
                                                    </td>
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-400">
                                                        <span class="italic">-</span>
                                                    </td>
                                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
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

<div class="waste-container mx-auto px-3 sm:px-4 md:px-6 py-4 sm:py-6 md:py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
            <div class="flex-1 min-w-0">
                <h1 class="waste-title text-xl sm:text-2xl">
                    <i class="fas fa-chart-line waste-icon"></i>
                    Seguimiento de Pilas
                </h1>
                <p class="waste-subtitle text-sm sm:text-base">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    <span class="break-words">{{ Auth::user()->name }} - Panel de Administración</span>
                </p>
            </div>
            <div class="text-left sm:text-right flex-shrink-0">
                <div class="text-green-400 font-bold text-base sm:text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
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
        <div class="p-3 sm:p-4 md:p-6 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Pilas de Compostaje
                </h2>
                <a href="{{ route('admin.tracking.download.all-pdf') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200"
                   title="Descargar PDF General de Todas las Pilas">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </div>
        </div>

        @if($compostings->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($compostings as $composting)
                    <div class="p-3 sm:p-4 md:p-6 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($composting->image)
                                            <img src="{{ asset('storage-file/'.$composting->image) }}" 
                                                 alt="{{ $composting->formatted_pile_num }}" 
                                                 class="w-16 h-16 object-cover rounded-lg border-2 border-green-200 shadow-sm cursor-pointer hover:opacity-80 transition-opacity"
                                                 onclick="openImageModal('{{ asset('storage-file/'.$composting->image) }}')"
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
                                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1">
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
                            <div class="flex items-center flex-wrap gap-2 sm:mt-0 mt-4">
                                @if($composting->trackings->count() > 0 || $composting->status === 'Completada' || $composting->days_elapsed >= 1)
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
                            <div class="mt-4 sm:ml-16">
                                <div class="flex flex-wrap items-center gap-2 sm:space-x-4 text-sm text-gray-600">
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

<!-- Modal para ver seguimientos (responsive) -->
<div id="trackingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 transition-opacity duration-300 overflow-y-auto py-2 sm:py-4" onclick="if(event.target === this) closeTrackingModal();">
    <div class="flex items-center justify-center min-h-full px-2 sm:p-4" onclick="event.stopPropagation();">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-6xl max-h-[calc(100vh-1rem)] flex flex-col min-w-0 overflow-hidden transform transition-all duration-300 scale-95" id="modalContainer" onclick="event.stopPropagation();">
            <!-- Header del Modal -->
            <div class="bg-green-100 px-4 sm:px-6 py-3 sm:py-4 border-b border-green-300 flex-shrink-0">
                <div class="flex items-start sm:items-center justify-between gap-2 min-w-0">
                    <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1">
                        <div class="bg-green-200 p-1.5 sm:p-2 rounded-lg flex-shrink-0">
                            <i class="fas fa-chart-line text-green-600 text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base sm:text-xl font-bold text-gray-800 break-words" id="modalTitle">Seguimientos de la Pila</h3>
                            <p class="text-gray-600 text-xs sm:text-sm break-words">Monitorea el progreso de la pila de compostaje</p>
                        </div>
                    </div>
                    <button onclick="closeTrackingModal()" 
                            class="text-gray-600 hover:bg-green-200 hover:text-gray-800 p-2 rounded-lg transition-colors duration-200 flex-shrink-0">
                        <i class="fas fa-times text-lg sm:text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Contenido del Modal -->
            <div class="p-3 sm:p-6 overflow-auto flex-1 min-h-0 bg-gray-50 min-w-0" id="modalContent">
                <!-- Contenido se carga dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Detalles del Seguimiento -->
<div id="viewTrackingModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative">
        <!-- Modal Header -->
        <div class="waste-header relative">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-eye waste-icon"></i>
                    Detalle del Seguimiento
                </h3>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    <span id="viewTrackingUserInfo">{{ Auth::user()->name }} - Seguimiento #<span id="viewTrackingId"></span></span>
                </p>
            </div>
            <button id="closeViewTrackingModal" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-full p-2 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6" id="viewTrackingContent">
            <!-- Contenido se carga dinámicamente -->
        </div>
    </div>
</div>

<!-- Modal para Editar Seguimiento -->
<div id="editTrackingModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative">
        <!-- Modal Header -->
        <div class="waste-header relative">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-edit waste-icon"></i>
                    Editar Seguimiento
                </h3>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    <span id="editTrackingUserInfo">{{ Auth::user()->name }} - Seguimiento #<span id="editTrackingId"></span></span>
                </p>
            </div>
            <button id="closeEditTrackingModal" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-full p-2 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="editTrackingForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Pila de Compostaje -->
                    <div class="waste-form-group md:col-span-2">
                        <label for="edit_tracking_composting_id" class="waste-form-label">Pila de Compostaje *</label>
                        <select id="edit_tracking_composting_id" name="composting_id" required class="waste-form-select">
                            <option value="">Seleccionar pila</option>
                        </select>
                    </div>

                    <!-- Día -->
                    <div class="waste-form-group">
                        <label for="edit_tracking_day" class="waste-form-label">Día del Proceso *</label>
                        <input type="number" id="edit_tracking_day" name="day" min="1" max="45" required class="waste-form-input" />
                    </div>

                    <!-- Fecha -->
                    <div class="waste-form-group">
                        <label for="edit_tracking_date" class="waste-form-label">Fecha *</label>
                        <input type="date" id="edit_tracking_date" name="date" required class="waste-form-input" />
                    </div>

                    <!-- Actividad -->
                    <div class="waste-form-group md:col-span-2">
                        <label for="edit_tracking_activity" class="waste-form-label">Actividad Realizada *</label>
                        <textarea id="edit_tracking_activity" name="activity" rows="3" required class="waste-form-input"></textarea>
                    </div>

                    <!-- Horas de Trabajo -->
                    <div class="waste-form-group">
                        <label for="edit_tracking_work_hours" class="waste-form-label">Horas de Trabajo *</label>
                        <input type="text" id="edit_tracking_work_hours" name="work_hours" required class="waste-form-input" />
                    </div>

                    <!-- Temperatura Interna -->
                    <div class="waste-form-group">
                        <label for="edit_tracking_temp_internal" class="waste-form-label">Temperatura Interna (°C)</label>
                        <input type="number" id="edit_tracking_temp_internal" name="temp_internal" step="0.01" min="0" max="100" class="waste-form-input" />
                    </div>

                    <!-- Hora de Medición -->
                    <div class="waste-form-group">
                        <label for="edit_tracking_temp_time" class="waste-form-label">Hora de Medición</label>
                        <input type="time" id="edit_tracking_temp_time" name="temp_time" class="waste-form-input" />
                    </div>

                    <!-- Temperatura Ambiente -->
                    <div class="waste-form-group">
                        <label for="edit_tracking_temp_env" class="waste-form-label">Temperatura Ambiente (°C)</label>
                        <input type="number" id="edit_tracking_temp_env" name="temp_env" step="0.01" min="-10" max="50" class="waste-form-input" />
                    </div>

                    <!-- Humedad Pila -->
                    <div class="waste-form-group">
                        <label for="edit_tracking_hum_pile" class="waste-form-label">Humedad Pila (%)</label>
                        <input type="number" id="edit_tracking_hum_pile" name="hum_pile" step="0.01" min="0" max="100" class="waste-form-input" />
                    </div>

                    <!-- Humedad Ambiente -->
                    <div class="waste-form-group">
                        <label for="edit_tracking_hum_env" class="waste-form-label">Humedad Ambiente (%)</label>
                        <input type="number" id="edit_tracking_hum_env" name="hum_env" step="0.01" min="0" max="100" class="waste-form-input" />
                    </div>

                    <!-- pH -->
                    <div class="waste-form-group">
                        <label for="edit_tracking_ph" class="waste-form-label">pH</label>
                        <input type="number" id="edit_tracking_ph" name="ph" step="0.01" min="0" max="14" class="waste-form-input" />
                    </div>

                    <!-- Agua -->
                    <div class="waste-form-group">
                        <label for="edit_tracking_water" class="waste-form-label">Agua Agregada (L)</label>
                        <input type="number" id="edit_tracking_water" name="water" step="0.01" min="0" class="waste-form-input" />
                    </div>

                    <!-- Cal -->
                    <div class="waste-form-group">
                        <label for="edit_tracking_lime" class="waste-form-label">Cal Agregada (Kg)</label>
                        <input type="number" id="edit_tracking_lime" name="lime" step="0.01" min="0" class="waste-form-input" />
                    </div>

                    <!-- Observaciones -->
                    <div class="waste-form-group md:col-span-2">
                        <label for="edit_tracking_others" class="waste-form-label">Observaciones Adicionales</label>
                        <textarea id="edit_tracking_others" name="others" rows="3" class="waste-form-input"></textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelEditTracking" class="waste-btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="waste-btn">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Funciones para el modal de ver seguimiento
function openViewTrackingModal(trackingId) {
    fetch(`/admin/tracking/${trackingId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            const tracking = data.tracking;
            
            // Actualizar ID
            document.getElementById('viewTrackingId').textContent = tracking.id.toString().padStart(3, '0');
            
            // Construir contenido
            let content = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información Básica -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Información Básica
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Pila de Compostaje</label>
                                <p class="text-lg font-semibold text-gray-900">${tracking.pile_num}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Día</label>
                                    <p class="text-lg font-semibold text-gray-900">${tracking.formatted_day}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Fecha</label>
                                    <p class="text-lg font-semibold text-gray-900">${tracking.formatted_date}</p>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Actividad Realizada</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">${tracking.activity}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Horas de Trabajo</label>
                                <p class="text-lg font-semibold text-gray-900">${tracking.work_hours}</p>
                            </div>
                            ${tracking.others ? `
                            <div>
                                <label class="text-sm font-medium text-gray-600">Observaciones</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">${tracking.others}</p>
                            </div>
                            ` : ''}
                        </div>
                    </div>

                    <!-- Mediciones -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-thermometer-half text-orange-600 mr-2"></i>
                            Mediciones
                        </h4>
                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-red-50 p-3 rounded-lg">
                                    <label class="text-sm font-medium text-red-600">Temperatura Interna</label>
                                    <p class="text-xl font-bold text-red-700">${tracking.formatted_temp_internal}</p>
                                    <p class="text-xs text-red-600">Medida a las ${tracking.formatted_temp_time}</p>
                                </div>
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <label class="text-sm font-medium text-blue-600">Temperatura Ambiente</label>
                                    <p class="text-xl font-bold text-blue-700">${tracking.formatted_temp_env}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <label class="text-sm font-medium text-green-600">Humedad Pila</label>
                                    <p class="text-xl font-bold text-green-700">${tracking.formatted_hum_pile}</p>
                                </div>
                                <div class="bg-cyan-50 p-3 rounded-lg">
                                    <label class="text-sm font-medium text-cyan-600">Humedad Ambiente</label>
                                    <p class="text-xl font-bold text-cyan-700">${tracking.formatted_hum_env}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-purple-50 p-3 rounded-lg">
                                    <label class="text-sm font-medium text-purple-600">pH</label>
                                    <p class="text-xl font-bold text-purple-700">${tracking.formatted_ph}</p>
                                </div>
                                <div class="bg-indigo-50 p-3 rounded-lg">
                                    <label class="text-sm font-medium text-indigo-600">Agua</label>
                                    <p class="text-xl font-bold text-indigo-700">${tracking.formatted_water}</p>
                                </div>
                                <div class="bg-yellow-50 p-3 rounded-lg">
                                    <label class="text-sm font-medium text-yellow-600">Cal</label>
                                    <p class="text-xl font-bold text-yellow-700">${tracking.formatted_lime}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de la Pila -->
                <div class="bg-white rounded-lg border border-gray-200 p-4 mt-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-layer-group text-green-600 mr-2"></i>
                        Información de la Pila
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Fecha de Inicio</label>
                            <p class="text-lg font-semibold text-gray-900">${tracking.composting.formatted_start_date}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Estado</label>
                            <p class="text-lg font-semibold text-gray-900">
                                ${tracking.composting.end_date ? '<span class="text-green-600">Completada</span>' : '<span class="text-orange-600">En Proceso</span>'}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Peso Total</label>
                            <p class="text-lg font-semibold text-gray-900">${tracking.composting.formatted_total_kg}</p>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('viewTrackingContent').innerHTML = content;
            
            // Mostrar modal
            document.getElementById('viewTrackingModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Error al cargar los datos del seguimiento',
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
        });
}

// Funciones para el modal de editar seguimiento
function openEditTrackingModal(trackingId) {
    fetch(`/admin/tracking/${trackingId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(result => {
            const tracking = result.tracking;
            const activeCompostings = result.activeCompostings || [];
            
            // Actualizar ID
            document.getElementById('editTrackingId').textContent = tracking.id.toString().padStart(3, '0');
            
            // Configurar acción del formulario
            document.getElementById('editTrackingForm').action = `/admin/tracking/${trackingId}`;
            
            // Llenar campos
            document.getElementById('edit_tracking_day').value = tracking.day || '';
            document.getElementById('edit_tracking_date').value = tracking.date || '';
            document.getElementById('edit_tracking_activity').value = tracking.activity || '';
            document.getElementById('edit_tracking_work_hours').value = tracking.work_hours || '';
            document.getElementById('edit_tracking_temp_internal').value = tracking.temp_internal || '';
            document.getElementById('edit_tracking_temp_time').value = tracking.temp_time || '';
            document.getElementById('edit_tracking_temp_env').value = tracking.temp_env || '';
            document.getElementById('edit_tracking_hum_pile').value = tracking.hum_pile || '';
            document.getElementById('edit_tracking_hum_env').value = tracking.hum_env || '';
            document.getElementById('edit_tracking_ph').value = tracking.ph || '';
            document.getElementById('edit_tracking_water').value = tracking.water || '';
            document.getElementById('edit_tracking_lime').value = tracking.lime || '';
            document.getElementById('edit_tracking_others').value = tracking.others || '';
            
            // Llenar select de pilas
            const select = document.getElementById('edit_tracking_composting_id');
            select.innerHTML = '<option value="">Seleccionar pila</option>';
            activeCompostings.forEach(composting => {
                const option = document.createElement('option');
                option.value = composting.id;
                option.textContent = composting.formatted_pile_num;
                if (composting.id == tracking.composting_id) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
            
            // Mostrar modal
            document.getElementById('editTrackingModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Error al cargar los datos del seguimiento',
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
        });
}

// Cerrar modales
document.getElementById('closeViewTrackingModal')?.addEventListener('click', function() {
    document.getElementById('viewTrackingModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
});

document.getElementById('closeEditTrackingModal')?.addEventListener('click', function() {
    document.getElementById('editTrackingModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
});

document.getElementById('cancelEditTracking')?.addEventListener('click', function() {
    document.getElementById('editTrackingModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
});

// Cerrar al hacer clic fuera del modal
document.getElementById('viewTrackingModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
});

document.getElementById('editTrackingModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
});
</script>

@endsection

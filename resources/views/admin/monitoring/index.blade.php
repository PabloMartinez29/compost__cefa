@extends('layouts.master')

@section('content')
@vite(['resources/css/dashboard-admin.css'])

<!-- Chart.js - Cargar con fallback a versión local -->
<script>
    // Función para cargar Chart.js con múltiples fallbacks
    function loadChartJS() {
        return new Promise((resolve, reject) => {
            // Verificar si ya está cargado
            if (typeof Chart !== 'undefined') {
                resolve();
                return;
            }
            
            // Intentar cargar desde CDN principal (Chart.js 4.4.0)
            const script1 = document.createElement('script');
            script1.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
            script1.onload = () => {
                if (typeof Chart !== 'undefined') {
                    resolve();
                } else {
                    tryCDN2();
                }
            };
            script1.onerror = () => {
                tryCDN2();
            };
            
            function tryCDN2() {
                // Intentar CDN alternativo
                const script2 = document.createElement('script');
                script2.src = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js';
                script2.onload = () => {
                    if (typeof Chart !== 'undefined') {
                        resolve();
                    } else {
                        tryLocal();
                    }
                };
                script2.onerror = () => {
                    tryLocal();
                };
                document.head.appendChild(script2);
            }
            
            function tryLocal() {
                // Usar versión local como último recurso
                const script3 = document.createElement('script');
                script3.src = '{{ asset("AdminLTE-3.2.0/plugins/chart.js/Chart.min.js") }}';
                script3.onload = () => {
                    if (typeof Chart !== 'undefined') {
                        console.warn('Chart.js cargado desde versión local (2.9.4). Algunas características pueden no estar disponibles.');
                        resolve();
                    } else {
                        reject(new Error('Chart.js no se pudo cargar desde ninguna fuente'));
                    }
                };
                script3.onerror = () => {
                    reject(new Error('Chart.js no se pudo cargar desde ninguna fuente'));
                };
                document.head.appendChild(script3);
            }
            
            document.head.appendChild(script1);
        });
    }
    
    // Cargar Chart.js inmediatamente
    loadChartJS().catch(err => {
        console.error('Error al cargar Chart.js:', err);
    });
</script>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<div class="container mx-auto px-3 sm:px-4 md:px-6 py-4 sm:py-6 md:py-8">
    <!-- Header -->
    <div class="bg-green-50 rounded-xl shadow-sm p-4 sm:p-6 border border-green-200 animate-fade-in-up">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
            <div class="flex-1 min-w-0">
                <h1 class="welcome-title text-xl sm:text-2xl">
                    <i class="fas fa-chart-line text-green-600 mr-2 sm:mr-3"></i>
                    Módulo de Monitoreo
                </h1>
                <p class="welcome-subtitle text-sm sm:text-base">
                    <i class="fas fa-user-shield text-green-500 mr-2"></i>
                    <span class="break-words">{{ Auth::user()?->name ?? 'Usuario' }} - Supervisión de Todos los Módulos</span>
                </p>
            </div>
            <div class="text-left sm:text-right flex-shrink-0">
                <div class="text-green-600 font-bold text-base sm:text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Filtros de Período (zona separada; no se solapa con el DataTable del historial) -->
    <div class="bg-green-50 rounded-xl shadow-sm p-6 mb-8 border border-green-200 animate-fade-in-up animate-delay-1 mt-6 monitoring-filters-row">
        <form method="GET" action="{{ route('admin.monitoring.index') }}" class="flex flex-wrap items-end gap-4">
            
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Fecha Específica</label>
                <div class="relative">
                    <input type="date" name="start_date" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}" onchange="document.getElementById('end_date_input').value = this.value" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <input type="hidden" id="end_date_input" name="end_date" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}">
                </div>
            </div>

            <div>
                <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrar
                </button>
                
                <a href="{{ route('admin.monitoring.index') }}" class="ml-2 px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200 inline-flex items-center text-center h-[42px]">
                    <i class="fas fa-eraser mr-2"></i>
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Cards de Módulos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card Residuos -->
        <div onclick="showModule('residuos')" class="module-card bg-white rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200" id="card-residuos">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="bg-cyan-300 text-white rounded-lg p-3">
                        <i class="fas fa-recycle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Residuos</h3>
                        <p class="text-xs text-gray-600">{{ $stats['total_organics'] }} registros</p>
                    </div>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_organics_weight'], 1) }} Kg</div>
        </div>

        <!-- Card Pilas -->
        <div onclick="showModule('pilas')" class="module-card bg-white rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200" id="card-pilas">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="bg-green-300 text-white rounded-lg p-3">
                        <i class="fas fa-mountain text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Pilas</h3>
                        <p class="text-xs text-gray-600">{{ $stats['total_compostings'] }} total</p>
                    </div>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['active_compostings'] }} activas</div>
        </div>

        <!-- Card Abono -->
        <div onclick="showModule('abono')" class="module-card bg-white rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200" id="card-abono">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="bg-yellow-300 text-white rounded-lg p-3">
                        <i class="fas fa-seedling text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Abono</h3>
                        <p class="text-xs text-gray-600">{{ $stats['total_fertilizers'] }} registros</p>
                    </div>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_fertilizers_amount'], 1) }} Kg/L</div>
        </div>

        <!-- Card Maquinaria -->
        <div onclick="showModule('maquinaria')" class="module-card bg-white rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200" id="card-maquinaria">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="bg-orange-300 text-white rounded-lg p-3">
                        <i class="fas fa-cogs text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Maquinaria</h3>
                        <p class="text-xs text-gray-600">{{ $stats['total_machinery'] }} equipos</p>
                    </div>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total_machinery'] }} unidades</div>
        </div>
    </div>

    <!-- Sección Expandida de Módulo (Oculta por defecto) -->
    <div id="module-expanded" class="hidden mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h2 id="module-title" class="text-2xl font-bold text-gray-800"></h2>
                <div class="flex items-center space-x-3">
                    <a id="module-excel-link" href="#" onclick="downloadExcel(event)" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-file-excel mr-2"></i>
                        Excel
                    </a>
                    <a id="module-pdf-link" href="#" onclick="downloadPDF(event)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        <i class="fas fa-file-pdf mr-2"></i>
                        PDF
                    </a>
                </div>
            </div>
            <!-- Gráfica -->
            <div id="module-chart-container" class="mb-6">
                <div style="height: 400px;">
                    <canvas id="module-chart"></canvas>
                </div>
            </div>
            <!-- Historial de Registros (contenedor aislado para evitar solapamiento con filtros de fecha) -->
            <div id="module-history" class="mt-6 monitoring-history-block" style="clear: both;">
                <!-- Contenido dinámico del historial -->
            </div>
        </div>
    </div>

</div>

<script>
// Variable global para módulo actual
let currentModule = '';

// Variables globales para datos generales (sin filtrar)
const organicDataGeneral = @json($organicDataGeneral ?? []);
const compostingDataGeneral = @json($compostingDataGeneral ?? []);
const fertilizerDataGeneral = @json($fertilizerDataGeneral ?? []);

// Colores suaves consistentes
const colors = {
    primary: 'rgba(59, 130, 246, 0.6)',
    primaryBorder: 'rgba(59, 130, 246, 1)',
    success: 'rgba(34, 197, 94, 0.6)',
    successBorder: 'rgba(34, 197, 94, 1)',
    warning: 'rgba(234, 179, 8, 0.6)',
    warningBorder: 'rgba(234, 179, 8, 1)',
    info: 'rgba(6, 182, 212, 0.6)',
    infoBorder: 'rgba(6, 182, 212, 1)',
    danger: 'rgba(239, 68, 68, 0.6)',
    dangerBorder: 'rgba(239, 68, 68, 1)',
    purple: 'rgba(168, 85, 247, 0.6)',
    purpleBorder: 'rgba(168, 85, 247, 1)',
    orange: 'rgba(249, 115, 22, 0.6)',
    orangeBorder: 'rgba(249, 115, 22, 1)',
};

// Función para calcular tendencia
function calculateTrend(data) {
    if (!data || Object.keys(data).length < 2) return { percent: 0, direction: 'equal' };
    const values = Object.values(data);
    const first = values[0] || 0;
    const last = values[values.length - 1] || 0;
    if (first === 0) return { percent: last > 0 ? 100 : 0, direction: last > 0 ? 'up' : 'equal' };
    const percent = ((last - first) / first) * 100;
    return {
        percent: Math.abs(percent).toFixed(1),
        direction: percent > 0 ? 'up' : percent < 0 ? 'down' : 'equal'
    };
}

// Mostrar módulo expandido
function showModule(module) {
    const expanded = document.getElementById('module-expanded');
    const title = document.getElementById('module-title');
    const chartContainer = document.getElementById('module-chart-container');
    const historyContainer = document.getElementById('module-history');
    const pdfLink = document.getElementById('module-pdf-link');
    const smallCharts = document.getElementById('small-charts-container');
    
    // Ocultar gráficas pequeñas
    if (smallCharts) {
        smallCharts.style.display = 'none';
    }
    
    // Ocultar todas las cards
    document.querySelectorAll('.module-card').forEach(card => {
        card.classList.remove('ring-4', 'ring-green-500');
    });
    
    // Resaltar card seleccionada
    document.getElementById('card-' + module).classList.add('ring-4', 'ring-green-500');
    
    // Configurar según módulo
    const modules = {
        'residuos': {
            title: '<i class="fas fa-recycle text-green-600 mr-2"></i>Historial de Residuos Orgánicos',
            pdf: 'residuos',
            data: @json($organicData['by_type'] ?? []),
            records: @json($organicRecords)
        },
        'pilas': {
            title: '<i class="fas fa-mountain text-cyan-600 mr-2"></i>Historial de Pilas de Compostaje',
            pdf: 'pilas',
            // Usamos los datos FILTRADOS por fecha para que reflejen el período seleccionado
            data: @json($compostingData['by_status'] ?? []),
            records: @json($compostingRecords)
        },
        'abono': {
            title: '<i class="fas fa-seedling text-yellow-600 mr-2"></i>Historial de Abonos',
            pdf: 'abono',
            // Usamos los datos FILTRADOS por fecha para que reflejen el período seleccionado
            data: @json($fertilizerData['by_date'] ?? []),
            records: @json($fertilizerRecords)
        },
        'maquinaria': {
            title: '<i class="fas fa-cogs text-purple-600 mr-2"></i>Estado de Maquinaria',
            pdf: 'maquinaria',
            {{-- Para la gráfica circular mostraremos:
                 - Maquinaria en operación (Operativa + Sin mantenimiento registrado)
                 - Maquinaria en mantenimiento
            --}}
            data: @json([
                'En operación' => ($machineryData['by_status']['Operativa'] ?? 0) + ($machineryData['by_status']['Sin mantenimiento registrado'] ?? 0),
                'En mantenimiento' => $machineryData['by_status']['Mantenimiento requerido'] ?? 0,
            ]),
            records: @json($machineryRecords)
        }
    };
    
    const moduleData = modules[module];
    currentModule = module;
    title.innerHTML = moduleData.title;
    pdfLink.href = '#';
    pdfLink.setAttribute('data-module', module);
    const excelLink = document.getElementById('module-excel-link');
    excelLink.href = '#';
    excelLink.setAttribute('data-module', module);
    
    // Limpiar gráficas anteriores (Chart.js)
    if (oldChartInstance) {
        try {
            oldChartInstance.destroy();
        } catch (e) {
            console.warn('Error al destruir gráfica Chart.js anterior:', e);
        }
        oldChartInstance = null;
    }
    
    // Mostrar el módulo expandido primero
    expanded.classList.remove('hidden');
    
    // Verificar que los datos estén disponibles
    console.log('Datos del módulo:', module, moduleData.data);
    
    // Crear gráfica después de asegurar que Chart.js esté cargado y el DOM esté listo
    function createChartWhenReady() {
        const chartContainer = document.getElementById('module-chart-container');
        const canvas = document.getElementById('module-chart');
        
        if (!chartContainer) {
            console.error('No se encontró el contenedor de la gráfica');
            return;
        }
        
        if (!canvas) {
            console.error('No se encontró el elemento canvas');
            // Recrear el canvas si no existe
            const containerDiv = chartContainer.querySelector('div[style*="height: 400px"]');
            if (containerDiv) {
                containerDiv.innerHTML = '<canvas id="module-chart"></canvas>';
            } else {
                chartContainer.innerHTML = '<div style="height: 400px;"><canvas id="module-chart"></canvas></div>';
            }
        }
        
        // Función para esperar a que Chart.js esté disponible
        function waitForChartJS(attempts = 0) {
            if (typeof Chart !== 'undefined') {
                // Verificar que los datos existan antes de crear la gráfica
                if (!moduleData.data || (typeof moduleData.data === 'object' && Object.keys(moduleData.data).length === 0)) {
                    console.warn('No hay datos filtrados disponibles para el módulo:', module, 'Intentando usar datos generales...');
                    // Intentar usar datos generales si los datos filtrados están vacíos
                    if (module === 'residuos' && typeof organicDataGeneral !== 'undefined' && organicDataGeneral.by_type) {
                        console.log('Usando datos generales de residuos:', organicDataGeneral.by_type);
                        moduleData.data = organicDataGeneral.by_type;
                    } else if (module === 'pilas' && typeof compostingDataGeneral !== 'undefined' && compostingDataGeneral.by_status) {
                        console.log('Usando datos generales de pilas:', compostingDataGeneral.by_status);
                        moduleData.data = compostingDataGeneral.by_status;
                    } else if (module === 'abono' && typeof fertilizerDataGeneral !== 'undefined' && fertilizerDataGeneral.by_date) {
                        console.log('Usando datos generales de abono:', fertilizerDataGeneral.by_date);
                        moduleData.data = fertilizerDataGeneral.by_date;
                    } else {
                        console.warn('No se encontraron datos generales para el módulo:', module);
                    }
                }
                
                // Verificar nuevamente después de intentar usar datos generales
                if (!moduleData.data || (typeof moduleData.data === 'object' && Object.keys(moduleData.data).length === 0)) {
                    console.error('No hay datos disponibles (ni filtrados ni generales) para el módulo:', module);
                    const container = document.getElementById('module-chart-container');
                    if (container) {
                        container.innerHTML = '<p class="text-gray-500 text-center py-8">No hay datos disponibles para mostrar. Por favor, verifica que existan registros en el sistema.</p>';
                    }
                    showModuleHistory(module, moduleData.records);
                    return;
                }
                
                createExpandedChart('module-chart', moduleData.data, module);
                showModuleHistory(module, moduleData.records);
            } else if (attempts < 10) {
                // Esperar hasta 5 segundos (10 intentos x 500ms)
                setTimeout(() => waitForChartJS(attempts + 1), 500);
            } else {
                // Intentar cargar Chart.js si no está disponible después de esperar
                if (typeof loadChartJS === 'function') {
                    loadChartJS().then(() => {
                        createExpandedChart('module-chart', moduleData.data, module);
                        showModuleHistory(module, moduleData.records);
                    }).catch(() => {
                        chartContainer.innerHTML = '<p class="text-red-500 text-center py-8">Error: No se pudo cargar Chart.js. Por favor, verifica tu conexión a internet y recarga la página.</p>';
                    });
                } else {
                    chartContainer.innerHTML = '<p class="text-red-500 text-center py-8">Error: Chart.js no está disponible. Por favor, recarga la página.</p>';
                }
            }
        }
        
        waitForChartJS();
    }
    
    // Esperar un poco más para asegurar que el DOM esté completamente listo después de remover 'hidden'
    setTimeout(createChartWhenReady, 100);
}

let oldChartInstance = null;
let dataTableInstance = null;

// Cerrar módulo
function closeModule() {
    document.getElementById('module-expanded').classList.add('hidden');
    const smallCharts = document.getElementById('small-charts-container');
    if (smallCharts) {
        smallCharts.style.display = 'grid';
    }
    document.querySelectorAll('.module-card').forEach(card => {
        card.classList.remove('ring-4', 'ring-green-500');
    });
    if (oldChartInstance) {
        try {
            oldChartInstance.destroy();
        } catch (e) {
            console.warn('Error al destruir gráfica Chart.js:', e);
        }
        oldChartInstance = null;
    }
    if (dataTableInstance) {
        dataTableInstance.destroy();
        dataTableInstance = null;
    }
    currentModule = '';
}

// Mostrar historial de registros
function showModuleHistory(module, records) {
    const historyContainer = document.getElementById('module-history');
    
    if (!records || records.length === 0) {
        historyContainer.innerHTML = '<p class="text-gray-500 text-center py-8">No hay registros disponibles para este período.</p>';
        return;
    }
    
    let html = '<div class="mt-6 monitoring-datatable-container"><h3 class="text-lg font-bold text-gray-800 mb-4">Historial de Entradas</h3>';
    
    // Controles personalizados (selector de cantidad de registros)
    html += '<div class="flex items-center justify-between mb-4">';
    html += '<div class="flex items-center space-x-2">';
    html += '<span class="text-sm text-gray-600">Mostrar</span>';
    html += '<select onchange="changeTableLength(this)" class="form-select px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 bg-white">';
    html += '<option value="5">5</option>';
    html += '<option value="10">10</option>';
    html += '<option value="25">25</option>';
    html += '<option value="50">50</option>';
    html += '<option value="-1">Todos</option>';
    html += '</select>';
    html += '<span class="text-sm text-gray-600">registros</span>';
    html += '</div>';
    html += '</div>';

    html += '<div class="overflow-x-auto"><table id="history-table" class="min-w-full divide-y divide-gray-200" style="min-width: 800px;">';
    
    if (module === 'residuos') {
        html += '<thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peso (Kg)</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Creado por</th></tr></thead>';
        html += '<tbody class="bg-white divide-y divide-gray-200">';
        records.forEach(record => {
            const date = new Date(record.date || record.created_at).toLocaleDateString('es-ES');
            const typeMap = {
                'Kitchen': 'Cocina',
                'Beds': 'Camas',
                'Leaves': 'Hojas',
                'CowDung': 'Estiércol de Vaca',
                'ChickenManure': 'Estiércol de Pollo',
                'PigManure': 'Estiércol de Cerdo',
                'Other': 'Otro'
            };
            const typeName = record.type_in_spanish || typeMap[record.type] || record.type || 'N/A';
            html += `<tr class="hover:bg-gray-50"><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">${date}</td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${typeName}</td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.weight || 0}</td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.creator ? record.creator.name : 'N/A'}</td></tr>`;
        });
    } else if (module === 'pilas') {
        html += '<thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Creación</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Creado por</th></tr></thead>';
        html += '<tbody class="bg-white divide-y divide-gray-200">';
        records.forEach(record => {
            const date = new Date(record.created_at).toLocaleDateString('es-ES');
            const status = record.end_date ? 'Completada' : 'Activa';
            html += `<tr class="hover:bg-gray-50"><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">${date}</td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.code || 'N/A'}</td><td class="px-4 py-3 whitespace-nowrap text-sm"><span class="px-2 py-1 text-xs rounded-full ${status === 'Activa' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">${status}</span></td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.creator ? record.creator.name : 'N/A'}</td></tr>`;
        });
    } else if (module === 'abono') {
        html += '<thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unidad</th></tr></thead>';
        html += '<tbody class="bg-white divide-y divide-gray-200">';
        records.forEach(record => {
            const date = new Date(record.date).toLocaleDateString('es-ES');
            html += `<tr class="hover:bg-gray-50"><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">${date}</td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.type || 'N/A'}</td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.amount || 0}</td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.type === 'Liquid' ? 'L' : 'Kg'}</td></tr>`;
        });
    } else if (module === 'maquinaria') {
        html += '<thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Marca</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modelo</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ubicación</th></tr></thead>';
        html += '<tbody class="bg-white divide-y divide-gray-200">';
        records.forEach(record => {
            let status = record.status || 'Sin mantenimiento registrado';
            if (status === 'N/A') {
                status = 'Sin mantenimiento registrado';
            }
            const statusClass = status === 'Operativa' ? 'bg-green-100 text-green-800' : 
                               status === 'Mantenimiento requerido' ? 'bg-red-100 text-red-800' : 
                               'bg-yellow-100 text-yellow-800';
            html += `<tr class="hover:bg-gray-50"><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">${record.name || 'N/A'}</td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.brand || 'N/A'}</td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.model || 'N/A'}</td><td class="px-4 py-3 whitespace-nowrap text-sm"><span class="px-2 py-1 text-xs rounded-full ${statusClass}">${status}</span></td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.location || 'N/A'}</td></tr>`;
        });
    }
    
    html += '</tbody></table></div></div>';
    historyContainer.innerHTML = html;
    
    // Destruir DataTable anterior si existe
    if (dataTableInstance) {
        dataTableInstance.destroy();
        dataTableInstance = null;
    }
    
    // Inicializar DataTable con 5 registros por página (length en fila propia para evitar solapamiento del 5 con la flecha)
    setTimeout(() => {
        dataTableInstance = $('#history-table').DataTable({
            pageLength: 5,
            lengthChange: false, // Desactivar el control por defecto
            dom: 'rtip', // 'r' processing, 't' table, 'i' info, 'p' pagination. Quitamos 'l' y 'f' (buscador)
            language: {
                "sProcessing": "Procesando...",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            order: [[0, 'desc']] // Ordenar por primera columna (fecha) descendente
        });
    }, 100);
}

// Función para cambiar cantidad de registros desde el select personalizado
function changeTableLength(select) {
    if (dataTableInstance) {
        const length = parseInt(select.value);
        dataTableInstance.page.len(length).draw();
    }
}

// Descargar PDF
function downloadPDF(event) {
    event.preventDefault();
    const module = event.target.closest('a').getAttribute('data-module');
    const period = '{{ $period }}';
    const startDate = '{{ $startDate ? $startDate->format("Y-m-d") : "" }}';
    const endDate = '{{ $endDate ? $endDate->format("Y-m-d") : "" }}';
    
    const url = '{{ route("admin.monitoring.download.pdf") }}?module=' + module + '&period=' + period + '&start_date=' + startDate + '&end_date=' + endDate;
    window.location.href = url;
}

// Descargar Excel
function downloadExcel(event) {
    event.preventDefault();
    const module = event.target.closest('a').getAttribute('data-module');
    const period = '{{ $period }}';
    const startDate = '{{ $startDate ? $startDate->format("Y-m-d") : "" }}';
    const endDate = '{{ $endDate ? $endDate->format("Y-m-d") : "" }}';
    
    const url = '{{ route("admin.monitoring.download.excel") }}?module=' + module + '&period=' + period + '&start_date=' + startDate + '&end_date=' + endDate;
    window.location.href = url;
}

// Crear gráfica expandida
function createExpandedChart(canvasId, data, module) {
    // Verificar que Chart.js esté disponible
    if (typeof Chart === 'undefined') {
        console.error('Chart.js no está disponible. Verifica que el script esté cargado.');
        const container = document.getElementById('module-chart-container');
        if (container) {
            container.innerHTML = '<p class="text-red-500 text-center py-8">Error: Chart.js no está cargado. Por favor, recarga la página.</p>';
        }
        return;
    }
    
    const ctx = document.getElementById(canvasId);
    if (!ctx) {
        console.error('No se encontró el elemento canvas con id:', canvasId);
        const container = document.getElementById('module-chart-container');
        if (container) {
            container.innerHTML = '<p class="text-red-500 text-center py-8">Error: No se encontró el elemento canvas. Por favor, recarga la página.</p>';
        }
        return;
    }
    
    // Destruir gráfica anterior si existe
    if (oldChartInstance) {
        try {
            oldChartInstance.destroy();
        } catch (e) {
            console.warn('Error al destruir gráfica anterior:', e);
        }
        oldChartInstance = null;
    }
    
    let labels = [];
    let values = [];
    let weights = []; // Para módulo de residuos
    let chartType = 'line';
    let datasets = [];
    
    // Validar que haya datos
    if (!data || (typeof data === 'object' && Object.keys(data).length === 0)) {
        console.warn('No hay datos para el módulo:', module, 'Datos recibidos:', data);
        const container = document.getElementById('module-chart-container');
        if (container) {
            container.innerHTML = '<p class="text-gray-500 text-center py-8">No hay datos disponibles para el período seleccionado. Intenta cambiar el filtro de fecha.</p>';
        }
        return;
    }
    
    console.log('Creando gráfica para módulo:', module, 'con datos:', data);
    
    if (module === 'residuos') {
        // Para residuos, mostrar por tipo con sus nombres en español - solo peso
        const typeMap = {
            'Kitchen': 'Cocina',
            'Beds': 'Camas',
            'Leaves': 'Hojas',
            'CowDung': 'Estiércol de Vaca',
            'ChickenManure': 'Estiércol de Pollo',
            'PigManure': 'Estiércol de Cerdo',
            'Other': 'Otro'
        };
        
        // Asegurar que todos los tipos estén presentes (incluso si no tienen datos)
        const allTypes = Object.keys(typeMap);
        const dataMap = {};
        Object.keys(data || {}).forEach(key => {
            dataMap[key] = data[key];
        });
        
        // Crear arrays ordenados con todos los tipos
        labels = [];
        weights = []; // Usar la variable del scope superior
        
        allTypes.forEach(type => {
            labels.push(typeMap[type]);
            if (dataMap[type]) {
                // Manejar diferentes formatos de datos
                if (typeof dataMap[type] === 'object' && dataMap[type].weight !== undefined) {
                    weights.push(parseFloat(dataMap[type].weight) || 0);
                } else if (typeof dataMap[type] === 'object' && dataMap[type].amount !== undefined) {
                    weights.push(parseFloat(dataMap[type].amount) || 0);
                } else if (typeof dataMap[type] === 'number') {
                    weights.push(parseFloat(dataMap[type]) || 0);
                } else {
                    weights.push(0);
                }
            } else {
                weights.push(0);
            }
        });
        
        console.log('Datos procesados para residuos - labels:', labels, 'weights:', weights);
        
        chartType = 'bar';
        
        // Solo una barra con el peso
        datasets = [{
            label: 'Peso Total (Kg)',
            data: weights,
            borderColor: colors.infoBorder,
            backgroundColor: colors.info,
            borderWidth: 2
        }];
    } else if (module === 'pilas') {
        // Para pilas mostramos gráfica circular simple
        // Manejar diferentes formatos de datos
        let active = 0;
        let completed = 0;
        
        if (data && typeof data === 'object') {
            if (data.active !== undefined) {
                active = parseInt(data.active) || 0;
            }
            if (data.completed !== undefined) {
                completed = parseInt(data.completed) || 0;
            }
            // Si los datos vienen como un array o objeto diferente, intentar extraerlos
            if (active === 0 && completed === 0 && Object.keys(data).length > 0) {
                // Intentar encontrar valores en diferentes formatos
                const dataValues = Object.values(data);
                if (dataValues.length >= 2) {
                    active = parseInt(dataValues[0]) || 0;
                    completed = parseInt(dataValues[1]) || 0;
                }
            }
        }
        
        // Gráfica circular simple - dos colores
        labels = ['Pilas en proceso', 'Pilas completadas'];
        values = [active, completed];
        chartType = 'pie';
        
        datasets = [{
            label: 'Estado de Pilas',
            data: values,
            backgroundColor: [
                '#06b6d4',  // Cyan para pilas en proceso
                '#22c55e'   // Verde para pilas completadas
            ],
            borderColor: [
                '#ffffff',
                '#ffffff'
            ],
            borderWidth: 3
        }];
    } else if (module === 'abono') {
        // Abono: gráfica LINEAL por fecha
        labels = Object.keys(data || {});
        // Manejar diferentes formatos de valores
        values = Object.values(data || {}).map(v => {
            if (typeof v === 'number') {
                return parseFloat(v) || 0;
            } else if (typeof v === 'object' && v !== null) {
                // Si el valor es un objeto, intentar extraer amount o weight
                return parseFloat(v.amount || v.weight || 0) || 0;
            } else {
                return parseFloat(v) || 0;
            }
        });
        
        // Si no hay datos, mostrar mensaje
        if (labels.length === 0 || (values.length > 0 && values.every(v => v === 0))) {
            const container = document.getElementById('module-chart-container');
            if (container) {
                container.innerHTML = '<p class="text-gray-500 text-center py-8">No hay datos de abono disponibles para el período seleccionado.</p>';
            }
            return;
        }
        
        // Ordenar por fecha si las etiquetas son fechas
        const sortedPairs = labels.map((label, index) => [label, values[index]])
            .sort((a, b) => {
                // Intentar ordenar por fecha si es posible
                const dateA = new Date(a[0]);
                const dateB = new Date(b[0]);
                if (!isNaN(dateA.getTime()) && !isNaN(dateB.getTime())) {
                    return dateA - dateB;
                }
                return a[0].localeCompare(b[0]);
            });
        
        labels = sortedPairs.map(pair => pair[0]);
        values = sortedPairs.map(pair => pair[1]);
        
        chartType = 'line';
        
        // Estilo línea: línea fina sin relleno, puntos visibles
        // Compatible con Chart.js 2.x y 4.x
        const lineConfig = {
            label: 'Cantidad de Abono (Kg/L)',
            data: values,
            borderColor: colors.warningBorder,
            backgroundColor: 'rgba(0,0,0,0)',
            borderWidth: 3,
            fill: false,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: colors.warningBorder,
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2
        };
        
        // Solo agregar tension si Chart.js 4.x está disponible
        if (typeof Chart !== 'undefined' && Chart.version && parseFloat(Chart.version) >= 3) {
            lineConfig.tension = 0.4;
        } else {
            // Para Chart.js 2.x, usar lineTension
            lineConfig.lineTension = 0.4;
        }
        
        datasets = [lineConfig];
    } else if (module === 'maquinaria') {
        // Maquinaria: gráfica circular con dos sectores
        labels = Object.keys(data || {});
        values = Object.values(data || {}).map(v => parseInt(v) || 0);
        
        // Si no hay datos, mostrar mensaje
        const total = values.reduce((a, b) => a + b, 0);
        if (total === 0) {
            const container = document.getElementById('module-chart-container');
            if (container) {
                container.innerHTML = '<p class="text-gray-500 text-center py-8">No hay datos de maquinaria disponibles.</p>';
            }
            return;
        }
        
        chartType = 'pie';

        // Colores para los dos sectores
        const colorOperacion = '#22c55e';        // Verde para en operación
        const colorMantenimiento = '#ef4444';    // Rojo para en mantenimiento

        // Mapear colores según las etiquetas
        const bgColors = labels.map(label => {
            if (label === 'En operación' || label === 'Operativa') {
                return colorOperacion;
            } else if (label === 'En mantenimiento' || label === 'Mantenimiento requerido') {
                return colorMantenimiento;
            }
            return colors.purple; // Color por defecto
        });

        datasets = [{
            label: 'Estado de Maquinaria',
            data: values,
            backgroundColor: bgColors,
            borderColor: [
                '#ffffff',
                '#ffffff'
            ],
            borderWidth: 3
        }];
    }
    
    // Validar que tengamos datos para mostrar
    // Para residuos, verificamos weights; para otros módulos, verificamos values
    const hasData = module === 'residuos' 
        ? (labels.length > 0 && weights && weights.length > 0 && weights.some(w => w > 0))
        : (labels.length > 0 && values.length > 0 && values.some(v => v > 0));
    
    if (!hasData) {
        console.warn('Validación fallida - labels:', labels.length, 'weights:', weights?.length, 'values:', values.length);
        const container = document.getElementById('module-chart-container');
        if (container) {
            container.innerHTML = '<p class="text-gray-500 text-center py-8">No hay datos disponibles para mostrar en la gráfica.</p>';
        }
        return;
    }
    
    try {
        // Detectar versión de Chart.js y crear configuración compatible
        const isChartJS4 = typeof Chart !== 'undefined' && Chart.version && parseFloat(Chart.version) >= 3;
        
        let chartConfig;
        
        if (isChartJS4) {
            // Configuración para Chart.js 4.x
            const isPieChart = chartType === 'pie' || chartType === 'doughnut';
            
            chartConfig = {
                type: chartType,
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    // Para gráficas circulares, no usar escalas
                    ...(isPieChart ? {} : {
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: module === 'residuos' ? 'Peso (Kg)' : (module === 'abono' ? 'Cantidad (Kg/L)' : 'Cantidad')
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: module === 'residuos'
                                        ? 'Tipo de Residuo'
                                        : (module === 'maquinaria' ? 'Estado de la maquinaria' : 'Fecha')
                                }
                            }
                        }
                    }),
                    plugins: {
                        legend: {
                            display: true,
                            position: isPieChart ? 'right' : 'top',
                            labels: {
                                padding: 15,
                                usePointStyle: isPieChart,
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            enabled: true,
                            mode: isPieChart ? 'point' : 'index',
                            intersect: false,
                            callbacks: isPieChart ? {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            } : {}
                        }
                    },
                    // Efectos 3D para gráficas circulares
                    ...(isPieChart ? {
                        animation: {
                            animateRotate: true,
                            animateScale: true,
                            duration: 1500,
                            easing: 'easeOutQuart'
                        },
                        elements: {
                            arc: {
                                borderWidth: 3,
                                borderColor: '#ffffff'
                            }
                        },
                        cutout: chartType === 'doughnut' ? '60%' : '0%'
                    } : {})
                }
            };
        } else {
            // Configuración para Chart.js 2.x
            const isPieChart = chartType === 'pie' || chartType === 'doughnut';
            
            chartConfig = {
                type: chartType,
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    // Para gráficas circulares, no usar escalas
                    ...(isPieChart ? {} : {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    precision: 0
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: module === 'residuos' ? 'Peso (Kg)' : (module === 'abono' ? 'Cantidad (Kg/L)' : 'Cantidad')
                                }
                            }],
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: module === 'residuos'
                                        ? 'Tipo de Residuo'
                                        : (module === 'maquinaria' ? 'Estado de la maquinaria' : 'Fecha')
                                }
                            }]
                        }
                    }),
                    legend: {
                        display: true,
                        position: isPieChart ? 'right' : 'top',
                        labels: {
                            padding: 15,
                            usePointStyle: isPieChart,
                            fontSize: 14,
                            fontStyle: 'bold'
                        }
                    },
                    tooltips: {
                        enabled: true,
                        mode: isPieChart ? 'point' : 'index',
                        intersect: false,
                        callbacks: isPieChart ? {
                            label: function(tooltipItem, data) {
                                const label = data.labels[tooltipItem.index] || '';
                                const value = data.datasets[0].data[tooltipItem.index] || 0;
                                const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        } : {}
                    },
                    // Efectos 3D para gráficas circulares
                    ...(isPieChart ? {
                        animation: {
                            animateRotate: true,
                            animateScale: true,
                            duration: 1500,
                            easing: 'easeOutQuart'
                        },
                        elements: {
                            arc: {
                                borderWidth: 3,
                                borderColor: '#ffffff'
                            }
                        },
                        cutoutPercentage: chartType === 'doughnut' ? 60 : 0
                    } : {})
                }
            };
        }
        
        oldChartInstance = new Chart(ctx, chartConfig);
    } catch (error) {
        console.error('Error al crear la gráfica:', error);
        const container = document.getElementById('module-chart-container');
        if (container) {
            container.innerHTML = '<p class="text-red-500 text-center py-8">Error al crear la gráfica: ' + error.message + '. Por favor, recarga la página.</p>';
        }
    }
}

// Mostrar residuos por defecto al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Esperar a que Chart.js esté disponible antes de mostrar el módulo
    function initMonitoring(attempts = 0) {
        if (typeof Chart !== 'undefined') {
            showModule('residuos');
        } else if (attempts < 15) {
            // Esperar hasta 7.5 segundos (15 intentos x 500ms)
            setTimeout(() => initMonitoring(attempts + 1), 500);
        } else {
            // Intentar cargar Chart.js si no está disponible después de esperar
            if (typeof loadChartJS === 'function') {
                loadChartJS().then(() => {
                    showModule('residuos');
                }).catch(() => {
                    const expanded = document.getElementById('module-expanded');
                    if (expanded) {
                        expanded.classList.remove('hidden');
                        expanded.innerHTML = '<div class="bg-red-50 border border-red-200 rounded-lg p-6"><p class="text-red-600 text-center">Error: No se pudo cargar Chart.js. Por favor, verifica tu conexión a internet y recarga la página.</p></div>';
                    }
                });
            } else {
                console.error('Chart.js no está disponible después de esperar');
                const expanded = document.getElementById('module-expanded');
                if (expanded) {
                    expanded.classList.remove('hidden');
                    expanded.innerHTML = '<div class="bg-red-50 border border-red-200 rounded-lg p-6"><p class="text-red-600 text-center">Error: Chart.js no está disponible. Por favor, recarga la página.</p></div>';
                }
            }
        }
    }
    
    // Iniciar después de un pequeño delay
    setTimeout(() => initMonitoring(), 500);
});
</script>

<script>
// Función para mostrar/ocultar fechas personalizadas
function toggleDateInputs(select) {
    const customDateFilters = document.getElementById('custom-date-filters');
    if (select.value === 'custom') {
        customDateFilters.classList.remove('hidden');
        customDateFilters.classList.add('flex');
    } else {
        customDateFilters.classList.add('hidden');
        customDateFilters.classList.remove('flex');
    }
}
</script>

<style>
/* Fila de filtros (fechas): ancho completo y no invade la tabla */
.monitoring-filters-row {
    width: 100%;
    display: block;
}

/* Bloque del historial: separado de los filtros de fecha para evitar solapamiento */
.monitoring-history-block {
    position: relative;
    z-index: 1;
    margin-top: 1rem;
    width: 100%;
}
.monitoring-datatable-container {
    width: 100%;
}
.monitoring-datatable-container .dataTables_wrapper {
    isolation: isolate;
}

/* Fila superior del DataTable: solo "Mostrar X registros", en bloque para que no se solape el 5 con la flecha */
.monitoring-datatable-container .dataTables_wrapper .monitoring-dt-top,
.dataTables_wrapper .monitoring-dt-top {
    display: block;
    width: 100%;
    margin-bottom: 0.75rem;
    clear: both;
}

/* Estilos para DataTables - fila "Mostrar X registros" en un solo bloque, sin solapamiento */
.dataTables_wrapper {
    position: relative;
    clear: both;
    width: 100%;
}

.dataTables_wrapper .dataTables_length {
    float: left;
    margin-bottom: 0;
    margin-left: 0;
    padding: 0.5rem 0.75rem 0.5rem 0;
    clear: none;
}

.dataTables_wrapper .dataTables_length label {
    font-weight: 500;
    color: #374151;
    margin: 0;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}

/* Select "Mostrar X registros": ancho suficiente para el número + espacio, sin flecha superpuesta */
#module-history .dataTables_wrapper .dataTables_length label select,
.monitoring-datatable-container .dataTables_wrapper .dataTables_length label select,
.dataTables_wrapper .dataTables_length label select {
    display: inline-block !important;
    margin: 0 !important;
    margin-right: 0.5rem !important;
    padding: 0.375rem 0.5rem !important; /* Tight padding */
    border: 1px solid #d1d5db !important;
    border-radius: 0.375rem !important;
    font-size: 0.875rem !important;
    min-width: 3rem !important;
    width: auto !important;
    text-align: center !important;
    text-align-last: center !important;
    background: #fff !important; /* Wipe all background properties */
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
}

/* Force hiding of IE/Edge native arrow on select */
#module-history .dataTables_wrapper .dataTables_length label select::-ms-expand,
.monitoring-datatable-container .dataTables_wrapper .dataTables_length label select::-ms-expand,
.dataTables_wrapper .dataTables_length label select::-ms-expand {
    display: none !important;
}
/* Ocultar cualquier flecha/icono que DataTables o el navegador añada al select */
#module-history .dataTables_length label::after,
#module-history .dataTables_length label::before,
#module-history .dataTables_length .select2-container,
#module-history .dataTables_length svg,
#module-history .dataTables_length [class*="dropdown"] {
    display: none !important;
}

.dataTables_wrapper .dataTables_info {
    padding: 0.75rem 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_paginate {
    padding: 0.75rem 0;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.5rem 0.75rem;
    margin: 0 0.25rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background-color: white;
    color: #374151;
    cursor: pointer;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #22c55e;
    color: white;
    border-color: #22c55e;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Estilos para gráfica circular 3D mejorada */
#module-chart-container canvas {
    filter: drop-shadow(0px 10px 20px rgba(0, 0, 0, 0.2));
    transition: transform 0.3s ease;
}

#module-chart-container canvas:hover {
    transform: scale(1.02);
}

/* Mejorar la apariencia de la leyenda para gráficas circulares */
#module-expanded .chartjs-legend {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

#module-expanded .chartjs-legend li {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    border-radius: 8px;
    transition: background-color 0.2s ease;
}

#module-expanded .chartjs-legend li:hover {
    background-color: rgba(0, 0, 0, 0.05);
}
</style>

@endsection

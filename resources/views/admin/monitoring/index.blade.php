@extends('layouts.master')

@section('content')
@vite(['resources/css/dashboard-admin.css'])

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="dashboard-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="welcome-title">
                    <i class="fas fa-chart-line text-green-600 mr-3"></i>
                    Módulo de Monitoreo
                </h1>
                <p class="welcome-subtitle">
                    <i class="fas fa-user-shield text-green-500 mr-2"></i>
                    {{ Auth::user()?->name ?? 'Usuario' }} - Supervisión de Todos los Módulos
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-600 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Filtros de Período -->
    <div class="bg-green-50 rounded-xl shadow-sm p-6 mb-8 border border-green-200 animate-fade-in-up animate-delay-1 mt-6">
        <form method="GET" action="{{ route('admin.monitoring.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                <select name="period" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Diario</option>
                    <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Semanal</option>
                    <option value="biweekly" {{ $period === 'biweekly' ? 'selected' : '' }}>Quincenal</option>
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Mensual</option>
                    <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Anual</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                <input type="date" name="start_date" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                <input type="date" name="end_date" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Cards de Módulos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card Residuos -->
        <div onclick="showModule('residuos')" class="module-card bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-300 rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200" id="card-residuos">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="bg-green-500 text-white rounded-lg p-3">
                        <i class="fas fa-recycle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Residuos</h3>
                        <p class="text-xs text-gray-600">{{ $stats['total_organics'] }} registros</p>
                    </div>
                </div>
            </div>
            <div class="text-2xl font-bold text-green-700">{{ number_format($stats['total_organics_weight'], 1) }} Kg</div>
        </div>

        <!-- Card Pilas -->
        <div onclick="showModule('pilas')" class="module-card bg-gradient-to-br from-cyan-50 to-cyan-100 border-2 border-cyan-300 rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200" id="card-pilas">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="bg-cyan-500 text-white rounded-lg p-3">
                        <i class="fas fa-mountain text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Pilas</h3>
                        <p class="text-xs text-gray-600">{{ $stats['total_compostings'] }} total</p>
                    </div>
                </div>
            </div>
            <div class="text-2xl font-bold text-cyan-700">{{ $stats['active_compostings'] }} activas</div>
        </div>

        <!-- Card Abono -->
        <div onclick="showModule('abono')" class="module-card bg-gradient-to-br from-yellow-50 to-yellow-100 border-2 border-yellow-300 rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200" id="card-abono">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="bg-yellow-500 text-white rounded-lg p-3">
                        <i class="fas fa-seedling text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Abono</h3>
                        <p class="text-xs text-gray-600">{{ $stats['total_fertilizers'] }} registros</p>
                    </div>
                </div>
            </div>
            <div class="text-2xl font-bold text-yellow-700">{{ number_format($stats['total_fertilizers_amount'], 1) }} Kg/L</div>
        </div>

        <!-- Card Maquinaria -->
        <div onclick="showModule('maquinaria')" class="module-card bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-300 rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200" id="card-maquinaria">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="bg-purple-500 text-white rounded-lg p-3">
                        <i class="fas fa-cogs text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Maquinaria</h3>
                        <p class="text-xs text-gray-600">{{ $stats['total_machinery'] }} equipos</p>
                    </div>
                </div>
            </div>
            <div class="text-2xl font-bold text-purple-700">{{ $stats['total_machinery'] }} unidades</div>
        </div>
    </div>

    <!-- Sección Expandida de Módulo (Oculta por defecto) -->
    <div id="module-expanded" class="hidden mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h2 id="module-title" class="text-2xl font-bold text-gray-800"></h2>
                <div class="flex items-center space-x-3">
                    <button onclick="closeModule()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Cerrar
                    </button>
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
            <!-- Historial de Registros -->
            <div id="module-history" class="mt-6">
                <!-- Contenido dinámico del historial -->
            </div>
        </div>
    </div>

    <!-- Gráficas Resumen Pequeñas -->
    <div id="small-charts-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
        <!-- Residuos Diarios -->
        <div class="bg-white rounded-lg shadow-sm p-3 border border-gray-200">
            <h2 class="text-xs font-semibold text-green-600 mb-2 flex items-center justify-between">
                <span class="flex items-center">
                    <i class="fas fa-recycle text-green-600 mr-1 text-xs"></i>
                    Peso de Residuos Registrados
                </span>
                <span id="residuos-trend" class="text-xs font-bold"></span>
            </h2>
            <div style="height: 80px;">
                <canvas id="organicsByDateChart"></canvas>
            </div>
        </div>

        <!-- Pilas por Estado -->
        <div class="bg-white rounded-lg shadow-sm p-3 border border-gray-200">
            <h2 class="text-xs font-semibold text-green-600 mb-2 flex items-center justify-between">
                <span class="flex items-center">
                    <i class="fas fa-mountain text-cyan-600 mr-1 text-xs"></i>
                    Estado de Pilas
                </span>
                <span id="pilas-trend" class="text-xs font-bold"></span>
            </h2>
            <div style="height: 80px;">
                <canvas id="compostingByStatusChart"></canvas>
            </div>
        </div>

        <!-- Abonos por Tipo -->
        <div class="bg-white rounded-lg shadow-sm p-3 border border-gray-200">
            <h2 class="text-xs font-semibold text-green-600 mb-2 flex items-center justify-between">
                <span class="flex items-center">
                    <i class="fas fa-seedling text-yellow-600 mr-1 text-xs"></i>
                    Producción de Abonos
                </span>
                <span id="abono-trend" class="text-xs font-bold"></span>
            </h2>
            <div style="height: 80px;">
                <canvas id="fertilizersByTypeChart"></canvas>
            </div>
        </div>

        <!-- Estado Maquinaria -->
        <div class="bg-white rounded-lg shadow-sm p-3 border border-gray-200">
            <h2 class="text-xs font-semibold text-green-600 mb-2 flex items-center justify-between">
                <span class="flex items-center">
                    <i class="fas fa-cogs text-purple-600 mr-1 text-xs"></i>
                    Estado Maquinaria
                </span>
                <span id="maquinaria-trend" class="text-xs font-bold"></span>
            </h2>
            <div style="height: 80px;">
                <canvas id="machineryStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Variable global para módulo actual
let currentModule = '';

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
            data: @json($organicData['by_type']),
            records: @json($organicRecords)
        },
        'pilas': {
            title: '<i class="fas fa-mountain text-cyan-600 mr-2"></i>Historial de Pilas de Compostaje',
            pdf: 'pilas',
            data: @json($compostingData['by_date']),
            records: @json($compostingRecords)
        },
        'abono': {
            title: '<i class="fas fa-seedling text-yellow-600 mr-2"></i>Historial de Abonos',
            pdf: 'abono',
            data: @json($fertilizerData['by_date']),
            records: @json($fertilizerRecords)
        },
        'maquinaria': {
            title: '<i class="fas fa-cogs text-purple-600 mr-2"></i>Estado de Maquinaria',
            pdf: 'maquinaria',
            data: @json($machineryData['by_status']),
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
    
    // Limpiar canvas anterior si existe
    const oldCanvas = document.getElementById('module-chart');
    if (oldChartInstance) {
        oldChartInstance.destroy();
    }
    
    expanded.classList.remove('hidden');
    
    // Crear gráfica después de un pequeño delay
    setTimeout(() => {
        createExpandedChart('module-chart', moduleData.data, module);
        showModuleHistory(module, moduleData.records);
    }, 100);
}

let oldChartInstance = null;

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
        oldChartInstance.destroy();
        oldChartInstance = null;
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
    
    let html = '<div class="mt-6"><h3 class="text-lg font-bold text-gray-800 mb-4">Historial de Entradas</h3>';
    html += '<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">';
    
    if (module === 'residuos') {
        html += '<thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peso (Kg)</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Creado por</th></tr></thead>';
        html += '<tbody class="bg-white divide-y divide-gray-200">';
        records.forEach(record => {
            const date = new Date(record.date || record.created_at).toLocaleDateString('es-ES');
            // Usar type_in_spanish si está disponible, sino traducir manualmente
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
            const status = record.status || 'N/A';
            const statusClass = status === 'Operativa' ? 'bg-green-100 text-green-800' : 
                               status === 'Mantenimiento requerido' ? 'bg-red-100 text-red-800' : 
                               'bg-yellow-100 text-yellow-800';
            html += `<tr class="hover:bg-gray-50"><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">${record.name || 'N/A'}</td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.brand || 'N/A'}</td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.model || 'N/A'}</td><td class="px-4 py-3 whitespace-nowrap text-sm"><span class="px-2 py-1 text-xs rounded-full ${statusClass}">${status}</span></td><td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.location || 'N/A'}</td></tr>`;
        });
    }
    
    html += '</tbody></table></div></div>';
    historyContainer.innerHTML = html;
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
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    // Destruir gráfica anterior si existe
    if (oldChartInstance) {
        oldChartInstance.destroy();
    }
    
    let labels, values, chartType, datasets;
    
    if (module === 'residuos') {
        // Para residuos, mostrar por tipo con sus nombres en español
        const typeMap = {
            'Kitchen': 'Cocina',
            'Beds': 'Camas',
            'Leaves': 'Hojas',
            'CowDung': 'Estiércol de Vaca',
            'ChickenManure': 'Estiércol de Pollo',
            'PigManure': 'Estiércol de Cerdo',
            'Other': 'Otro'
        };
        
        labels = Object.keys(data).map(key => typeMap[key] || key);
        values = Object.values(data).map(item => item.count || item);
        chartType = 'bar';
        
        datasets = [{
            label: 'Cantidad de Registros',
            data: values,
            borderColor: colors.successBorder,
            backgroundColor: colors.success,
            borderWidth: 2
        }, {
            label: 'Peso Total (Kg)',
            data: Object.values(data).map(item => item.weight || 0),
            borderColor: colors.infoBorder,
            backgroundColor: colors.info,
            borderWidth: 2,
            yAxisID: 'y1'
        }];
    } else {
        labels = Object.keys(data);
        values = Object.values(data);
        chartType = 'line';
        
        datasets = [{
            label: module === 'maquinaria' ? 'Cantidad' : 'Registros',
            data: values,
            borderColor: module === 'pilas' ? colors.infoBorder :
                        module === 'abono' ? colors.warningBorder : colors.purpleBorder,
            backgroundColor: module === 'pilas' ? colors.info :
                            module === 'abono' ? colors.warning : colors.purple,
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 5
        }];
    }
    
    const chartConfig = {
        type: chartType,
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: module === 'residuos' ? 'Cantidad de Registros' : 'Cantidad'
                    }
                },
                y1: module === 'residuos' ? {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Peso (Kg)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                } : undefined,
                x: {
                    title: {
                        display: true,
                        text: module === 'residuos' ? 'Tipo de Residuo' : 'Período'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    };
    
    // Remover y1 si no es residuos
    if (module !== 'residuos' && chartConfig.options.scales.y1) {
        delete chartConfig.options.scales.y1;
    }
    
    oldChartInstance = new Chart(ctx, chartConfig);
}

// Gráficas pequeñas
const organicsByDateData = @json($organicData['by_date']);
const organicsByDateLabels = Object.keys(organicsByDateData);
const organicsByDateValues = Object.values(organicsByDateData);
const residuosTrend = calculateTrend(organicsByDateData);
document.getElementById('residuos-trend').innerHTML = 
    residuosTrend.direction === 'up' ? `<span class="text-green-600">↑ ${residuosTrend.percent}%</span>` :
    residuosTrend.direction === 'down' ? `<span class="text-red-600">↓ ${residuosTrend.percent}%</span>` :
    `<span class="text-gray-600">→ 0%</span>`;

new Chart(document.getElementById('organicsByDateChart'), {
    type: 'line',
    data: {
        labels: organicsByDateLabels,
        datasets: [{
            label: 'Peso (Kg)',
            data: organicsByDateValues,
            borderColor: colors.successBorder,
            backgroundColor: colors.success,
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, display: false },
            x: { display: false }
        },
        plugins: { legend: { display: false } }
    }
});

const compostingByStatusData = @json($compostingData['by_status']);
const pilasTrend = { percent: ((compostingByStatusData.completed / (compostingByStatusData.active + compostingByStatusData.completed || 1)) * 100).toFixed(1), direction: 'up' };
document.getElementById('pilas-trend').innerHTML = `<span class="text-cyan-600">${pilasTrend.percent}% completadas</span>`;

new Chart(document.getElementById('compostingByStatusChart'), {
    type: 'line',
    data: {
        labels: ['Activas', 'Completadas'],
        datasets: [{
            label: 'Pilas',
            data: [compostingByStatusData.active, compostingByStatusData.completed],
            borderColor: colors.infoBorder,
            backgroundColor: colors.info,
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, display: false },
            x: { display: false }
        },
        plugins: { legend: { display: false } }
    }
});

const fertilizersByTypeData = @json($fertilizerData['by_type']);
const fertilizersByTypeLabels = Object.keys(fertilizersByTypeData).map(type => type === 'Liquid' ? 'Líquido' : 'Sólido');
const fertilizersByTypeCounts = Object.values(fertilizersByTypeData).map(item => item.count);
const abonoTrend = calculateTrend(fertilizersByTypeData);
document.getElementById('abono-trend').innerHTML = 
    abonoTrend.direction === 'up' ? `<span class="text-yellow-600">↑ ${abonoTrend.percent}%</span>` :
    abonoTrend.direction === 'down' ? `<span class="text-red-600">↓ ${abonoTrend.percent}%</span>` :
    `<span class="text-gray-600">→ 0%</span>`;

new Chart(document.getElementById('fertilizersByTypeChart'), {
    type: 'line',
    data: {
        labels: fertilizersByTypeLabels,
        datasets: [{
            label: 'Abonos',
            data: fertilizersByTypeCounts,
            borderColor: colors.warningBorder,
            backgroundColor: colors.warning,
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, display: false },
            x: { display: false }
        },
        plugins: { legend: { display: false } }
    }
});

const machineryStatusData = @json($machineryData['by_status']);
const maquinariaTrend = { percent: ((machineryStatusData['Operativa'] || 0) / (Object.values(machineryStatusData).reduce((a, b) => a + b, 0) || 1) * 100).toFixed(1), direction: 'up' };
document.getElementById('maquinaria-trend').innerHTML = `<span class="text-purple-600">${maquinariaTrend.percent}% operativa</span>`;

new Chart(document.getElementById('machineryStatusChart'), {
    type: 'line',
    data: {
        labels: Object.keys(machineryStatusData),
        datasets: [{
            label: 'Maquinaria',
            data: Object.values(machineryStatusData),
            borderColor: colors.orangeBorder,
            backgroundColor: colors.orange,
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, display: false },
            x: { display: false }
        },
        plugins: { legend: { display: false } }
    }
});
</script>

@endsection

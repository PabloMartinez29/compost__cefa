@extends('layouts.master')

@section('content')
@vite(['resources/css/dashboard-admin.css'])

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="bg-green-50 rounded-xl shadow-sm p-6 border border-green-200 animate-fade-in-up">
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

    <!-- Filtros de Período (zona separada; no se solapa con el DataTable del historial) -->
    <div class="bg-green-50 rounded-xl shadow-sm p-6 mb-8 border border-green-200 animate-fade-in-up animate-delay-1 mt-6 monitoring-filters-row">
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
            data: @json($organicDataGeneral['by_type'] ?? []),
            records: @json($organicRecords)
        },
        'pilas': {
            title: '<i class="fas fa-mountain text-cyan-600 mr-2"></i>Historial de Pilas de Compostaje',
            pdf: 'pilas',
            // Usamos los datos por estado GLOBAL (sin filtro de fechas) para que siempre
            // se vean las pilas activas/completadas aunque se hayan creado en otro período.
            data: @json($compostingDataGeneral['by_status'] ?? []),
            records: @json($compostingRecords)
        },
        'abono': {
            title: '<i class="fas fa-seedling text-yellow-600 mr-2"></i>Historial de Abonos',
            pdf: 'abono',
            // Usamos la tendencia general por fecha (sin filtrar por período)
            data: @json($fertilizerDataGeneral['by_date'] ?? []),
            records: @json($fertilizerRecords)
        },
        'maquinaria': {
            title: '<i class="fas fa-cogs text-purple-600 mr-2"></i>Estado de Maquinaria',
            pdf: 'maquinaria',
            {{-- Para la gráfica solo mostraremos:
                 - Maquinaria operativa
                 - Maquinaria registrada (total de equipos)
            --}}
            data: @json([
                'Operativa' => $machineryData['by_status']['Operativa'] ?? 0,
                'Maquinaria registrada' => $machineryData['total'] ?? 0,
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
        oldChartInstance.destroy();
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
    html += '<div class="overflow-x-auto"><table id="history-table" class="min-w-full divide-y divide-gray-200">';
    
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
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            dom: '<"monitoring-dt-top"l>rtip', // l en fila aparte; r=processing, t=tabla, i=info, p=paginación
            language: {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
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
    
    let labels = [];
    let values = [];
    let chartType = 'line';
    let datasets = [];
    
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
        const weights = [];
        
        allTypes.forEach(type => {
            labels.push(typeMap[type]);
            if (dataMap[type] && dataMap[type].weight !== undefined) {
                weights.push(dataMap[type].weight);
            } else {
                weights.push(0);
            }
        });
        
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
        // Para pilas mostramos barras de estado (en proceso vs completadas)
        labels = ['Pilas en proceso', 'Pilas completadas'];
        values = [data?.active || 0, data?.completed || 0];
        chartType = 'bar';
        
        datasets = [{
            label: 'Registros',
            data: values,
            borderColor: [colors.infoBorder, colors.successBorder],
            backgroundColor: [colors.info, colors.success],
            borderWidth: 2,
            fill: false,
            tension: 0,
            pointRadius: 0,
            // Barras más delgadas para que se vean más estilizadas
            barPercentage: 0.4,
            categoryPercentage: 0.5,
            maxBarThickness: 40
        }];
    } else {
        // Abono y Maquinaria
        labels = Object.keys(data || {});
        values = Object.values(data || {});

        if (module === 'abono') {
            // Si abono tiene un solo punto, duplicamos para que se vea claramente la línea
            if (labels.length === 1) {
                labels = [labels[0], labels[0]];
                values = [values[0], values[0]];
            }

            chartType = 'line';
            
            // Estilo tipo trading: línea fina sin relleno, puntos pequeños
            datasets = [{
                label: 'Registros',
                data: values,
                borderColor: colors.warningBorder,
                backgroundColor: 'rgba(0,0,0,0)',
                borderWidth: 2,
                fill: false,
                tension: 0.25,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointBackgroundColor: colors.warningBorder,
                pointBorderColor: '#ffffff'
            }];
        } else {
            // Maquinaria: barras por estado
            chartType = 'bar';

            const statusBgColors = {
                'Operativa': colors.success,
                'Mantenimiento requerido': colors.danger,
                'Sin mantenimiento registrado': colors.warning
            };
            const statusBorderColors = {
                'Operativa': colors.successBorder,
                'Mantenimiento requerido': colors.dangerBorder,
                'Sin mantenimiento registrado': colors.warningBorder
            };

            const bgColors = labels.map(label => statusBgColors[label] || colors.purple);
            const borderColors = labels.map(label => statusBorderColors[label] || colors.purpleBorder);

            datasets = [{
                label: 'Cantidad de equipos',
                data: values,
                backgroundColor: bgColors,
                borderColor: borderColors,
                borderWidth: 2,
                barPercentage: 0.5,
                categoryPercentage: 0.6,
                maxBarThickness: 50
            }];
        }
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
                        text: module === 'residuos' ? 'Peso (Kg)' : 'Cantidad'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: module === 'residuos'
                            ? 'Tipo de Residuo'
                            : (module === 'pilas'
                                ? 'Estado de las pilas'
                                : (module === 'maquinaria' ? 'Estado de la maquinaria' : 'Período'))
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
    
    oldChartInstance = new Chart(ctx, chartConfig);
}

// Mostrar residuos por defecto al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    showModule('residuos');
});
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
    padding: 0.5rem 0.75rem 0.5rem 0.75rem !important;
    border: 1px solid #d1d5db !important;
    border-radius: 0.375rem !important;
    font-size: 0.875rem !important;
    min-width: 4rem !important;
    width: auto !important;
    text-align: center !important;
    background-color: #fff !important;
    background-image: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
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
</style>

@endsection

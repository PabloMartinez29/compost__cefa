<?php

// Controlador Admin MonitoringController — Monitoreo de pilas en tiempo real
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organic;
use App\Models\Composting;
use App\Models\Tracking;
use App\Models\Fertilizer;
use App\Models\WarehouseClassification;
use App\Models\User;
use App\Models\Machinery;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    // Display the monitoring dashboard
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly'); // daily, weekly, biweekly, monthly, yearly
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        if ($startDate && $endDate) {
            $period = 'daily'; // Si hay fechas, forzamos diario para mejorar la granularidad
        }
        
        // Determinar fechas según el período
        $dates = $this->getDateRange($period, $request->get('start_date'), $request->get('end_date'));
        $startDate = $dates['start'];
        $endDate = $dates['end'];
        
        // Estadísticas generales
        $stats = [
            'total_users' => User::count(),
            'total_apprentices' => User::where('role', 'aprendiz')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_organics' => Organic::count(),
            'total_organics_weight' => Organic::sum('weight'),
            'total_compostings' => Composting::count(),
            'active_compostings' => Composting::whereNull('end_date')->count(),
            'completed_compostings' => Composting::whereNotNull('end_date')->count(),
            'total_trackings' => Tracking::count(),
            'total_fertilizers' => Fertilizer::count(),
            'total_fertilizers_amount' => Fertilizer::sum('amount'),
            'total_machinery' => Machinery::count(),
        ];
        
        // Datos para gráficas de residuos orgánicos (filtrados por período - para sección expandida)
        $organicData = $this->getOrganicData($startDate, $endDate, $period);
        
        // Datos generales para gráficas pequeñas (sin filtrar por período)
        $organicDataGeneral = $this->getOrganicDataGeneral();
        
        // Datos para gráficas de pilas de compostaje (filtrados por período - para sección expandida)
        $compostingData = $this->getCompostingData($startDate, $endDate, $period);
        
        // Datos generales para gráficas pequeñas de pilas
        $compostingDataGeneral = $this->getCompostingDataGeneral();
        
        // Datos para gráficas de seguimientos
        $trackingData = $this->getTrackingData($startDate, $endDate, $period);
        
        // Datos para gráficas de abonos (filtrados por período - para sección expandida)
        $fertilizerData = $this->getFertilizerData($startDate, $endDate, $period);
        
        // Datos generales para gráficas pequeñas de abonos
        $fertilizerDataGeneral = $this->getFertilizerDataGeneral();
        
        // Datos de bodega
        $warehouseData = $this->getWarehouseData();
        
        // Datos de maquinaria
        $machineryData = $this->getMachineryData();
        
        // Actividad por usuario (admin y aprendiz)
        $userActivity = $this->getUserActivity($startDate, $endDate);
        
        // Obtener registros individuales para el historial
        // Residuos: filtrar por el período seleccionado
        $organicRecords = Organic::with('creator')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($organic) {
                // Asegurar que el campo type_in_spanish esté disponible
                $organic->type_in_spanish = $organic->type_in_spanish;
                return $organic;
            });
        
        $compostingRecords = Composting::with('creator')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $fertilizerRecords = Fertilizer::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('date', 'desc')
            ->get();
        
        // Obtener registros de maquinaria con relaciones necesarias para calcular el estado
        // El atributo 'status' se incluye automáticamente gracias a $appends en el modelo
        $machineryRecords = Machinery::with('maintenances', 'usageControls')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Mostrar vista
        return view('admin.monitoring.index', compact(
            'stats',
            'organicData',
            'organicDataGeneral',
            'compostingData',
            'compostingDataGeneral',
            'trackingData',
            'fertilizerData',
            'fertilizerDataGeneral',
            'warehouseData',
            'machineryData',
            'userActivity',
            'period',
            'startDate',
            'endDate',
            'organicRecords',
            'compostingRecords',
            'fertilizerRecords',
            'machineryRecords'
        ));
    }
    
    // Get date range based on period
    private function getDateRange($period, $startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            return [
                'start' => Carbon::parse($startDate)->startOfDay(),
                'end' => Carbon::parse($endDate)->endOfDay()
            ];
        }
        
        switch ($period) {
            case 'daily':
                return [
                    'start' => Carbon::today()->startOfDay(),
                    'end' => Carbon::today()->endOfDay()
                ];
            case 'weekly':
                return [
                    'start' => Carbon::now()->startOfWeek(),
                    'end' => Carbon::now()->endOfWeek()
                ];
            case 'biweekly':
                $start = Carbon::now()->subDays(14)->startOfDay();
                return [
                    'start' => $start,
                    'end' => Carbon::now()->endOfDay()
                ];
            case 'monthly':
                return [
                    'start' => Carbon::now()->startOfMonth(),
                    'end' => Carbon::now()->endOfMonth()
                ];
            case 'yearly':
                return [
                    'start' => Carbon::now()->startOfYear(),
                    'end' => Carbon::now()->endOfYear()
                ];
            default:
                return [
                    'start' => Carbon::now()->startOfMonth(),
                    'end' => Carbon::now()->endOfMonth()
                ];
        }
    }
    
    // Get organic waste data for charts
    private function getOrganicData($startDate, $endDate, $period)
    {
        $organics = Organic::with('creator')->whereBetween('created_at', [$startDate, $endDate])->get();
        
        // Por tipo
        $byType = $organics->groupBy('type')->map(function($group) {
            return [
                'count' => $group->count(),
                'weight' => $group->sum('weight')
            ];
        });
        
        // Por fecha (agrupado según período) - incluir peso total
        $byDate = $this->groupByPeriodWithWeight($organics, $startDate, $endDate, $period, 'created_at');
        
        // Por usuario (admin vs aprendiz)
        $byUser = $organics->groupBy(function($organic) {
            return $organic->creator ? $organic->creator->role : 'unknown';
        })->map(function($group) {
            return [
                'count' => $group->count(),
                'weight' => $group->sum('weight')
            ];
        });
        
        return [
            'by_type' => $byType,
            'by_date' => $byDate,
            'by_user' => $byUser,
            'total' => $organics->count(),
            'total_weight' => $organics->sum('weight')
        ];
    }
    
    // Get organic waste data general (all records,
    private function getOrganicDataGeneral()
    {
        $organics = Organic::with('creator')->get();
        
        // Por tipo (para gráfica expandida)
        $byType = $organics->groupBy('type')->map(function($group) {
            return [
                'count' => $group->count(),
                'weight' => $group->sum('weight')
            ];
        });
        
        // Agrupar por mes para mostrar tendencia general
        $byDate = $organics->groupBy(function($organic) {
            return $organic->created_at->format('Y-m');
        })->map(function($group) {
            return $group->sum('weight');
        })->sortKeys();
        
        return [
            'by_type' => $byType->toArray(),
            'by_date' => $byDate->toArray()
        ];
    }
    
    // Get composting data general (all records, for
    private function getCompostingDataGeneral()
    {
        $compostings = Composting::with('creator')->get();
        
        // Por estado
        $byStatus = [
            'active' => $compostings->whereNull('end_date')->count(),
            'completed' => $compostings->whereNotNull('end_date')->count()
        ];
        
        return [
            'by_status' => $byStatus
        ];
    }
    
    // Get fertilizer data general (all records, for
    private function getFertilizerDataGeneral()
    {
        $fertilizers = Fertilizer::all();
        
        // Por tipo
        $byType = $fertilizers->groupBy('type')->map(function($group) {
            return [
                'count' => $group->count(),
                'amount' => $group->sum('amount')
            ];
        });

        // Agrupar por mes para mostrar tendencia general (cantidad total de abono)
        $byDate = $fertilizers->groupBy(function($fertilizer) {
            return $fertilizer->date ? $fertilizer->date->format('Y-m') : null;
        })->filter(function($group, $key) {
            // Filtrar posibles claves nulas si hay registros sin fecha
            return !is_null($key);
        })->map(function($group) {
            return $group->sum('amount');
        })->sortKeys();
        
        return [
            'by_type' => $byType->toArray(),
            'by_date' => $byDate->toArray()
        ];
    }
    
    // Get composting data for charts
    private function getCompostingData($startDate, $endDate, $period)
    {
        $compostings = Composting::with('creator')->whereBetween('created_at', [$startDate, $endDate])->get();
        
        // Por fecha
        $byDate = $this->groupByPeriod($compostings, $startDate, $endDate, $period, 'created_at');
        
        // Por estado
        $byStatus = [
            'active' => $compostings->whereNull('end_date')->count(),
            'completed' => $compostings->whereNotNull('end_date')->count()
        ];
        
        // Por usuario
        $byUser = $compostings->groupBy(function($composting) {
            return $composting->creator ? $composting->creator->role : 'unknown';
        })->map(function($group) {
            return $group->count();
        });
        
        return [
            'by_date' => $byDate,
            'by_status' => $byStatus,
            'by_user' => $byUser,
            'total' => $compostings->count()
        ];
    }
    
    // Get tracking data for charts
    private function getTrackingData($startDate, $endDate, $period)
    {
        $trackings = Tracking::whereBetween('created_at', [$startDate, $endDate])->get();
        
        // Por fecha
        $byDate = $this->groupByPeriod($trackings, $startDate, $endDate, $period, 'created_at');
        
        // Por pila
        $byComposting = $trackings->groupBy('composting_id')->map(function($group) {
            return $group->count();
        })->take(10); // Top 10 pilas con más seguimientos
        
        return [
            'by_date' => $byDate,
            'by_composting' => $byComposting,
            'total' => $trackings->count()
        ];
    }
    
    // Get fertilizer data for charts
    private function getFertilizerData($startDate, $endDate, $period)
    {
        $fertilizers = Fertilizer::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->get();
        
        // Por fecha (usando date que es un Carbon date según el cast) - sumar cantidad (amount)
        $byDate = $this->groupByPeriodWithWeight($fertilizers, $startDate, $endDate, $period, 'date', 'amount');
        
        // Por tipo
        $byType = $fertilizers->groupBy('type')->map(function($group) {
            return [
                'count' => $group->count(),
                'amount' => $group->sum('amount')
            ];
        });
        
        return [
            'by_date' => $byDate,
            'by_type' => $byType,
            'total' => $fertilizers->count(),
            'total_amount' => $fertilizers->sum('amount')
        ];
    }
    
    // Get warehouse data
    private function getWarehouseData()
    {
        $inventory = WarehouseClassification::getInventoryByType();
        
        return [
            'by_type' => $inventory,
            'total' => array_sum($inventory)
        ];
    }
    
    // Get machinery data
    private function getMachineryData()
    {
        $machineries = Machinery::all();

        // Normalizamos los estados para que valores nulos o "N/A"
        // cuenten como "Sin mantenimiento registrado"
        $byStatus = [
            'Operativa' => 0,
            'Mantenimiento requerido' => 0,
            'Sin mantenimiento registrado' => 0,
        ];

        foreach ($machineries as $machinery) {
            $status = $machinery->status;

            if ($status === 'Operativa') {
                $byStatus['Operativa']++;
            } elseif ($status === 'Mantenimiento requerido') {
                $byStatus['Mantenimiento requerido']++;
            } else {
                // Cualquier otro valor (null, "N/A", vacío, etc.)
                // se considera como "Sin mantenimiento registrado"
                $byStatus['Sin mantenimiento registrado']++;
            }
        }

        return [
            'by_status' => $byStatus,
            'total' => $machineries->count()
        ];
    }
    
    // Get user activity data
    private function getUserActivity($startDate, $endDate)
    {
        $organics = Organic::whereBetween('created_at', [$startDate, $endDate])->get();
        $compostings = Composting::whereBetween('created_at', [$startDate, $endDate])->get();
        
        $activity = [];
        
        foreach (User::all() as $user) {
            $userOrganics = $organics->where('created_by', $user->id);
            $userCompostings = $compostings->where('created_by', $user->id);
            
            $activity[] = [
                'user' => $user->name,
                'role' => $user->role,
                'organics_count' => $userOrganics->count(),
                'organics_weight' => $userOrganics->sum('weight'),
                'compostings_count' => $userCompostings->count()
            ];
        }
        
        return collect($activity)->sortByDesc('organics_count')->take(10);
    }
    
    // Group data by period
    private function groupByPeriod($collection, $startDate, $endDate, $period, $dateField = 'created_at')
    {
        $grouped = [];
        
        if ($period === 'daily') {
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $key = $current->format('Y-m-d');
                $grouped[$key] = $collection->filter(function($item) use ($current, $dateField) {
                    $itemDate = $this->parseDate($item->$dateField);
                    return $itemDate && $itemDate->format('Y-m-d') === $current->format('Y-m-d');
                })->count();
                $current->addDay();
            }
        } elseif ($period === 'weekly' || $period === 'biweekly') {
            $current = $startDate->copy()->startOfWeek();
            while ($current <= $endDate) {
                $key = 'Semana ' . $current->format('W/Y');
                $weekEnd = $current->copy()->endOfWeek();
                $grouped[$key] = $collection->filter(function($item) use ($current, $weekEnd, $dateField) {
                    $itemDate = $this->parseDate($item->$dateField);
                    return $itemDate && $itemDate >= $current && $itemDate <= $weekEnd;
                })->count();
                $current->addWeek();
            }
        } elseif ($period === 'monthly') {
            $current = $startDate->copy()->startOfMonth();
            while ($current <= $endDate) {
                $key = $current->format('M Y');
                $monthEnd = $current->copy()->endOfMonth();
                $grouped[$key] = $collection->filter(function($item) use ($current, $monthEnd, $dateField) {
                    $itemDate = $this->parseDate($item->$dateField);
                    return $itemDate && $itemDate >= $current && $itemDate <= $monthEnd;
                })->count();
                $current->addMonth();
            }
        } elseif ($period === 'yearly') {
            $current = $startDate->copy()->startOfYear();
            while ($current <= $endDate) {
                $key = $current->format('Y');
                $yearEnd = $current->copy()->endOfYear();
                $grouped[$key] = $collection->filter(function($item) use ($current, $yearEnd, $dateField) {
                    $itemDate = $this->parseDate($item->$dateField);
                    return $itemDate && $itemDate >= $current && $itemDate <= $yearEnd;
                })->count();
                $current->addYear();
            }
        }
        
        return $grouped;
    }
    
    // Group by period with weight (for organics
    private function groupByPeriodWithWeight($collection, $startDate, $endDate, $period, $dateField = 'created_at', $sumColumn = 'weight')
    {
        $grouped = [];
        
        if ($period === 'daily') {
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $key = $current->format('Y-m-d');
                $filtered = $collection->filter(function($item) use ($current, $dateField) {
                    $itemDate = $this->parseDate($item->$dateField);
                    return $itemDate && $itemDate->format('Y-m-d') === $current->format('Y-m-d');
                });
                $grouped[$key] = $filtered->sum($sumColumn);
                $current->addDay();
            }
        } elseif ($period === 'weekly' || $period === 'biweekly') {
            $current = $startDate->copy()->startOfWeek();
            while ($current <= $endDate) {
                $key = 'Semana ' . $current->format('W/Y');
                $weekEnd = $current->copy()->endOfWeek();
                $filtered = $collection->filter(function($item) use ($current, $weekEnd, $dateField) {
                    $itemDate = $this->parseDate($item->$dateField);
                    return $itemDate && $itemDate >= $current && $itemDate <= $weekEnd;
                });
                $grouped[$key] = $filtered->sum($sumColumn);
                $current->addWeek();
            }
        } elseif ($period === 'monthly') {
            $current = $startDate->copy()->startOfMonth();
            while ($current <= $endDate) {
                $key = $current->format('M Y');
                $monthEnd = $current->copy()->endOfMonth();
                $filtered = $collection->filter(function($item) use ($current, $monthEnd, $dateField) {
                    $itemDate = $this->parseDate($item->$dateField);
                    return $itemDate && $itemDate >= $current && $itemDate <= $monthEnd;
                });
                $grouped[$key] = $filtered->sum($sumColumn);
                $current->addMonth();
            }
        } elseif ($period === 'yearly') {
            $current = $startDate->copy()->startOfYear();
            while ($current <= $endDate) {
                $key = $current->format('Y');
                $yearEnd = $current->copy()->endOfYear();
                $filtered = $collection->filter(function($item) use ($current, $yearEnd, $dateField) {
                    $itemDate = $this->parseDate($item->$dateField);
                    return $itemDate && $itemDate >= $current && $itemDate <= $yearEnd;
                });
                $grouped[$key] = $filtered->sum($sumColumn);
                $current->addYear();
            }
        }
        
        return $grouped;
    }
    
    // Parse date from various formats
    private function parseDate($date)
    {
        if (is_null($date)) {
            return null;
        }
        
        if ($date instanceof \Carbon\Carbon) {
            return $date;
        }
        
        if (is_string($date)) {
            try {
                return Carbon::parse($date);
            } catch (\Exception $e) {
                return null;
            }
        }
        
        return null;
    }
    
    // Generate PDF for monitoring report
    public function downloadMonitoringPDF(Request $request)
    {
        $module = $request->get('module', 'residuos');
        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $dates = $this->getDateRange($period, $startDate, $endDate);
        $startDate = $dates['start'];
        $endDate = $dates['end'];
        
        $data = [];
        $title = '';
        
        switch ($module) {
            case 'residuos':
                $data = $this->getOrganicData($startDate, $endDate, $period);
                // Traducir los tipos de residuos al español
                $typeMap = [
                    'Kitchen' => 'Cocina',
                    'Beds' => 'Camas',
                    'Leaves' => 'Hojas',
                    'CowDung' => 'Estiércol de Vaca',
                    'ChickenManure' => 'Estiércol de Pollo',
                    'PigManure' => 'Estiércol de Cerdo',
                    'Other' => 'Otro'
                ];
                // Convertir las claves de by_type a español
                $translatedByType = [];
                foreach ($data['by_type'] as $type => $info) {
                    $translatedType = $typeMap[$type] ?? $type;
                    $translatedByType[$translatedType] = $info;
                }
                $data['by_type'] = $translatedByType;
                $title = 'Historial de Residuos Orgánicos';
                break;
            case 'pilas':
                $data = $this->getCompostingData($startDate, $endDate, $period);
                $title = 'Historial de Pilas de Compostaje';
                break;
            case 'abono':
                $data = $this->getFertilizerData($startDate, $endDate, $period);
                $title = 'Historial de Abonos';
                break;
            case 'maquinaria':
                $data = $this->getMachineryData();
                $title = 'Estado de Maquinaria';
                break;
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.monitoring.pdf.monitoring-report', compact('data', 'title', 'module', 'period', 'startDate', 'endDate'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('monitoreo_' . $module . '_' . date('Y-m-d') . '.pdf');
    }
    
    // Generate Excel export for monitoring report
    public function downloadMonitoringExcel(Request $request)
    {
        $module = $request->get('module', 'residuos');
        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $dates = $this->getDateRange($period, $startDate, $endDate);
        $startDate = $dates['start'];
        $endDate = $dates['end'];
        
        // Usamos HTML simple con estilos básicos para que Excel
        // muestre el contenido con bordes y márgenes agradables.
        $filename = 'monitoreo_' . $module . '_' . date('Y-m-d') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($module, $startDate, $endDate, $period) {
            echo '<meta charset="UTF-8">';
            echo '<style>
                table { border-collapse: collapse; margin: 10px; }
                th, td { border: 1px solid #9ca3af; padding: 6px 10px; font-family: Arial, sans-serif; font-size: 11pt; }
            </style>';

            echo '<table>';

            switch ($module) {
                case 'residuos':
                    $records = Organic::with('creator')
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $headerStyle = ' style="background-color:#bbf7d0;color:#065f46;font-weight:bold;text-align:center;"';

                    echo '<tr>
                        <th' . $headerStyle . '>Fecha</th>
                        <th' . $headerStyle . '>Tipo</th>
                        <th' . $headerStyle . '>Peso (Kg)</th>
                        <th' . $headerStyle . '>Entregado por</th>
                        <th' . $headerStyle . '>Recibido por</th>
                        <th' . $headerStyle . '>Creado por</th>
                        <th' . $headerStyle . '>Notas</th>
                    </tr>';

                    foreach ($records as $record) {
                        $typeMap = [
                            'Kitchen' => 'Cocina',
                            'Beds' => 'Camas',
                            'Leaves' => 'Hojas',
                            'CowDung' => 'Estiércol de Vaca',
                            'ChickenManure' => 'Estiércol de Pollo',
                            'PigManure' => 'Estiércol de Cerdo',
                            'Other' => 'Otro'
                        ];
                        $typeName = $typeMap[$record->type] ?? $record->type;
                        $weight = number_format($record->weight, 2, ',', '');

                        echo '<tr>';
                        echo '<td>' . e($record->date->format('d/m/Y')) . '</td>';
                        echo '<td>' . e($typeName) . '</td>';
                        echo '<td style="text-align:right;">' . e($weight) . '</td>';
                        echo '<td>' . e($record->delivered_by ?? 'N/A') . '</td>';
                        echo '<td>' . e($record->received_by ?? 'N/A') . '</td>';
                        echo '<td>' . e($record->creator ? $record->creator->name : 'N/A') . '</td>';
                        echo '<td>' . e($record->notes ?? '') . '</td>';
                        echo '</tr>';
                    }
                    break;

                case 'pilas':
                    $records = Composting::with('creator')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $headerStyle = ' style="background-color:#bbf7d0;color:#065f46;font-weight:bold;text-align:center;"';

                    echo '<tr>
                        <th' . $headerStyle . '>Fecha Creación</th>
                        <th' . $headerStyle . '>Código</th>
                        <th' . $headerStyle . '>Estado</th>
                        <th' . $headerStyle . '>Fecha Inicio</th>
                        <th' . $headerStyle . '>Fecha Fin</th>
                        <th' . $headerStyle . '>Creado por</th>
                    </tr>';

                    foreach ($records as $record) {
                        $status = $record->end_date ? 'Completada' : 'Activa';
                        echo '<tr>';
                        echo '<td>' . e($record->created_at->format('d/m/Y')) . '</td>';
                        echo '<td>' . e($record->code ?? 'N/A') . '</td>';
                        echo '<td>' . e($status) . '</td>';
                        echo '<td>' . e($record->start_date ? $record->start_date->format('d/m/Y') : 'N/A') . '</td>';
                        echo '<td>' . e($record->end_date ? $record->end_date->format('d/m/Y') : 'N/A') . '</td>';
                        echo '<td>' . e($record->creator ? $record->creator->name : 'N/A') . '</td>';
                        echo '</tr>';
                    }
                    break;

                case 'abono':
                    $records = Fertilizer::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                        ->orderBy('date', 'desc')
                        ->get();

                    $headerStyle = ' style="background-color:#bbf7d0;color:#065f46;font-weight:bold;text-align:center;"';

                    echo '<tr>
                        <th' . $headerStyle . '>Fecha</th>
                        <th' . $headerStyle . '>Tipo</th>
                        <th' . $headerStyle . '>Cantidad</th>
                        <th' . $headerStyle . '>Unidad</th>
                        <th' . $headerStyle . '>Descripción</th>
                    </tr>';

                    foreach ($records as $record) {
                        $amount = number_format($record->amount ?? 0, 2, ',', '');
                        echo '<tr>';
                        echo '<td>' . e($record->date->format('d/m/Y')) . '</td>';
                        echo '<td>' . e($record->type ?? 'N/A') . '</td>';
                        echo '<td style="text-align:right;">' . e($amount) . '</td>';
                        echo '<td>' . e($record->type === 'Liquid' ? 'L' : 'Kg') . '</td>';
                        echo '<td>' . e($record->description ?? '') . '</td>';
                        echo '</tr>';
                    }
                    break;

                case 'maquinaria':
                    $records = Machinery::with('maintenances')->orderBy('created_at', 'desc')->get();

                    $headerStyle = ' style="background-color:#bbf7d0;color:#065f46;font-weight:bold;text-align:center;"';

                    echo '<tr>
                        <th' . $headerStyle . '>Nombre</th>
                        <th' . $headerStyle . '>Marca</th>
                        <th' . $headerStyle . '>Modelo</th>
                        <th' . $headerStyle . '>Serie</th>
                        <th' . $headerStyle . '>Ubicación</th>
                        <th' . $headerStyle . '>Estado</th>
                        <th' . $headerStyle . '>Fecha Inicio Funcionamiento</th>
                        <th' . $headerStyle . '>Frecuencia Mantenimiento</th>
                    </tr>';

                    foreach ($records as $record) {
                        echo '<tr>';
                        echo '<td>' . e($record->name ?? 'N/A') . '</td>';
                        echo '<td>' . e($record->brand ?? 'N/A') . '</td>';
                        echo '<td>' . e($record->model ?? 'N/A') . '</td>';
                        echo '<td>' . e($record->serial ?? 'N/A') . '</td>';
                        echo '<td>' . e($record->location ?? 'N/A') . '</td>';
                        echo '<td>' . e($record->status ?? 'N/A') . '</td>';
                        echo '<td>' . e($record->start_func ? $record->start_func->format('d/m/Y') : 'N/A') . '</td>';
                        echo '<td>' . e($record->maint_freq ?? 'N/A') . '</td>';
                        echo '</tr>';
                    }
                    break;
            }

            echo '</table>';
        };

        return response()->stream($callback, 200, $headers);
    }
}


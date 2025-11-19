<?php

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
    /**
     * Display the monitoring dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly'); // daily, weekly, biweekly, monthly, yearly
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Determinar fechas según el período
        $dates = $this->getDateRange($period, $startDate, $endDate);
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
        // Residuos: mostrar todos en general, sin filtrar por período
        $organicRecords = Organic::with('creator')
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
        
        $machineryRecords = Machinery::with('maintenances')->orderBy('created_at', 'desc')->get();
        
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
    
    /**
     * Get date range based on period
     */
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
    
    /**
     * Get organic waste data for charts
     */
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
    
    /**
     * Get organic waste data general (all records, for small charts)
     */
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
    
    /**
     * Get composting data general (all records, for small charts)
     */
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
    
    /**
     * Get fertilizer data general (all records, for small charts)
     */
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
        
        return [
            'by_type' => $byType
        ];
    }
    
    /**
     * Get composting data for charts
     */
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
    
    /**
     * Get tracking data for charts
     */
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
    
    /**
     * Get fertilizer data for charts
     */
    private function getFertilizerData($startDate, $endDate, $period)
    {
        $fertilizers = Fertilizer::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->get();
        
        // Por fecha (usando date que es un Carbon date según el cast)
        $byDate = $this->groupByPeriod($fertilizers, $startDate, $endDate, $period, 'date');
        
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
    
    /**
     * Get warehouse data
     */
    private function getWarehouseData()
    {
        $inventory = WarehouseClassification::getInventoryByType();
        
        return [
            'by_type' => $inventory,
            'total' => array_sum($inventory)
        ];
    }
    
    /**
     * Get machinery data
     */
    private function getMachineryData()
    {
        $machineries = Machinery::all();
        
        $byStatus = [
            'Operativa' => $machineries->filter(function($m) { return $m->status === 'Operativa'; })->count(),
            'Mantenimiento requerido' => $machineries->filter(function($m) { return $m->status === 'Mantenimiento requerido'; })->count(),
            'Sin mantenimiento registrado' => $machineries->filter(function($m) { return $m->status === 'Sin mantenimiento registrado'; })->count(),
        ];
        
        return [
            'by_status' => $byStatus,
            'total' => $machineries->count()
        ];
    }
    
    /**
     * Get user activity data
     */
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
    
    /**
     * Group data by period
     */
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
    
    /**
     * Group by period with weight (for organics)
     */
    private function groupByPeriodWithWeight($collection, $startDate, $endDate, $period, $dateField = 'created_at')
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
                $grouped[$key] = $filtered->sum('weight');
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
                $grouped[$key] = $filtered->sum('weight');
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
                $grouped[$key] = $filtered->sum('weight');
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
                $grouped[$key] = $filtered->sum('weight');
                $current->addYear();
            }
        }
        
        return $grouped;
    }
    
    /**
     * Parse date from various formats
     */
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
    
    /**
     * Generate PDF for monitoring report
     */
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
    
    /**
     * Generate Excel export for monitoring report
     */
    public function downloadMonitoringExcel(Request $request)
    {
        $module = $request->get('module', 'residuos');
        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $dates = $this->getDateRange($period, $startDate, $endDate);
        $startDate = $dates['start'];
        $endDate = $dates['end'];
        
        $filename = 'monitoreo_' . $module . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($module, $startDate, $endDate, $period) {
            $file = fopen('php://output', 'w');
            
            // BOM para Excel UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            switch ($module) {
                case 'residuos':
                    $records = Organic::with('creator')
                        ->orderBy('created_at', 'desc')
                        ->get();
                    
                    fputcsv($file, ['Fecha', 'Tipo', 'Peso (Kg)', 'Entregado por', 'Recibido por', 'Creado por', 'Notas']);
                    
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
                        
                        fputcsv($file, [
                            $record->date->format('d/m/Y'),
                            $typeName,
                            $record->weight,
                            $record->delivered_by ?? 'N/A',
                            $record->received_by ?? 'N/A',
                            $record->creator ? $record->creator->name : 'N/A',
                            $record->notes ?? ''
                        ]);
                    }
                    break;
                    
                case 'pilas':
                    $records = Composting::with('creator')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->orderBy('created_at', 'desc')
                        ->get();
                    
                    fputcsv($file, ['Fecha Creación', 'Código', 'Estado', 'Fecha Inicio', 'Fecha Fin', 'Creado por']);
                    
                    foreach ($records as $record) {
                        $status = $record->end_date ? 'Completada' : 'Activa';
                        fputcsv($file, [
                            $record->created_at->format('d/m/Y'),
                            $record->code ?? 'N/A',
                            $status,
                            $record->start_date ? $record->start_date->format('d/m/Y') : 'N/A',
                            $record->end_date ? $record->end_date->format('d/m/Y') : 'N/A',
                            $record->creator ? $record->creator->name : 'N/A'
                        ]);
                    }
                    break;
                    
                case 'abono':
                    $records = Fertilizer::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                        ->orderBy('date', 'desc')
                        ->get();
                    
                    fputcsv($file, ['Fecha', 'Tipo', 'Cantidad', 'Unidad', 'Descripción']);
                    
                    foreach ($records as $record) {
                        fputcsv($file, [
                            $record->date->format('d/m/Y'),
                            $record->type ?? 'N/A',
                            $record->amount ?? 0,
                            $record->type === 'Liquid' ? 'L' : 'Kg',
                            $record->description ?? ''
                        ]);
                    }
                    break;
                    
                case 'maquinaria':
                    $records = Machinery::with('maintenances')->orderBy('created_at', 'desc')->get();
                    
                    fputcsv($file, ['Nombre', 'Marca', 'Modelo', 'Serie', 'Ubicación', 'Estado', 'Fecha Inicio Funcionamiento', 'Frecuencia Mantenimiento']);
                    
                    foreach ($records as $record) {
                        fputcsv($file, [
                            $record->name ?? 'N/A',
                            $record->brand ?? 'N/A',
                            $record->model ?? 'N/A',
                            $record->serial ?? 'N/A',
                            $record->location ?? 'N/A',
                            $record->status ?? 'N/A',
                            $record->start_func ? $record->start_func->format('d/m/Y') : 'N/A',
                            $record->maint_freq ?? 'N/A'
                        ]);
                    }
                    break;
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}


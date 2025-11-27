<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use App\Models\Composting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class FertilizerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fertilizers = Fertilizer::with('composting')->orderBy('date', 'desc')->orderBy('time', 'desc')->get();
        
        // Statistics
        $totalAmount = Fertilizer::sum('amount');
        $totalRecords = Fertilizer::count();
        $todayRecords = Fertilizer::whereDate('date', today())->count();
        $todayAmount = Fertilizer::whereDate('date', today())->sum('amount');
        
        return view('admin.fertilizer.index', compact('fertilizers', 'totalAmount', 'totalRecords', 'todayRecords', 'todayAmount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener solo las pilas completadas que no tengan abono registrado
        // Una pila está completada según el mismo criterio usado en el modelo (accessor status)
        $completedCompostings = Composting::whereDoesntHave('fertilizers')
            ->with(['creator', 'trackings'])
            ->get()
            ->filter(function($composting) {
                // Usar el accessor de estado para mantener la misma lógica que en seguimiento de pilas
                return $composting->status === 'Completada';
            })
            ->sortByDesc(function($composting) {
                // Ordenar por end_date si existe, sino por fecha de creación
                return $composting->end_date ? $composting->end_date->timestamp : $composting->created_at->timestamp;
            })
            ->values();
        
        return view('admin.fertilizer.create', compact('completedCompostings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'composting_id' => 'required|exists:compostings,id',
            'date' => 'required|date',
            'time' => 'required',
            'requester' => 'required|string|max:150',
            'destination' => 'required|string|max:150',
            'received_by' => 'required|string|max:150',
            'delivered_by' => 'required|string|max:150',
            'type' => 'required|in:Liquid,Solid',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        // Verificar que la pila esté completada
        $composting = Composting::with('trackings')->findOrFail($request->composting_id);
        
        // Usar el accessor status para mantener la misma lógica que en create()
        if ($composting->status !== 'Completada') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La pila seleccionada no está completada. Solo se pueden registrar abonos de pilas completadas.');
        }

        // Verificar que no exista ya un abono para esta pila
        if (Fertilizer::where('composting_id', $request->composting_id)->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ya existe un registro de abono para esta pila.');
        }

        // Validar que la cantidad no exceda los kilogramos beneficiados disponibles
        $availableKg = $composting->total_kg ?? 0;
        if ($request->amount > $availableKg) {
            return redirect()->back()
                ->withInput()
                ->with('error', "La cantidad solicitada ({$request->amount} " . ($request->type === 'Liquid' ? 'L' : 'Kg') . ") excede los kilogramos beneficiados disponibles ({$availableKg} Kg) de la pila.");
        }

        // Crear el registro de abono
        Fertilizer::create($request->all());

        // Restar la cantidad de los kilogramos beneficiados de la pila
        $newTotalKg = max(0, $availableKg - $request->amount);
        $composting->update(['total_kg' => $newTotalKg]);

        return redirect()->route('admin.fertilizer.index')->with('success', '¡Registro de abono creado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fertilizer $fertilizer)
    {
        $fertilizer->load('composting.creator');
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'id' => $fertilizer->id,
                'formatted_date' => $fertilizer->formatted_date,
                'time' => $fertilizer->time,
                'composting' => $fertilizer->composting ? [
                    'id' => $fertilizer->composting->id,
                    'formatted_pile_num' => $fertilizer->composting->formatted_pile_num,
                ] : null,
                'type' => $fertilizer->type,
                'type_in_spanish' => $fertilizer->type_in_spanish,
                'formatted_amount' => $fertilizer->formatted_amount,
                'amount' => $fertilizer->amount,
                'requester' => $fertilizer->requester,
                'destination' => $fertilizer->destination,
                'received_by' => $fertilizer->received_by,
                'delivered_by' => $fertilizer->delivered_by,
                'notes' => $fertilizer->notes,
            ]);
        }
        
        return view('admin.fertilizer.show', compact('fertilizer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fertilizer $fertilizer)
    {
        $fertilizer->load('composting');
        
        // Si es una petición AJAX, devolver JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'fertilizer' => [
                    'id' => $fertilizer->id,
                    'date' => $fertilizer->date->format('Y-m-d'),
                    'time' => $fertilizer->time,
                    'composting_id' => $fertilizer->composting_id,
                    'composting' => $fertilizer->composting ? [
                        'id' => $fertilizer->composting->id,
                        'formatted_pile_num' => $fertilizer->composting->formatted_pile_num,
                    ] : null,
                    'requester' => $fertilizer->requester,
                    'destination' => $fertilizer->destination,
                    'received_by' => $fertilizer->received_by,
                    'delivered_by' => $fertilizer->delivered_by,
                    'type' => $fertilizer->type,
                    'amount' => $fertilizer->amount,
                    'notes' => $fertilizer->notes,
                ]
            ]);
        }
        
        return view('admin.fertilizer.edit', compact('fertilizer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fertilizer $fertilizer)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'requester' => 'required|string|max:150',
            'destination' => 'required|string|max:150',
            'received_by' => 'required|string|max:150',
            'delivered_by' => 'required|string|max:150',
            'type' => 'required|in:Liquid,Solid',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        $composting = $fertilizer->composting;
        $oldAmount = $fertilizer->amount;
        $newAmount = $request->amount;
        $amountDifference = $newAmount - $oldAmount;

        // Si la cantidad cambió, actualizar los kilogramos beneficiados de la pila
        if ($amountDifference != 0) {
            $currentTotalKg = $composting->total_kg ?? 0;
            
            // Si se aumenta la cantidad, validar que no exceda lo disponible
            if ($amountDifference > 0) {
                $availableKg = $currentTotalKg;
                if ($amountDifference > $availableKg) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "No se puede aumentar la cantidad. Solo hay {$availableKg} Kg disponibles en la pila.");
                }
            }
            
            // Calcular el nuevo total de kilogramos beneficiados
            // Si se aumenta: restar la diferencia
            // Si se disminuye: sumar la diferencia (devolver a la pila)
            $newTotalKg = max(0, $currentTotalKg - $amountDifference);
            $composting->update(['total_kg' => $newTotalKg]);
        }

        $fertilizer->update($request->all());

        return redirect()->route('admin.fertilizer.index')->with('success', '¡Registro de abono actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fertilizer $fertilizer)
    {
        // Devolver la cantidad a los kilogramos beneficiados de la pila
        $composting = $fertilizer->composting;
        if ($composting) {
            $currentTotalKg = $composting->total_kg ?? 0;
            $newTotalKg = $currentTotalKg + $fertilizer->amount;
            $composting->update(['total_kg' => $newTotalKg]);
        }

        $fertilizer->delete();

        return redirect()->route('admin.fertilizer.index')->with('success', '¡Registro de abono eliminado exitosamente!');
    }

    /**
     * Generate PDF for all fertilizers
     */
    public function downloadAllFertilizersPDF()
    {
        $fertilizers = Fertilizer::with('composting')->orderBy('date', 'desc')->orderBy('time', 'desc')->get();
        
        $pdf = PDF::loadView('admin.fertilizer.pdf.all-fertilizers', compact('fertilizers'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('todos_los_abonos_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for individual fertilizer
     */
    public function downloadFertilizerPDF(Fertilizer $fertilizer)
    {
        $fertilizer->load('composting.creator');
        
        $pdf = PDF::loadView('admin.fertilizer.pdf.fertilizer-details', compact('fertilizer'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('abono_' . str_pad($fertilizer->id, 3, '0', STR_PAD_LEFT) . '_' . date('Y-m-d') . '.pdf');
    }
}


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organic;
use App\Models\WarehouseClassification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class OrganicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organics = Organic::with('creator')->orderBy('date', 'desc')->get();
        
        // Statistics
        $totalWeight = Organic::sum('weight');
        $totalRecords = Organic::count();
        $todayRecords = Organic::whereDate('date', today())->count();
        $todayWeight = Organic::whereDate('date', today())->sum('weight');
        
        return view('admin.organic.index', compact('organics', 'totalWeight', 'totalRecords', 'todayRecords', 'todayWeight'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.organic.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:Kitchen,Beds,Leaves,CowDung,ChickenManure,PigManure,Other',
            'weight' => 'required|numeric|min:0.01',
            'delivered_by' => 'required|string|max:100',
            'received_by' => 'required|string|max:100',
            'notes' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('organics'), $imageName);
            $data['img'] = 'organics/' . $imageName;
        }

        // Agregar el ID del usuario que crea el registro
        $data['created_by'] = auth()->id();

        $organic = Organic::create($data);

        // Crear movimiento automático en bodega de clasificación
        WarehouseClassification::create([
            'date' => $data['date'],
            'type' => $data['type'],
            'movement_type' => 'entry', // Entrada automática
            'weight' => $data['weight'],
            'notes' => 'Entrada automática desde registro de residuos orgánicos',
            'processed_by' => $data['received_by'],
            'img' => $data['img'] // Misma imagen si existe
        ]);

        return redirect()->route('admin.organic.index')->with('success', 'Residuo orgánico registrado y clasificado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Organic $organic)
    {
        // Si es una petición AJAX, devolver JSON
        if (request()->ajax()) {
            // Cargar la relación del creador
            $organic->load('creator');
            
            return response()->json([
                'id' => $organic->id,
                'date' => $organic->date->format('Y-m-d'),
                'date_formatted' => $organic->formatted_date,
                'type' => $organic->type,
                'type_in_spanish' => $organic->type_in_spanish,
                'weight' => $organic->weight,
                'formatted_weight' => $organic->formatted_weight,
                'delivered_by' => $organic->delivered_by,
                'received_by' => $organic->received_by,
                'notes' => $organic->notes,
                'img' => $organic->img,
                'img_url' => $organic->img ? asset($organic->img) : null,
                'created_at' => $organic->created_at->format('Y-m-d H:i:s'),
                'created_at_formatted' => $organic->created_at->format('d/m/Y H:i:s'),
                'created_by_info' => $organic->created_by_info,
                'updated_at' => $organic->updated_at->format('Y-m-d H:i:s'),
            ]);
        }
        
        return view('admin.organic.show', compact('organic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organic $organic)
    {
        return view('admin.organic.edit', compact('organic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organic $organic)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:Kitchen,Beds,Leaves,CowDung,ChickenManure,PigManure,Other',
            'weight' => 'required|numeric|min:0.01',
            'delivered_by' => 'required|string|max:100',
            'received_by' => 'required|string|max:100',
            'notes' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('img')) {
            // Delete old image
            if ($organic->img && file_exists(public_path($organic->img))) {
                unlink(public_path($organic->img));
            }
            $image = $request->file('img');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('organics'), $imageName);
            $data['img'] = 'organics/' . $imageName;
        }

        $organic->update($data);

        return redirect()->route('admin.organic.index')->with('success', '¡Registro de residuo orgánico actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organic $organic)
    {
        // Restar del inventario de bodega antes de eliminar
        \App\Models\WarehouseClassification::create([
            'date' => now()->toDateString(),
            'type' => $organic->type,
            'movement_type' => 'exit',
            'weight' => $organic->weight,
            'notes' => "Eliminación de residuo orgánico #" . str_pad($organic->id, 3, '0', STR_PAD_LEFT),
            'processed_by' => auth()->user()->name
        ]);

        // Delete image if exists
        if ($organic->img && file_exists(public_path($organic->img))) {
            unlink(public_path($organic->img));
        }
        
        $organic->delete();

        return redirect()->route('admin.organic.index')->with('success', '¡Registro de residuo orgánico eliminado exitosamente! El inventario de bodega ha sido actualizado.');
    }

    /**
     * Generate PDF for all organics
     */
    public function downloadAllOrganicsPDF()
    {
        $organics = Organic::with('creator')->orderBy('date', 'desc')->get();
        
        $pdf = PDF::loadView('admin.organic.pdf.all-organics', compact('organics'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('todos_los_residuos_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for individual organic
     */
    public function downloadOrganicPDF(Organic $organic)
    {
        $organic->load('creator');
        
        // Convertir imagen a base64 si existe
        $imageBase64 = null;
        if ($organic->img && file_exists(public_path($organic->img))) {
            $imagePath = public_path($organic->img);
            $imageData = file_get_contents($imagePath);
            $imageInfo = getimagesize($imagePath);
            $mimeType = $imageInfo['mime'];
            $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        
        $pdf = PDF::loadView('admin.organic.pdf.organic-details', compact('organic', 'imageBase64'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('residuo_' . str_pad($organic->id, 3, '0', STR_PAD_LEFT) . '_' . date('Y-m-d') . '.pdf');
    }
}

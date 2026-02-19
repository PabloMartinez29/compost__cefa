<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    /** Nombre del archivo del manual en public/docs */
    public const MANUAL_FILENAME = 'manual-usuario.pdf';

    /**
     * Mostrar la página de soporte (manual de usuario).
     */
    public function index()
    {
        $manualPath = 'docs/' . self::MANUAL_FILENAME;
        $hasManual = file_exists(public_path($manualPath));

        return view('soporte', [
            'hasManual' => $hasManual,
            'manualUrl' => $manualPath,
        ]);
    }

    /**
     * Subir o reemplazar el manual de usuario (solo admin).
     */
    public function upload(Request $request)
    {
        $request->validate([
            'manual' => 'required|file|mimes:pdf|max:20480', // 20 MB
        ], [
            'manual.required' => 'Debes seleccionar un archivo PDF.',
            'manual.mimes' => 'El manual debe ser un archivo PDF.',
            'manual.max' => 'El archivo no debe superar 20 MB.',
        ]);

        $dir = public_path('docs');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/' . self::MANUAL_FILENAME;
        $request->file('manual')->move($dir, self::MANUAL_FILENAME);

        return redirect()->route('soporte')
            ->with('success', 'Manual de usuario actualizado correctamente.');
    }
}

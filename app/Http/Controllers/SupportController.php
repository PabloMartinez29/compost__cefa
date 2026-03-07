<?php

// Controlador SupportController — Manuales y página de ayuda
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    // Nombres de los archivos de manuales en
    public const MANUAL_APRENDIZ = 'manual-aprendiz.pdf';
    public const MANUAL_ADMIN = 'manual-administrador.pdf';
    public const MANUAL_TECNICO = 'manual-tecnico.pdf';

    // Mostrar la página de ayuda (manuales)
    public function index()
    {
        $aprendizPath = 'docs/' . self::MANUAL_APRENDIZ;
        $adminPath = 'docs/' . self::MANUAL_ADMIN;
        $tecnicoPath = 'docs/' . self::MANUAL_TECNICO;

        // Mostrar vista
        return view('soporte', [
            'hasManualAprendiz' => file_exists(public_path($aprendizPath)),
            'manualAprendizUrl' => $aprendizPath,
            'hasManualAdmin' => file_exists(public_path($adminPath)),
            'manualAdminUrl' => $adminPath,
            'hasManualTecnico' => file_exists(public_path($tecnicoPath)),
            'manualTecnicoUrl' => $tecnicoPath,
        ]);
    }

    // Mostrar un manual en visor con favicon
    public function viewManual(string $type)
    {
        $manuals = [
            'aprendiz'      => ['file' => self::MANUAL_APRENDIZ, 'title' => 'Manual de Aprendiz'],
            'administrador' => ['file' => self::MANUAL_ADMIN, 'title' => 'Manual de Administrador'],
            'tecnico'       => ['file' => self::MANUAL_TECNICO, 'title' => 'Manual Técnico'],
        ];

        if (!isset($manuals[$type])) {
            abort(404);
        }

        $manual = $manuals[$type];
        $path = 'docs/' . $manual['file'];

        if (!file_exists(public_path($path))) {
            abort(404, 'El manual no está disponible.');
        }

        // Mostrar vista
        return view('pdf-viewer', [
            'pdfUrl' => asset($path),
            'title'  => $manual['title'],
        ]);
    }

    // Subir o reemplazar un manual (solo admin)
    public function upload(Request $request)
    {
        // Validar datos recibidos
        $request->validate([
            'manual' => 'required|file|mimes:pdf|max:20480', // 20 MB
            'type'   => 'required|in:aprendiz,admin,tecnico',
        ], [
            'manual.required' => 'Debes seleccionar un archivo PDF.',
            'manual.mimes' => 'El manual debe ser un archivo PDF.',
            'manual.max' => 'El archivo no debe superar 20 MB.',
            'type.required' => 'Debes indicar el tipo de manual.',
            'type.in' => 'Tipo de manual no válido.',
        ]);

        $dir = public_path('docs');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = match ($request->type) {
            'aprendiz' => self::MANUAL_APRENDIZ,
            'tecnico'  => self::MANUAL_TECNICO,
            default    => self::MANUAL_ADMIN,
        };
        $label = match ($request->type) {
            'aprendiz' => 'Aprendiz',
            'tecnico'  => 'Técnico',
            default    => 'Administrador',
        };

        $request->file('manual')->move($dir, $filename);

        // Redirigir con mensaje
            return redirect()->route('soporte')
            ->with('success', "Manual de $label actualizado correctamente.");
    }
}

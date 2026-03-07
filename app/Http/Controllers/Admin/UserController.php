<?php

// Controlador Admin UserController — Gestión de usuarios del sistema
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class UserController extends Controller
{
    // Listar todos los registros
    public function index(Request $request)
    {
        // Obtener todos los usuarios sin paginación (DataTables manejará la paginación del lado del cliente)
        $users = User::orderBy('created_at', 'desc')->get();
        
        // Mostrar vista
        return view('admin.users.index', compact('users'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        // Mostrar vista
        return view('admin.users.create');
    }

    // Guardar nuevo registro
    public function store(Request $request)
    {
        // Validar datos recibidos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'identification' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,aprendiz',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'identification' => $request->identification,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Redirigir con mensaje
            return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    // Mostrar detalle del registro
    public function show(User $user)
    {
        // Si es una petición AJAX o se solicita JSON, devolver JSON
        if (request()->ajax() || request()->wantsJson() || request()->header('Accept') === 'application/json') {
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'identification' => $user->identification,
                'document_type' => $user->document_type,
                'role' => $user->role,
                'email_verified_at' => $user->email_verified_at,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }
        
        // Mostrar vista
        return view('admin.users.show', compact('user'));
    }

    // Get user data in JSON format
    public function getUserData(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'identification' => $user->identification,
            'document_type' => $user->document_type,
            'role' => $user->role,
            'email_verified_at' => $user->email_verified_at,
            'is_active' => $user->is_active,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }

    // Mostrar formulario de edición
    public function edit(User $user)
    {
        // Mostrar vista
        return view('admin.users.edit', compact('user'));
    }

    // Actualizar registro existente
    public function update(Request $request, User $user)
    {
        // Validar datos recibidos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'identification' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'document_type' => 'required|string|in:CC,TI,CE,PEP,PASAPORTE',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,aprendiz',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'identification' => $request->identification,
            'document_type' => $request->document_type,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Redirigir con mensaje
            return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    // Eliminar registro del sistema
    public function destroy(User $user)
    {
        // No permitir desactivar al usuario actual
        if ($user->id === Auth::id()) {
            // Redirigir con mensaje
            return redirect()->route('admin.users.index')
                ->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        // Desactivar usuario en lugar de eliminarlo
        $user->update([
            'is_active' => false,
        ]);

        // Redirigir con mensaje
            return redirect()->route('admin.users.index')
            ->with('success', 'Usuario desactivado exitosamente.');
    }

    // Reactivate a previously deactivated user
    public function activate(User $user)
    {
        $user->update([
            'is_active' => true,
        ]);

        // Redirigir con mensaje
            return redirect()->route('admin.users.index')
            ->with('success', 'Usuario activado exitosamente.');
    }

    // Generate PDF for all users (o solo
    public function downloadAllUsersPDF(Request $request)
    {
        $query = User::orderBy('created_at', 'desc');

        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->ids)));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        $users = $query->get();

        $pdf = PDF::loadView('admin.users.pdf.all-users', compact('users'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('todos_los_usuarios_' . date('Y-m-d') . '.pdf');
    }

    // Generate PDF for individual user
    public function downloadUserPDF(User $user)
    {
        $pdf = PDF::loadView('admin.users.pdf.user-details', compact('user'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('usuario_' . $user->identification . '_' . date('Y-m-d') . '.pdf');
    }
}

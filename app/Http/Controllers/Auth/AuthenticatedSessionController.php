<?php

// Controlador Auth — Inicio y cierre de sesión
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    // Display the login view
    public function create(): View
    {
        // Mostrar vista
        return view('auth.login');
    }

    // Handle an incoming authentication request
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            // Obtener el usuario autenticado
            $user = Auth::user();
            
            if (!$user) {
                // Si no hay usuario autenticado, redirigir al login
                // Redirigir con mensaje
            return redirect()->route('login')->with('error', 'Error de autenticación.');
            }

            // Si la cuenta está desactivada, cerrar sesión inmediatamente y mostrar mensaje
            if (isset($user->is_active) && $user->is_active === false) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirigir con mensaje
            return redirect()->route('login')
                    ->with('account_deactivated_login', true);
            }

            // Verificar y asignar rol por defecto si es necesario
            if (is_null($user->role)) {
                \App\Models\User::where('id', $user->id)->update(['role' => 'aprendiz']);
                // Obtener el usuario actualizado
                $user = Auth::user();
            }

            if ($user->role === 'admin') {
                // Redirigir con mensaje
            return redirect()->route('dashboard.admin');
            } else {
                // Redirigir con mensaje
            return redirect()->route('aprendiz.dashboard');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Redirigir con mensaje
            return redirect()->route('login')->with('error', 'Estas credenciales no coinciden con nuestros registros.');
        }
    }

    // Destroy an authenticated session
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Redirigir con mensaje
            return redirect('/');
    }
}

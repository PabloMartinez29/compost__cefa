<?php

// Middleware CheckRole — Verifica el rol del usuario (admin/aprendiz)
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // Handle an incoming request
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!auth()->check()) { 
            return redirect('/login'); 
        } 

        // Si el usuario está autenticado pero su cuenta fue desactivada,
        // cerrar sesión y redirigir al inicio de sesión con mensaje claro.
        if (auth()->user() && isset(auth()->user()->is_active) && auth()->user()->is_active === false) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('account_deactivated_session', true);
        }
 
        $userRole = auth()->user()->role;
        
        // Si el usuario no tiene rol, asignar por defecto 'aprendiz'
        if (is_null($userRole)) {
            auth()->user()->update(['role' => 'aprendiz']);
            $userRole = 'aprendiz';
        }
        
        if ($userRole !== $role) { 
            // Redirigir al dashboard correcto del usuario con SweetAlert
            $redirectRoute = $userRole === 'admin' ? 'dashboard.admin' : 'aprendiz.dashboard';
            return redirect()->route($redirectRoute)
                ->with('unauthorized_access', true);
        } 

        return $next($request);
        
    }
}

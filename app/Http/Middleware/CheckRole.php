<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
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
            abort(403, 'Acceso no autorizado.'); 
        } 


        return $next($request);
        
    }
}

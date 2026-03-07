<?php

// Controlador Auth — Restablecer contraseña
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    // Display the password reset view
    public function create(Request $request): View
    {
        // Mostrar vista
        return view('auth.reset-password', ['request' => $request]);
    }

    // Handle an incoming new password request
    public function store(Request $request): RedirectResponse
    {
        // Validar datos recibidos
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status == Password::PASSWORD_RESET) {
            // Redirigir con mensaje
            return redirect()->route('login')->with('status', __($status));
        }

        // Mensaje claro cuando el enlace ya fue usado, expiró o no es válido
        $message = 'El enlace de recuperación no es válido o ha expirado. Si ya lo utilizaste, solicita un nuevo enlace.';
        return back()->withInput($request->only('email'))
            ->withErrors(['email' => $message]);
    }
}

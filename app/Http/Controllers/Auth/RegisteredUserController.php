<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class, function ($attribute, $value, $fail) {
                if (!str_contains($value, '@')) {
                    $fail('El campo email debe contener el símbolo @.');
                }
            }],
            'document_type' => ['required', 'string', 'in:CC,TI,CE,PEP,PASAPORTE'],
            'identification' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'document_type' => $request->document_type,
            'identification' => $request->identification,
            'password' => Hash::make($request->password),
            'role' => 'aprendiz', // Por defecto los usuarios registrados son aprendices
        ]);

        event(new Registered($user));

        // No auto-login, redirect to login with success message
        return redirect()->route('login')->with('success', 'Registro exitoso. Ya puedes iniciar sesión.');
    }
}

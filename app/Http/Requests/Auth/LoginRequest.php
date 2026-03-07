<?php

// Request LoginRequest — Validación del formulario de login
namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    // Determine if the user is authorized to
    public function authorize(): bool
    {
        return true;
    }

    // Get the validation rules that apply to
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!str_contains($value, '@')) {
                    $fail('El campo email debe contener el símbolo @.');
                }
            }],
            'password' => ['required', 'string'],
        ];
    }

    // Attempt to authenticate the request's credentials
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Convertir email a minúsculas para la autenticación pero mantener el original para validación
        $credentials = $this->only('email', 'password');
        $credentials['email'] = strtolower($credentials['email']);

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    // Ensure the login request is not rate
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    // Get the rate limiting throttle key for
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}

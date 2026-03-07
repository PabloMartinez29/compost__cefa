<?php

// Modelo User — Usuarios del sistema (admin/aprendiz, autenticación, roles)

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Campos editables
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'identification',
        'document_type',
        'is_active',
    ];

    // Campos ocultos en JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts de atributos
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isRole($role) 
    { 
        return $this->role === $role; 
    }

    // Enviar notificación de restablecer contraseña
    public function sendPasswordResetNotification($token): void
    {
        $url = url(route('password.reset', ['token' => $token, 'email' => $this->email], false));
        $this->notify(new \App\Notifications\CustomResetPassword($url));
    }
}

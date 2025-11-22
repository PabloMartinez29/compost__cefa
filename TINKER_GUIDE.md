## Sistema de Compostaje CEFA




### 1. Abrir Tinker


php artisan tinker


### 2. Crear Usuario Administrador

Una vez dentro de Tinker, copia y pega este código:

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Administrador',
    'email' => 'admin@cefa.com',
    'password' => Hash::make('password123'),
    'role' => 'admin'
]);
```

### 3. Crear Usuario Aprendiz

```php
User::create([
    'name' => 'Aprendiz',
    'email' => 'aprendiz@cefa.com',
    'password' => Hash::make('password123'),
    'role' => 'aprendiz'
]);
```

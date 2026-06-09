<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Persona;
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
     * Muestra el formulario de registro
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Procesa el registro creando Persona + Usuario
     */
    public function store(Request $request): RedirectResponse
    {
        // Validación — email se verifica en tabla persona, no en usuario
        $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:150',
                           'unique:persona,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Crear el registro base en persona
        $persona = Persona::create([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'email'    => $request->email,
        ]);

        // 2. Crear el usuario vinculado a esa persona
        $usuario = User::withoutGlobalScopes()->create([
            'id_persona'    => $persona->id_persona,
            'password_hash' => Hash::make($request->password),
            'rol'           => 'empleado',
            'activo'        => true,
        ]);

        // 3. Recargar con el Global Scope para que Auth lo reconozca
        $usuarioConPersona = User::find($usuario->id_usuario);

        event(new Registered($usuarioConPersona));

        Auth::login($usuarioConPersona);

        return redirect(route('dashboard', absolute: false));
    }
}

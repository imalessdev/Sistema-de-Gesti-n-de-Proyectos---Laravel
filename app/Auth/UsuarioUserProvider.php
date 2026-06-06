<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class UsuarioUserProvider extends EloquentUserProvider
{
    /*
    |-----------------------------------------------------------
    | Sobrescribimos retrieveByCredentials para que busque
    | el email en persona.email en lugar de usuario.email
    |-----------------------------------------------------------
    */
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        $email = $credentials['email'] ?? null;
        if (!$email) return null;

        return $this->createModel()
                    ->newQuery()
                    ->where('persona.email', $email)
                    ->first();
    }
}

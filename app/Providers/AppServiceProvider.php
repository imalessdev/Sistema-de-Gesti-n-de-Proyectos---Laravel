<?php

namespace App\Providers;

use App\Auth\UsuarioUserProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
        |-----------------------------------------------------------
        | Registramos nuestro provider personalizado para que
        | Breeze busque usuarios por persona.email en vez de
        | usuario.email
        |-----------------------------------------------------------
        */
        Auth::provider('usuario_provider', function ($app, array $config) {
            return new UsuarioUserProvider($app['hash'], $config['model']);
        });
    }
}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'TecnoSoluciones') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            html, body { height: 100%; margin: 0; }

            .guest-wrapper {
                display: flex;
                height: 100vh;
                overflow: hidden;
                background-color: #f3f4f6;
            }

            .guest-left {
                display: none;
                position: relative;
                overflow: hidden;
            }
            @media (min-width: 1024px) {
                .guest-left  { display: flex; width: 50%; height: 100%; }
                .guest-right { width: 50%; }
            }

            .guest-left img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .guest-left-overlay {
                position: absolute;
                inset: 0;
                background: rgba(55, 48, 163, 0.45);
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                padding: 3rem;
            }
            .guest-left-overlay h2 {
                color: #fff;
                font-size: 1.75rem;
                font-weight: 700;
                margin: 0 0 0.5rem;
                line-height: 1.3;
            }
            .guest-left-overlay p {
                color: #c7d2fe;
                font-size: 0.875rem;
                margin: 0;
            }

            /* Panel derecho */
            .guest-right {
                display: flex;
                width: 100%;
                height: 100%;
                align-items: center;
                justify-content: center;
                overflow-y: auto;
                padding: 2rem;
            }

            .guest-card-wrapper {
                width: 100%;
                max-width: 26rem;
            }

            .guest-header {
                text-align: center;
                margin-bottom: 2rem;
            }
            .guest-header h1 {
                font-size: 1.375rem;
                font-weight: 600;
                color: #1f2937;
                margin: 0.75rem 0 0.25rem;
            }
            .guest-header p {
                font-size: 0.875rem;
                color: #6b7280;
                margin: 0;
            }

            .guest-card {
                background: #fff;
                border-radius: 0.75rem;
                box-shadow: 0 4px 16px rgba(0,0,0,0.08);
                padding: 2rem;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <div class="guest-wrapper">

            <!-- IZQUIERDA: imagen decorativa -->
            <div class="guest-left">
                <img
                   src="{{ asset('img/login-bg.webp') }}"
                   alt="TecnoSoluciones"
                >
                <div class="guest-left-overlay">
                    <h2>Sistema de Gestión<br>de Proyectos</h2>
                    <p>TecnoSoluciones S.A. — Plataforma interna</p>
                </div>
            </div>

            <!-- DERECHA: formulario -->
            <div class="guest-right">
                <div class="guest-card-wrapper">

                    <div class="guest-header">
                        <a href="/">
                            <x-application-logo class="w-14 h-14 fill-current text-indigo-600 mx-auto" />
                        </a>
                        <h1>{{ config('app.name', 'TecnoSoluciones') }}</h1>
                        <p>Bienvenido de vuelta</p>
                    </div>

                    <div class="guest-card">
                        {{ $slot }}
                    </div>

                </div>
            </div>

        </div>

    </body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — @yield('titulo', 'Panel')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar { width: 240px; min-height: 100vh; background: #1e1b4b; flex-shrink: 0; }
        .sidebar a { display:flex; align-items:center; gap:10px; padding:10px 20px;
                     color:#c7d2fe; font-size:14px; text-decoration:none; transition:background .15s; }
        .sidebar a:hover, .sidebar a.active { background:#312e81; color:#fff; }
        .sidebar .nav-title { font-size:11px; color:#6366f1; font-weight:600;
                              letter-spacing:.08em; padding:20px 20px 6px; text-transform:uppercase; }
        .main-content { flex: 1; background: #f3f4f6; min-height: 100vh; }
        .topbar { background:#fff; border-bottom:1px solid #e5e7eb;
                  padding:0 24px; height:56px; display:flex; align-items:center;
                  justify-content:space-between; }
        .page-content { padding: 24px; }
        .alert-success { background:#d1fae5; border:1px solid #6ee7b7; color:#065f46;
                         padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:14px; }
        .alert-error   { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b;
                         padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:14px; }
    </style>
</head>
<body style="display:flex;margin:0;font-family:'Figtree',sans-serif">

    <!-- Sidebar -->
    <div class="sidebar">
        <div style="padding:20px;border-bottom:1px solid #312e81">
            <a href="{{ route('dashboard') }}" style="color:#fff;font-weight:600;font-size:16px;text-decoration:none">
                {{ config('app.name') }}
            </a>
        </div>

        <p class="nav-title">Principal</p>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <p class="nav-title">Gestión</p>
        <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}">
            Clientes
        </a>
        <a href="{{ route('proyectos.index') }}" class="{{ request()->routeIs('proyectos.*') ? 'active' : '' }}">
            Proyectos
        </a>
        <a href="{{ route('tareas.index') }}" class="{{ request()->routeIs('tareas.*') ? 'active' : '' }}">
            Tareas
        </a>

        <p class="nav-title">Reportes</p>
        <a href="{{ route('reportes.index') }}" class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}">Reportes PDF</a>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">

        <!-- Topbar -->
        <div class="topbar">
            <span style="font-weight:500;color:#374151">@yield('titulo', 'Panel')</span>
            <div style="display:flex;align-items:center;gap:16px">
                <span style="font-size:13px;color:#6b7280">
                    {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        style="font-size:13px;color:#6b7280;background:none;border:none;cursor:pointer;padding:0">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>

        <!-- Alertas -->
        <div class="page-content">
            @if(session('success'))
                <div class="alert-success">✔ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-error">✖ {{ session('error') }}</div>
            @endif

            @yield('contenido')
        </div>

    </div>

</body>
</html>

<<<<<<< HEAD
@extends('layouts.app')
@section('titulo', 'Dashboard')

@section('contenido')

{{-- ── Métricas principales ────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:24px">

    <div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <p style="font-size:12px;color:#9ca3af;margin:0 0 6px">Clientes</p>
        <p style="font-size:28px;font-weight:600;color:#111827;margin:0">{{ $totalClientes }}</p>
        <a href="{{ route('clientes.index') }}" style="font-size:12px;color:#4f46e5;text-decoration:none">Ver todos →</a>
    </div>

    <div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <p style="font-size:12px;color:#9ca3af;margin:0 0 6px">Proyectos</p>
        <p style="font-size:28px;font-weight:600;color:#111827;margin:0">{{ $totalProyectos }}</p>
        <p style="font-size:12px;color:#9ca3af;margin:4px 0 0">{{ $proyActivos }} activos · {{ $proyPendientes }} pendientes</p>
    </div>

    <div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <p style="font-size:12px;color:#9ca3af;margin:0 0 6px">Tareas</p>
        <p style="font-size:28px;font-weight:600;color:#111827;margin:0">{{ $totalTareas }}</p>
        <p style="font-size:12px;color:#9ca3af;margin:4px 0 0">Total registradas</p>
    </div>

    <div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 4px rgba(0,0,0,.07);border-left:3px solid {{ $tareasVencidas > 0 ? '#dc2626' : '#d1d5db' }}">
        <p style="font-size:12px;color:#9ca3af;margin:0 0 6px">Tareas vencidas</p>
        <p style="font-size:28px;font-weight:600;color:{{ $tareasVencidas > 0 ? '#dc2626' : '#111827' }};margin:0">
            {{ $tareasVencidas }}
        </p>
        <p style="font-size:12px;color:{{ $tareasVencidas > 0 ? '#dc2626' : '#9ca3af' }};margin:4px 0 0">
            {{ $tareasVencidas > 0 ? 'Requieren atención' : 'Todo al día ✓' }}
        </p>
    </div>

    <div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <p style="font-size:12px;color:#9ca3af;margin:0 0 6px">Usuarios activos</p>
        <p style="font-size:28px;font-weight:600;color:#111827;margin:0">{{ $totalUsuarios }}</p>
        <p style="font-size:12px;color:#9ca3af;margin:4px 0 0">En el sistema</p>
    </div>

</div>

{{-- ── Estados de proyectos (barras) ───────────────────── --}}
<div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 4px rgba(0,0,0,.07);margin-bottom:20px">
    <p style="font-size:14px;font-weight:500;color:#111827;margin:0 0 16px">Proyectos por estado</p>
    @php
        $estados = [
            ['label'=>'Activos',     'count'=>$proyActivos,     'color'=>'#4f46e5'],
            ['label'=>'Pendientes',  'count'=>$proyPendientes,  'color'=>'#f59e0b'],
            ['label'=>'Completados', 'count'=>$proyCompletados, 'color'=>'#10b981'],
            ['label'=>'Suspendidos', 'count'=>$proySuspendidos, 'color'=>'#ef4444'],
        ];
    @endphp
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:12px">
        @foreach($estados as $e)
        <div>
            <div style="display:flex;justify-content:space-between;font-size:12px;color:#6b7280;margin-bottom:4px">
                <span>{{ $e['label'] }}</span>
                <span style="font-weight:500;color:#111827">{{ $e['count'] }}</span>
            </div>
            <div style="background:#f3f4f6;border-radius:4px;height:8px">
                @if($totalProyectos > 0)
                <div style="width:{{ ($e['count'] / $totalProyectos) * 100 }}%;background:{{ $e['color'] }};height:8px;border-radius:4px;transition:width .3s"></div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ── Dos columnas ─────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">

    {{-- Proyectos activos --}}
    <div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <p style="font-size:14px;font-weight:500;color:#111827;margin:0">Proyectos activos</p>
            <a href="{{ route('proyectos.index') }}" style="font-size:12px;color:#4f46e5;text-decoration:none">Ver todos →</a>
        </div>

        @forelse($proyectosActivos as $proyecto)
        <div style="padding:10px 0;border-top:1px solid #f3f4f6">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px">
                <div>
                    <a href="{{ route('proyectos.show', $proyecto) }}"
                       style="font-size:13px;font-weight:500;color:#111827;text-decoration:none">
                        {{ $proyecto->nombre }}
                    </a>
                    <p style="font-size:11px;color:#9ca3af;margin:2px 0 0">
                        {{ $proyecto->cliente->persona->nombre ?? '' }}
                        {{ $proyecto->cliente->empresa ? '· ' . $proyecto->cliente->empresa : '' }}
                    </p>
                </div>
                <span style="font-size:12px;font-weight:500;color:#4f46e5;white-space:nowrap;margin-left:8px">
                    {{ $proyecto->avance }}%
                </span>
            </div>
            <div style="background:#f3f4f6;border-radius:4px;height:5px">
                <div style="width:{{ $proyecto->avance }}%;background:#4f46e5;height:5px;border-radius:4px"></div>
            </div>
            <p style="font-size:11px;color:#9ca3af;margin:4px 0 0">
                Vence: {{ \Carbon\Carbon::parse($proyecto->fecha_fin_estimada)->format('d/m/Y') }}
                @if($proyecto->estaVencido()) <span style="color:#dc2626">⚠ Vencido</span> @endif
            </p>
        </div>
        @empty
        <p style="font-size:13px;color:#9ca3af;text-align:center;padding:20px 0">
            No hay proyectos activos.
        </p>
        @endforelse
    </div>

    {{-- Tareas por vencer --}}
    <div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <p style="font-size:14px;font-weight:500;color:#111827;margin:0">Próximas a vencer</p>
            <a href="{{ route('tareas.index') }}" style="font-size:12px;color:#4f46e5;text-decoration:none">Ver todas →</a>
        </div>

        @forelse($tareasPorVencer as $tarea)
        @php
            $dias = \Carbon\Carbon::today()->diffInDays($tarea->fecha_limite, false);
            $pc = ['urgente'=>['bg'=>'#fee2e2','color'=>'#991b1b'],'alta'=>['bg'=>'#fef3c7','color'=>'#92400e'],'media'=>['bg'=>'#ede9fe','color'=>'#5b21b6'],'baja'=>['bg'=>'#d1fae5','color'=>'#065f46']];
            $p = $pc[$tarea->prioridad] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
        @endphp
        <div style="display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-top:1px solid #f3f4f6">
            <div style="flex:1">
                <p style="font-size:13px;font-weight:500;color:#111827;margin:0">{{ $tarea->titulo }}</p>
                <p style="font-size:11px;color:#9ca3af;margin:2px 0 0">{{ $tarea->proyecto->nombre ?? '—' }}</p>
            </div>
            <div style="text-align:right;white-space:nowrap">
                <span style="background:{{ $p['bg'] }};color:{{ $p['color'] }};font-size:10px;font-weight:500;padding:2px 7px;border-radius:20px;display:block;margin-bottom:3px">
                    {{ $tarea->prioridad }}
                </span>
                <span style="font-size:11px;color:{{ $dias <= 2 ? '#dc2626' : '#9ca3af' }}">
                    {{ $dias == 0 ? 'Hoy' : ($dias == 1 ? 'Mañana' : 'En ' . $dias . ' días') }}
                </span>
            </div>
        </div>
        @empty
        <p style="font-size:13px;color:#9ca3af;text-align:center;padding:20px 0">
            No hay tareas próximas a vencer.
        </p>
        @endforelse
    </div>

</div>

{{-- ── Tareas vencidas ──────────────────────────────────── --}}
@if($tareasVencidasLista->count() > 0)
<div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 4px rgba(0,0,0,.07);border-left:3px solid #dc2626">
    <p style="font-size:14px;font-weight:500;color:#dc2626;margin:0 0 14px">
        ⚠ Tareas vencidas sin resolver ({{ $tareasVencidasLista->count() }})
    </p>
    <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <thead>
                <tr style="border-bottom:1px solid #fee2e2">
                    <th style="padding:6px 12px;text-align:left;font-weight:500;color:#9ca3af;font-size:12px">Tarea</th>
                    <th style="padding:6px 12px;text-align:left;font-weight:500;color:#9ca3af;font-size:12px">Proyecto</th>
                    <th style="padding:6px 12px;text-align:left;font-weight:500;color:#9ca3af;font-size:12px">Venció</th>
                    <th style="padding:6px 12px;text-align:left;font-weight:500;color:#9ca3af;font-size:12px">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tareasVencidasLista as $tarea)
                <tr style="border-top:1px solid #f3f4f6">
                    <td style="padding:8px 12px;font-weight:500;color:#111827">{{ $tarea->titulo }}</td>
                    <td style="padding:8px 12px;color:#6b7280">{{ $tarea->proyecto->nombre ?? '—' }}</td>
                    <td style="padding:8px 12px;color:#dc2626;font-weight:500">
                        {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}
                        <span style="font-size:11px;font-weight:400;color:#9ca3af">
                            (hace {{ \Carbon\Carbon::parse($tarea->fecha_limite)->diffInDays(\Carbon\Carbon::today()) }} días)
                        </span>
                    </td>
                    <td style="padding:8px 12px">
                        <a href="{{ route('tareas.edit', $tarea) }}"
                           style="font-size:12px;color:#4f46e5;text-decoration:none">
                            Resolver →
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
=======
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
>>>>>>> 88d4633176a6c74f1edbc51bdeb0715a1cfedb93

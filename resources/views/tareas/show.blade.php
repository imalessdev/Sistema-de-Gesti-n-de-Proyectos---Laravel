@extends('layouts.app')
@section('titulo', 'Detalle de Tarea')

@section('contenido')
<div style="max-width:640px">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
        <div style="display:flex;align-items:center;gap:12px">
            <a href="{{ route('tareas.index') }}" style="color:#6b7280;text-decoration:none;font-size:14px">← Volver</a>
            <h2 style="font-size:20px;font-weight:600;color:#111827;margin:0">{{ $tarea->titulo }}</h2>
        </div>
        <a href="{{ route('tareas.edit', $tarea) }}"
           style="padding:8px 16px;background:#4f46e5;color:#fff;border-radius:8px;font-size:14px;font-weight:500;text-decoration:none">
            Editar
        </a>
    </div>

    <div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:24px">
        @php
            $pc = ['urgente'=>['bg'=>'#fee2e2','color'=>'#991b1b'],'alta'=>['bg'=>'#fef3c7','color'=>'#92400e'],'media'=>['bg'=>'#ede9fe','color'=>'#5b21b6'],'baja'=>['bg'=>'#d1fae5','color'=>'#065f46']];
            $ec = ['pendiente'=>['bg'=>'#f3f4f6','color'=>'#374151'],'en_progreso'=>['bg'=>'#dbeafe','color'=>'#1e40af'],'completada'=>['bg'=>'#d1fae5','color'=>'#065f46'],'cancelada'=>['bg'=>'#fee2e2','color'=>'#991b1b']];
            $p = $pc[$tarea->prioridad] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
            $e = $ec[$tarea->estado]    ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
        @endphp

        <div style="display:flex;gap:8px;margin-bottom:20px">
            <span style="background:{{ $p['bg'] }};color:{{ $p['color'] }};font-size:12px;font-weight:500;padding:3px 10px;border-radius:20px">
                {{ $tarea->prioridad }}
            </span>
            <span style="background:{{ $e['bg'] }};color:{{ $e['color'] }};font-size:12px;font-weight:500;padding:3px 10px;border-radius:20px">
                {{ str_replace('_',' ',$tarea->estado) }}
            </span>
            @if($tarea->estaVencida())
            <span style="background:#fee2e2;color:#991b1b;font-size:12px;font-weight:500;padding:3px 10px;border-radius:20px">
                ⚠️ Vencida
            </span>
            @endif
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:14px">
            <div>
                <p style="color:#9ca3af;margin:0 0 2px;font-size:12px">Proyecto</p>
                <p style="color:#111827;margin:0;font-weight:500">{{ $tarea->proyecto->nombre ?? '—' }}</p>
            </div>
            <div>
                <p style="color:#9ca3af;margin:0 0 2px;font-size:12px">Cliente</p>
                <p style="color:#111827;margin:0">{{ $tarea->proyecto->cliente->persona->nombre ?? '—' }}</p>
            </div>
            <div>
                <p style="color:#9ca3af;margin:0 0 2px;font-size:12px">Fecha inicio</p>
                <p style="color:#111827;margin:0">{{ $tarea->fecha_inicio ? \Carbon\Carbon::parse($tarea->fecha_inicio)->format('d/m/Y') : '—' }}</p>
            </div>
            <div>
                <p style="color:#9ca3af;margin:0 0 2px;font-size:12px">Fecha límite</p>
                <p style="color:{{ $tarea->estaVencida() ? '#dc2626' : '#111827' }};margin:0;font-weight:{{ $tarea->estaVencida() ? '500' : '400' }}">
                    {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}
                </p>
            </div>
            @if($tarea->fecha_completada)
            <div>
                <p style="color:#9ca3af;margin:0 0 2px;font-size:12px">Fecha completada</p>
                <p style="color:#059669;margin:0">{{ \Carbon\Carbon::parse($tarea->fecha_completada)->format('d/m/Y') }}</p>
            </div>
            @endif
            <div>
                <p style="color:#9ca3af;margin:0 0 2px;font-size:12px">Creada por</p>
                <p style="color:#111827;margin:0">{{ $tarea->creador->nombre ?? '—' }}</p>
            </div>
        </div>

        @if($tarea->descripcion)
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f3f4f6">
            <p style="color:#9ca3af;margin:0 0 4px;font-size:12px">Descripción</p>
            <p style="color:#374151;margin:0;font-size:14px">{{ $tarea->descripcion }}</p>
        </div>
        @endif

        @if($tarea->asignados->count())
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f3f4f6">
            <p style="color:#9ca3af;margin:0 0 8px;font-size:12px">Asignado a</p>
            <div style="display:flex;flex-wrap:wrap;gap:8px">
                @foreach($tarea->asignados as $u)
                <span style="background:#ede9fe;color:#5b21b6;font-size:13px;padding:4px 12px;border-radius:20px">
                    {{ $u->nombre }} {{ $u->apellido }}
                </span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

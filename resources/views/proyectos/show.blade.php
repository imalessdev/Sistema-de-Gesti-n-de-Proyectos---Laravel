@extends('layouts.app')
@section('titulo', 'Detalle de Proyecto')

@section('contenido')
<div style="max-width:800px">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
        <div style="display:flex;align-items:center;gap:12px">
            <a href="{{ route('proyectos.index') }}" style="color:#6b7280;text-decoration:none;font-size:14px">← Volver</a>
            <h2 style="font-size:20px;font-weight:600;color:#111827;margin:0">{{ $proyecto->nombre }}</h2>
        </div>
        <a href="{{ route('proyectos.edit', $proyecto) }}"
           style="padding:8px 16px;background:#4f46e5;color:#fff;border-radius:8px;font-size:14px;font-weight:500;text-decoration:none">
            Editar
        </a>
    </div>

    <!-- Avance -->
    <div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:24px;margin-bottom:16px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
            <span style="font-size:14px;font-weight:500;color:#374151">Avance del proyecto</span>
            <span style="font-size:20px;font-weight:600;color:#4f46e5">{{ $avance }}%</span>
        </div>
        <div style="background:#e5e7eb;border-radius:8px;height:10px;overflow:hidden">
            <div style="width:{{ $avance }}%;background:#4f46e5;height:100%;border-radius:8px;transition:width .3s"></div>
        </div>
    </div>

    <!-- Info general -->
    <div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:24px;margin-bottom:16px">
        <h3 style="font-size:15px;font-weight:600;color:#374151;margin:0 0 16px">Información general</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:14px">
            <div>
                <p style="color:#9ca3af;margin:0 0 2px;font-size:12px">Cliente</p>
                <p style="color:#111827;margin:0;font-weight:500">
                    {{ $proyecto->cliente->persona->nombre }} {{ $proyecto->cliente->persona->apellido }}
                    {{ $proyecto->cliente->empresa ? '— ' . $proyecto->cliente->empresa : '' }}
                </p>
            </div>
            <div>
                <p style="color:#9ca3af;margin:0 0 2px;font-size:12px">Estado</p>
                @php
                    $colores = ['activo'=>['bg'=>'#dbeafe','color'=>'#1e40af'],'pendiente'=>['bg'=>'#fef3c7','color'=>'#92400e'],'completado'=>['bg'=>'#d1fae5','color'=>'#065f46'],'suspendido'=>['bg'=>'#fee2e2','color'=>'#991b1b']];
                    $c = $colores[$proyecto->estado] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
                @endphp
                <span style="background:{{ $c['bg'] }};color:{{ $c['color'] }};font-size:12px;font-weight:500;padding:3px 10px;border-radius:20px">
                    {{ $proyecto->estado }}
                </span>
            </div>
            <div>
                <p style="color:#9ca3af;margin:0 0 2px;font-size:12px">Fecha inicio</p>
                <p style="color:#111827;margin:0">{{ $proyecto->fecha_inicio->format('d/m/Y') }}</p>
            </div>
            <div>
                <p style="color:#9ca3af;margin:0 0 2px;font-size:12px">Fecha fin estimada</p>
                <p style="color:#111827;margin:0">{{ $proyecto->fecha_fin_estimada->format('d/m/Y') }}</p>
            </div>
            <div>
                <p style="color:#9ca3af;margin:0 0 2px;font-size:12px">Presupuesto</p>
                <p style="color:#111827;margin:0">{{ $proyecto->presupuesto ? 'S/ ' . number_format($proyecto->presupuesto, 2) : '—' }}</p>
            </div>
            <div>
                <p style="color:#9ca3af;margin:0 0 2px;font-size:12px">Creado por</p>
                <p style="color:#111827;margin:0">{{ $proyecto->creador->nombre ?? '—' }}</p>
            </div>
        </div>
        @if($proyecto->descripcion)
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f3f4f6">
            <p style="color:#9ca3af;margin:0 0 4px;font-size:12px">Descripción</p>
            <p style="color:#374151;margin:0;font-size:14px">{{ $proyecto->descripcion }}</p>
        </div>
        @endif
    </div>

    <!-- Tareas -->
    <div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:24px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <h3 style="font-size:15px;font-weight:600;color:#374151;margin:0">
                Tareas ({{ $proyecto->tareas->count() }})
            </h3>
            <a href="{{ route('tareas.create') }}?proyecto={{ $proyecto->id_proyecto }}"
               style="font-size:13px;color:#4f46e5;text-decoration:none">+ Nueva tarea</a>
        </div>
        @forelse($proyecto->tareas as $tarea)
        @php
            $pc = ['urgente'=>['bg'=>'#fee2e2','color'=>'#991b1b'],'alta'=>['bg'=>'#fef3c7','color'=>'#92400e'],'media'=>['bg'=>'#ede9fe','color'=>'#5b21b6'],'baja'=>['bg'=>'#d1fae5','color'=>'#065f46']];
            $p = $pc[$tarea->prioridad] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
        @endphp
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-top:1px solid #f3f4f6">
            <div style="flex:1">
                <p style="font-size:14px;font-weight:500;color:#111827;margin:0">{{ $tarea->titulo }}</p>
                <p style="font-size:12px;color:#9ca3af;margin:4px 0 0">
                    Límite: {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}
                    @if($tarea->asignados->count())
                        · Asignado a: {{ $tarea->asignados->map(fn($u) => $u->nombre)->join(', ') }}
                    @endif
                </p>
            </div>
            <span style="background:{{ $p['bg'] }};color:{{ $p['color'] }};font-size:11px;font-weight:500;padding:2px 8px;border-radius:20px;margin-left:12px">
                {{ $tarea->prioridad }}
            </span>
        </div>
        @empty
        <p style="font-size:14px;color:#9ca3af;text-align:center;padding:20px 0">Sin tareas registradas.</p>
        @endforelse
    </div>
</div>
@endsection

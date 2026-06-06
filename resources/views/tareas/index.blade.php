@extends('layouts.app')
@section('titulo', 'Tareas')

@section('contenido')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
    <div>
        <h2 style="font-size:20px;font-weight:600;color:#111827;margin:0">Tareas</h2>
        <p style="font-size:13px;color:#6b7280;margin:4px 0 0">{{ $tareas->total() }} tareas registradas</p>
    </div>
    <a href="{{ route('tareas.create') }}"
       style="background:#4f46e5;color:#fff;padding:8px 16px;border-radius:8px;font-size:14px;font-weight:500;text-decoration:none">
        + Nueva tarea
    </a>
</div>

<div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);overflow:hidden">
    <table style="width:100%;border-collapse:collapse;font-size:14px">
        <thead>
            <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb">
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Título</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Proyecto</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Prioridad</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Estado</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Límite</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Asignado a</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tareas as $tarea)
            @php
                $pc = ['urgente'=>['bg'=>'#fee2e2','color'=>'#991b1b'],'alta'=>['bg'=>'#fef3c7','color'=>'#92400e'],'media'=>['bg'=>'#ede9fe','color'=>'#5b21b6'],'baja'=>['bg'=>'#d1fae5','color'=>'#065f46']];
                $ec = ['pendiente'=>['bg'=>'#f3f4f6','color'=>'#374151'],'en_progreso'=>['bg'=>'#dbeafe','color'=>'#1e40af'],'completada'=>['bg'=>'#d1fae5','color'=>'#065f46'],'cancelada'=>['bg'=>'#fee2e2','color'=>'#991b1b']];
                $p = $pc[$tarea->prioridad] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
                $e = $ec[$tarea->estado]    ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
            @endphp
            <tr style="border-bottom:1px solid #f3f4f6">
                <td style="padding:12px 16px;font-weight:500;color:#111827">{{ $tarea->titulo }}</td>
                <td style="padding:12px 16px;color:#374151">{{ $tarea->proyecto->nombre ?? '—' }}</td>
                <td style="padding:12px 16px">
                    <span style="background:{{ $p['bg'] }};color:{{ $p['color'] }};font-size:12px;font-weight:500;padding:3px 10px;border-radius:20px">
                        {{ $tarea->prioridad }}
                    </span>
                </td>
                <td style="padding:12px 16px">
                    <span style="background:{{ $e['bg'] }};color:{{ $e['color'] }};font-size:12px;font-weight:500;padding:3px 10px;border-radius:20px">
                        {{ str_replace('_', ' ', $tarea->estado) }}
                    </span>
                </td>
                <td style="padding:12px 16px;color:{{ $tarea->estaVencida() ? '#dc2626' : '#374151' }};font-weight:{{ $tarea->estaVencida() ? '500' : '400' }}">
                    {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}
                    @if($tarea->estaVencida()) ⚠️ @endif
                </td>
                <td style="padding:12px 16px;color:#374151;font-size:13px">
                    {{ $tarea->asignados->map(fn($u) => $u->nombre)->join(', ') ?: '—' }}
                </td>
                <td style="padding:12px 16px">
                    <div style="display:flex;gap:8px">
                        <a href="{{ route('tareas.show', $tarea) }}" style="font-size:12px;color:#4f46e5;text-decoration:none">Ver</a>
                        <a href="{{ route('tareas.edit', $tarea) }}" style="font-size:12px;color:#059669;text-decoration:none">Editar</a>
                        <form action="{{ route('tareas.destroy', $tarea) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar esta tarea?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="font-size:12px;color:#dc2626;background:none;border:none;cursor:pointer;padding:0">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:40px;text-align:center;color:#9ca3af">No hay tareas registradas aún.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($tareas->hasPages())
    <div style="padding:16px;border-top:1px solid #e5e7eb">{{ $tareas->links() }}</div>
    @endif
</div>
@endsection

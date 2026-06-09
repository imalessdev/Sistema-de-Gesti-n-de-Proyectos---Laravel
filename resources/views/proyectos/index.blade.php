@extends('layouts.app')
@section('titulo', 'Proyectos')

@section('contenido')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
    <div>
        <h2 style="font-size:20px;font-weight:600;color:#111827;margin:0">Proyectos</h2>
        <p style="font-size:13px;color:#6b7280;margin:4px 0 0">{{ $proyectos->total() }} proyectos registrados</p>
    </div>
    <a href="{{ route('proyectos.create') }}"
       style="background:#4f46e5;color:#fff;padding:8px 16px;border-radius:8px;font-size:14px;font-weight:500;text-decoration:none">
        + Nuevo proyecto
    </a>
</div>

<div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);overflow:hidden">
    <table style="width:100%;border-collapse:collapse;font-size:14px">
        <thead>
            <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb">
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">#</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Nombre</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Cliente</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Estado</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Fin estimado</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Presupuesto</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($proyectos as $proyecto)
            @php
                $colores = [
                    'activo'     => ['bg'=>'#dbeafe','color'=>'#1e40af'],
                    'pendiente'  => ['bg'=>'#fef3c7','color'=>'#92400e'],
                    'completado' => ['bg'=>'#d1fae5','color'=>'#065f46'],
                    'suspendido' => ['bg'=>'#fee2e2','color'=>'#991b1b'],
                ];
                $c = $colores[$proyecto->estado] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
            @endphp
            <tr style="border-bottom:1px solid #f3f4f6">
                <td style="padding:12px 16px;color:#9ca3af">{{ $proyecto->id_proyecto }}</td>
                <td style="padding:12px 16px;font-weight:500;color:#111827">{{ $proyecto->nombre }}</td>
                <td style="padding:12px 16px;color:#374151">
                    {{ $proyecto->cliente->persona->nombre ?? '—' }}
                    {{ $proyecto->cliente->persona->apellido ?? '' }}
                </td>
                <td style="padding:12px 16px">
                    <span style="background:{{ $c['bg'] }};color:{{ $c['color'] }};
                                 font-size:12px;font-weight:500;padding:3px 10px;border-radius:20px">
                        {{ $proyecto->estado }}
                    </span>
                </td>
                <td style="padding:12px 16px;color:#374151">
                    {{ \Carbon\Carbon::parse($proyecto->fecha_fin_estimada)->format('d/m/Y') }}
                </td>
                <td style="padding:12px 16px;color:#374151">
                    {{ $proyecto->presupuesto ? 'S/ ' . number_format($proyecto->presupuesto, 2) : '—' }}
                </td>
                <td style="padding:12px 16px">
                    <div style="display:flex;gap:8px">
                        <a href="{{ route('proyectos.show', $proyecto) }}"
                           style="font-size:12px;color:#4f46e5;text-decoration:none">Ver</a>
                        <a href="{{ route('proyectos.edit', $proyecto) }}"
                           style="font-size:12px;color:#059669;text-decoration:none">Editar</a>
                        <form action="{{ route('proyectos.destroy', $proyecto) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este proyecto?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                style="font-size:12px;color:#dc2626;background:none;border:none;cursor:pointer;padding:0">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:40px;text-align:center;color:#9ca3af">
                    No hay proyectos registrados aún.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($proyectos->hasPages())
    <div style="padding:16px;border-top:1px solid #e5e7eb">{{ $proyectos->links() }}</div>
    @endif
</div>
@endsection

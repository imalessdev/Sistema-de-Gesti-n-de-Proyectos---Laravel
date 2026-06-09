@extends('layouts.app')
@section('titulo', 'Reportes PDF')

@section('contenido')
<h2 style="font-size:20px;font-weight:600;color:#111827;margin:0 0 24px">Reportes PDF</h2>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:32px">

    <!-- Reporte Clientes -->
    <div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:24px">
        <div style="font-size:32px;margin-bottom:12px">👥</div>
        <h3 style="font-size:16px;font-weight:600;color:#111827;margin:0 0 8px">Reporte de Clientes</h3>
        <p style="font-size:13px;color:#6b7280;margin:0 0 20px;line-height:1.5">
            Lista completa de todos los clientes registrados con sus datos de contacto y cantidad de proyectos.
        </p>
        <a href="{{ route('reportes.clientes') }}" target="_blank"
           style="display:block;text-align:center;background:#4f46e5;color:#fff;
                  padding:10px;border-radius:8px;font-size:14px;font-weight:500;
                  text-decoration:none">
            Generar PDF
        </a>
    </div>

    <!-- Reporte Proyectos -->
    <div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:24px">
        <div style="font-size:32px;margin-bottom:12px">📁</div>
        <h3 style="font-size:16px;font-weight:600;color:#111827;margin:0 0 8px">Reporte de Proyectos</h3>
        <p style="font-size:13px;color:#6b7280;margin:0 0 20px;line-height:1.5">
            Resumen de todos los proyectos con su estado, fechas, presupuesto y porcentaje de avance.
        </p>
        <a href="{{ route('reportes.proyectos') }}" target="_blank"
           style="display:block;text-align:center;background:#4f46e5;color:#fff;
                  padding:10px;border-radius:8px;font-size:14px;font-weight:500;
                  text-decoration:none">
            Generar PDF
        </a>
    </div>

    <!-- Reporte Tareas -->
    <div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:24px">
        <div style="font-size:32px;margin-bottom:12px">✅</div>
        <h3 style="font-size:16px;font-weight:600;color:#111827;margin:0 0 8px">Reporte de Tareas</h3>
        <p style="font-size:13px;color:#6b7280;margin:0 0 16px;line-height:1.5">
            Detalle de tareas de un proyecto específico con sus asignados y estado actual.
        </p>
        <form method="GET" action="{{ route('reportes.tareas') }}" target="_blank">
            <select name="id_proyecto" required
                    style="width:100%;border:1px solid #d1d5db;border-radius:8px;
                           padding:8px 12px;font-size:13px;margin-bottom:10px;
                           box-sizing:border-box;background:#fff">
                <option value="">Selecciona un proyecto</option>
                @foreach($proyectos as $proyecto)
                <option value="{{ $proyecto->id_proyecto }}">{{ $proyecto->nombre }}</option>
                @endforeach
            </select>
            <button type="submit"
                    style="width:100%;background:#4f46e5;color:#fff;border:none;
                           padding:10px;border-radius:8px;font-size:14px;
                           font-weight:500;cursor:pointer">
                Generar PDF
            </button>
        </form>
    </div>

</div>

<!-- Historial -->
<div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);overflow:hidden">
    <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb">
        <h3 style="font-size:15px;font-weight:600;color:#374151;margin:0">Historial de reportes generados</h3>
    </div>
    <table style="width:100%;border-collapse:collapse;font-size:14px">
        <thead>
            <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb">
                <th style="padding:10px 16px;text-align:left;font-weight:500;color:#6b7280">Archivo</th>
                <th style="padding:10px 16px;text-align:left;font-weight:500;color:#6b7280">Tipo</th>
                <th style="padding:10px 16px;text-align:left;font-weight:500;color:#6b7280">Generado</th>
                <th style="padding:10px 16px;text-align:left;font-weight:500;color:#6b7280">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($historial as $reporte)
            <tr style="border-bottom:1px solid #f3f4f6">
                <td style="padding:10px 16px;color:#374151">{{ $reporte->nombre_archivo }}</td>
                <td style="padding:10px 16px">
                    @php
                        $tc = ['clientes'=>['bg'=>'#dbeafe','color'=>'#1e40af'],
                               'proyectos'=>['bg'=>'#d1fae5','color'=>'#065f46'],
                               'tareas'=>['bg'=>'#ede9fe','color'=>'#5b21b6']];
                        $t = $tc[$reporte->tipo] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
                    @endphp
                    <span style="background:{{ $t['bg'] }};color:{{ $t['color'] }};
                                 font-size:12px;font-weight:500;padding:2px 8px;border-radius:20px">
                        {{ $reporte->tipo }}
                    </span>
                </td>
                <td style="padding:10px 16px;color:#6b7280;font-size:13px">
                    {{ \Carbon\Carbon::parse($reporte->fecha_generacion)->format('d/m/Y H:i') }}
                </td>
                <td style="padding:10px 16px">
                    <a href="{{ route('reportes.descargar', $reporte) }}"
                       style="font-size:13px;color:#4f46e5;text-decoration:none">
                        ⬇ Descargar
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:32px;text-align:center;color:#9ca3af;font-size:14px">
                    Aún no has generado ningún reporte.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

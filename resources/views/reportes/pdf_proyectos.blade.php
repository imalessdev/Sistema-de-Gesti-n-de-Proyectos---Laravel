<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size:10px; color:#1f2937; }
        .header { background:#4f46e5; color:#fff; padding:18px 24px; margin-bottom:16px; }
        .header h1 { font-size:17px; font-weight:700; margin-bottom:3px; }
        .header p  { font-size:10px; opacity:.85; }
        .meta { display:flex; justify-content:space-between; padding:0 24px 14px; font-size:10px; color:#6b7280; }
        .section { padding:0 24px; }
        table { width:100%; border-collapse:collapse; font-size:9.5px; }
        thead tr { background:#4f46e5; color:#fff; }
        thead th { padding:7px 10px; text-align:left; font-weight:600; }
        tbody tr:nth-child(even) { background:#f9fafb; }
        tbody tr:nth-child(odd)  { background:#ffffff; }
        tbody td { padding:6px 10px; border-bottom:1px solid #e5e7eb; vertical-align:middle; }
        .badge { display:inline-block; padding:2px 7px; border-radius:10px; font-size:8.5px; font-weight:700; }
        .badge-activo     { background:#dbeafe; color:#1e40af; }
        .badge-pendiente  { background:#fef3c7; color:#92400e; }
        .badge-completado { background:#d1fae5; color:#065f46; }
        .badge-suspendido { background:#fee2e2; color:#991b1b; }
        .progress-wrap { background:#e5e7eb; border-radius:4px; height:6px; width:70px; }
        .progress-fill { background:#4f46e5; height:6px; border-radius:4px; }
        .footer { position:fixed; bottom:0; left:0; right:0; text-align:center;
                  font-size:9px; color:#9ca3af; padding:6px; border-top:1px solid #e5e7eb; }
        .resumen { display:table; width:100%; margin-bottom:14px; padding:0 24px; }
        .res-cell { display:table-cell; background:#f3f4f6; border-radius:6px;
                    padding:8px 12px; width:25%; }
        .res-cell .num { font-size:18px; font-weight:700; color:#4f46e5; }
        .res-cell .lbl { font-size:9px; color:#6b7280; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Reporte de Proyectos</h1>
        <p>TecnoSoluciones S.A. — Sistema de Gestión de Proyectos</p>
    </div>

    <div class="meta">
        <span>Generado: {{ now()->format('d/m/Y H:i') }}</span>
        <span>Total: {{ $proyectos->count() }} proyectos</span>
    </div>

    {{-- Resumen por estado --}}
    @php
        $activos     = $proyectos->where('estado','activo')->count();
        $pendientes  = $proyectos->where('estado','pendiente')->count();
        $completados = $proyectos->where('estado','completado')->count();
        $suspendidos = $proyectos->where('estado','suspendido')->count();
    @endphp

    <div style="padding:0 24px;margin-bottom:14px">
        <table style="width:100%;border-collapse:separate;border-spacing:6px">
            <tr>
                <td style="background:#dbeafe;border-radius:6px;padding:8px 12px;text-align:center">
                    <div style="font-size:18px;font-weight:700;color:#1e40af">{{ $activos }}</div>
                    <div style="font-size:9px;color:#1e40af">Activos</div>
                </td>
                <td style="background:#fef3c7;border-radius:6px;padding:8px 12px;text-align:center">
                    <div style="font-size:18px;font-weight:700;color:#92400e">{{ $pendientes }}</div>
                    <div style="font-size:9px;color:#92400e">Pendientes</div>
                </td>
                <td style="background:#d1fae5;border-radius:6px;padding:8px 12px;text-align:center">
                    <div style="font-size:18px;font-weight:700;color:#065f46">{{ $completados }}</div>
                    <div style="font-size:9px;color:#065f46">Completados</div>
                </td>
                <td style="background:#fee2e2;border-radius:6px;padding:8px 12px;text-align:center">
                    <div style="font-size:18px;font-weight:700;color:#991b1b">{{ $suspendidos }}</div>
                    <div style="font-size:9px;color:#991b1b">Suspendidos</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Proyecto</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Inicio</th>
                    <th>Fin estimado</th>
                    <th>Presupuesto</th>
                    <th>Tareas</th>
                    <th>Avance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proyectos as $i => $proyecto)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $proyecto->nombre }}</strong></td>
                    <td>
                        {{ $proyecto->cliente->persona->nombre ?? '—' }}
                        {{ $proyecto->cliente->persona->apellido ?? '' }}
                        @if($proyecto->cliente->empresa)
                            <br><span style="color:#9ca3af">{{ $proyecto->cliente->empresa }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $proyecto->estado }}">
                            {{ $proyecto->estado }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($proyecto->fecha_fin_estimada)->format('d/m/Y') }}</td>
                    <td>{{ $proyecto->presupuesto ? 'S/ '.number_format($proyecto->presupuesto,2) : '—' }}</td>
                    <td style="text-align:center">{{ $proyecto->tareas->count() }}</td>
                    <td>
                        <div class="progress-wrap">
                            <div class="progress-fill" style="width:{{ $proyecto->avance }}%"></div>
                        </div>
                        <span style="font-size:9px;color:#6b7280">{{ $proyecto->avance }}%</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        TecnoSoluciones S.A. — Documento generado automáticamente — Confidencial
    </div>

</body>
</html>

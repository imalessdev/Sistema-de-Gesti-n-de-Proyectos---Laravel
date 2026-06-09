<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size:11px; color:#1f2937; }
        .header { background:#4f46e5; color:#fff; padding:20px 24px; margin-bottom:6px; }
        .header h1 { font-size:17px; font-weight:700; margin-bottom:3px; }
        .header p  { font-size:10px; opacity:.85; }
        .proyecto-info { background:#f3f4f6; padding:12px 24px; margin-bottom:16px;
                         border-left:4px solid #4f46e5; }
        .proyecto-info h2 { font-size:13px; font-weight:700; color:#111827; margin-bottom:4px; }
        .proyecto-info p  { font-size:10px; color:#6b7280; }
        .meta { display:flex; justify-content:space-between; padding:0 24px 14px; font-size:10px; color:#6b7280; }
        .section { padding:0 24px; }
        table { width:100%; border-collapse:collapse; font-size:10px; }
        thead tr { background:#4f46e5; color:#fff; }
        thead th { padding:8px 12px; text-align:left; font-weight:600; }
        tbody tr:nth-child(even) { background:#f9fafb; }
        tbody tr:nth-child(odd)  { background:#ffffff; }
        tbody td { padding:7px 12px; border-bottom:1px solid #e5e7eb; vertical-align:top; }
        .badge { display:inline-block; padding:2px 7px; border-radius:10px; font-size:8.5px; font-weight:700; }
        .badge-urgente    { background:#fee2e2; color:#991b1b; }
        .badge-alta       { background:#fef3c7; color:#92400e; }
        .badge-media      { background:#ede9fe; color:#5b21b6; }
        .badge-baja       { background:#d1fae5; color:#065f46; }
        .badge-pendiente  { background:#f3f4f6;  color:#374151; }
        .badge-en_progreso{ background:#dbeafe;  color:#1e40af; }
        .badge-completada { background:#d1fae5;  color:#065f46; }
        .badge-cancelada  { background:#fee2e2;  color:#991b1b; }
        .vencida { color:#dc2626; font-weight:700; }
        .footer { position:fixed; bottom:0; left:0; right:0; text-align:center;
                  font-size:9px; color:#9ca3af; padding:6px; border-top:1px solid #e5e7eb; }
        .stats { display:table; width:100%; margin-bottom:14px; padding:0 24px; }
        .stat { display:table-cell; text-align:center; padding:8px; }
        .stat .num { font-size:18px; font-weight:700; color:#4f46e5; }
        .stat .lbl { font-size:9px; color:#6b7280; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Reporte de Tareas</h1>
        <p>TecnoSoluciones S.A. — Sistema de Gestión de Proyectos</p>
    </div>

    <div class="proyecto-info">
        <h2>📁 {{ $proyecto->nombre }}</h2>
        <p>
            Cliente: {{ $proyecto->cliente->persona->nombre }} {{ $proyecto->cliente->persona->apellido }}
            {{ $proyecto->cliente->empresa ? ' — ' . $proyecto->cliente->empresa : '' }}
            &nbsp;·&nbsp;
            Estado: {{ strtoupper($proyecto->estado) }}
            &nbsp;·&nbsp;
            Fin estimado: {{ \Carbon\Carbon::parse($proyecto->fecha_fin_estimada)->format('d/m/Y') }}
        </p>
    </div>

    <div class="meta">
        <span>Generado: {{ now()->format('d/m/Y H:i') }}</span>
        <span>Total de tareas: {{ $proyecto->tareas->count() }}</span>
    </div>

    {{-- Estadísticas --}}
    @php
        $tareas      = $proyecto->tareas;
        $completadas = $tareas->where('estado','completada')->count();
        $enProgreso  = $tareas->where('estado','en_progreso')->count();
        $pendientes  = $tareas->where('estado','pendiente')->count();
        $canceladas  = $tareas->where('estado','cancelada')->count();
        $avance      = $proyecto->calcularAvance();
    @endphp

    <div style="padding:0 24px;margin-bottom:14px">
        <table style="width:100%;border-collapse:separate;border-spacing:5px">
            <tr>
                <td style="background:#d1fae5;border-radius:6px;padding:7px 10px;text-align:center">
                    <div style="font-size:16px;font-weight:700;color:#065f46">{{ $completadas }}</div>
                    <div style="font-size:9px;color:#065f46">Completadas</div>
                </td>
                <td style="background:#dbeafe;border-radius:6px;padding:7px 10px;text-align:center">
                    <div style="font-size:16px;font-weight:700;color:#1e40af">{{ $enProgreso }}</div>
                    <div style="font-size:9px;color:#1e40af">En progreso</div>
                </td>
                <td style="background:#f3f4f6;border-radius:6px;padding:7px 10px;text-align:center">
                    <div style="font-size:16px;font-weight:700;color:#374151">{{ $pendientes }}</div>
                    <div style="font-size:9px;color:#374151">Pendientes</div>
                </td>
                <td style="background:#fee2e2;border-radius:6px;padding:7px 10px;text-align:center">
                    <div style="font-size:16px;font-weight:700;color:#991b1b">{{ $canceladas }}</div>
                    <div style="font-size:9px;color:#991b1b">Canceladas</div>
                </td>
                <td style="background:#ede9fe;border-radius:6px;padding:7px 10px;text-align:center">
                    <div style="font-size:16px;font-weight:700;color:#5b21b6">{{ $avance }}%</div>
                    <div style="font-size:9px;color:#5b21b6">Avance</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Fecha límite</th>
                    <th>Completada</th>
                    <th>Asignado a</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proyecto->tareas as $i => $tarea)
                @php $vencida = $tarea->estaVencida(); @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <strong>{{ $tarea->titulo }}</strong>
                        @if($tarea->descripcion)
                            <br><span style="color:#9ca3af;font-size:9px">{{ Str::limit($tarea->descripcion, 60) }}</span>
                        @endif
                    </td>
                    <td><span class="badge badge-{{ $tarea->prioridad }}">{{ $tarea->prioridad }}</span></td>
                    <td><span class="badge badge-{{ $tarea->estado }}">{{ str_replace('_',' ',$tarea->estado) }}</span></td>
                    <td class="{{ $vencida ? 'vencida' : '' }}">
                        {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}
                        {{ $vencida ? '⚠' : '' }}
                    </td>
                    <td>
                        {{ $tarea->fecha_completada
                            ? \Carbon\Carbon::parse($tarea->fecha_completada)->format('d/m/Y')
                            : '—' }}
                    </td>
                    <td>
                        @if($tarea->asignados->count())
                            {{ $tarea->asignados->map(fn($u) => $u->nombre.' '.$u->apellido)->join(', ') }}
                        @else
                            <span style="color:#9ca3af">Sin asignar</span>
                        @endif
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

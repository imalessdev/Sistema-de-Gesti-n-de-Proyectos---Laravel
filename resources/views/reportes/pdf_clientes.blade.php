<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size:11px; color:#1f2937; }
        .header { background:#4f46e5; color:#fff; padding:20px 24px; margin-bottom:20px; }
        .header h1 { font-size:18px; font-weight:700; margin-bottom:4px; }
        .header p  { font-size:11px; opacity:.85; }
        .meta { display:flex; justify-content:space-between; padding:0 24px 16px; font-size:10px; color:#6b7280; }
        table { width:100%; border-collapse:collapse; font-size:10px; }
        thead tr { background:#4f46e5; color:#fff; }
        thead th { padding:8px 12px; text-align:left; font-weight:600; }
        tbody tr:nth-child(even) { background:#f9fafb; }
        tbody tr:nth-child(odd)  { background:#ffffff; }
        tbody td { padding:7px 12px; border-bottom:1px solid #e5e7eb; }
        .badge { display:inline-block; padding:2px 7px; border-radius:10px; font-size:9px; font-weight:600; }
        .badge-activo  { background:#dbeafe; color:#1e40af; }
        .badge-ninguno { background:#f3f4f6; color:#6b7280; }
        .footer { position:fixed; bottom:0; left:0; right:0; text-align:center;
                  font-size:9px; color:#9ca3af; padding:8px; border-top:1px solid #e5e7eb; }
        .page-break { page-break-after:always; }
        .section { padding:0 24px; }
        .summary { display:flex; gap:16px; padding:0 24px 16px; }
        .summary-card { flex:1; background:#f3f4f6; border-radius:6px; padding:10px 14px; }
        .summary-card .num { font-size:20px; font-weight:700; color:#4f46e5; }
        .summary-card .lbl { font-size:10px; color:#6b7280; margin-top:2px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Reporte de Clientes</h1>
        <p>TecnoSoluciones S.A. — Sistema de Gestión de Proyectos</p>
    </div>

    <div class="meta">
        <span>Generado: {{ now()->format('d/m/Y H:i') }}</span>
        <span>Total de clientes: {{ $clientes->count() }}</span>
    </div>

    <div class="section" style="margin-bottom:16px">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre completo</th>
                    <th>Empresa</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Proyectos</th>
                    <th>Registro</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $i => $cliente)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $cliente->persona->nombre }} {{ $cliente->persona->apellido }}</strong></td>
                    <td>{{ $cliente->empresa ?? '—' }}</td>
                    <td>{{ $cliente->persona->email }}</td>
                    <td>{{ $cliente->persona->telefono ?? '—' }}</td>
                    <td>{{ $cliente->persona->direccion ?? '—' }}</td>
                    <td style="text-align:center">{{ $cliente->proyectos->count() }}</td>
                    <td>{{ \Carbon\Carbon::parse($cliente->fecha_registro)->format('d/m/Y') }}</td>
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

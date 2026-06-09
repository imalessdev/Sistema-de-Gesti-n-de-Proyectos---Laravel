@extends('layouts.app')
@section('titulo', 'Clientes')

@section('contenido')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
    <div>
        <h2 style="font-size:20px;font-weight:600;color:#111827;margin:0">Clientes</h2>
        <p style="font-size:13px;color:#6b7280;margin:4px 0 0">
            {{ $clientes->total() }} clientes registrados
        </p>
    </div>
    <a href="{{ route('clientes.create') }}"
       style="background:#4f46e5;color:#fff;padding:8px 16px;border-radius:8px;
              font-size:14px;font-weight:500;text-decoration:none">
        + Nuevo cliente
    </a>
</div>

<div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);overflow:hidden">
    <table style="width:100%;border-collapse:collapse;font-size:14px">
        <thead>
            <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb">
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">#</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Nombre</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Empresa</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Email</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Teléfono</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Registro</th>
                <th style="padding:12px 16px;text-align:left;font-weight:500;color:#6b7280">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clientes as $cliente)
            <tr style="border-bottom:1px solid #f3f4f6">
                <td style="padding:12px 16px;color:#9ca3af">{{ $cliente->id_cliente }}</td>
                <td style="padding:12px 16px;font-weight:500;color:#111827">
                    {{ $cliente->persona->nombre }} {{ $cliente->persona->apellido }}
                </td>
                <td style="padding:12px 16px;color:#374151">
                    {{ $cliente->empresa ?? '—' }}
                </td>
                <td style="padding:12px 16px;color:#374151">{{ $cliente->persona->email }}</td>
                <td style="padding:12px 16px;color:#374151">{{ $cliente->persona->telefono ?? '—' }}</td>
                <td style="padding:12px 16px;color:#6b7280">
                    {{ \Carbon\Carbon::parse($cliente->fecha_registro)->format('d/m/Y') }}
                </td>
                <td style="padding:12px 16px">
                    <div style="display:flex;gap:8px">
                        <a href="{{ route('clientes.show', $cliente) }}"
                           style="font-size:12px;color:#4f46e5;text-decoration:none">Ver</a>
                        <a href="{{ route('clientes.edit', $cliente) }}"
                           style="font-size:12px;color:#059669;text-decoration:none">Editar</a>
                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este cliente?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                style="font-size:12px;color:#dc2626;background:none;
                                       border:none;cursor:pointer;padding:0">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:40px;text-align:center;color:#9ca3af">
                    No hay clientes registrados aún.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($clientes->hasPages())
    <div style="padding:16px;border-top:1px solid #e5e7eb">
        {{ $clientes->links() }}
    </div>
    @endif
</div>
@endsection

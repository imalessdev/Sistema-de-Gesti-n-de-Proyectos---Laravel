@extends('layouts.app')
@section('titulo', 'Editar Proyecto')

@section('contenido')
<div style="max-width:640px">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
        <a href="{{ route('proyectos.index') }}" style="color:#6b7280;text-decoration:none;font-size:14px">← Volver</a>
        <h2 style="font-size:20px;font-weight:600;color:#111827;margin:0">Editar Proyecto</h2>
    </div>

    <div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:28px">
        <form method="POST" action="{{ route('proyectos.update', $proyecto) }}">
            @csrf @method('PATCH')

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Cliente *</label>
                <select name="id_cliente" required
                        style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box;background:#fff">
                    @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id_cliente }}"
                        {{ old('id_cliente', $proyecto->id_cliente) == $cliente->id_cliente ? 'selected' : '' }}>
                        {{ $cliente->persona->nombre }} {{ $cliente->persona->apellido }}
                        {{ $cliente->empresa ? '— ' . $cliente->empresa : '' }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $proyecto->nombre) }}" required
                       style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box">
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Descripción</label>
                <textarea name="descripcion" rows="3"
                          style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box;resize:vertical">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Fecha inicio *</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $proyecto->fecha_inicio?->format('Y-m-d')) }}" required
                           style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Fin estimado *</label>
                    <input type="date" name="fecha_fin_estimada" value="{{ old('fecha_fin_estimada', $proyecto->fecha_fin_estimada?->format('Y-m-d')) }}" required
                           style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:24px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Fin real</label>
                    <input type="date" name="fecha_fin_real" value="{{ old('fecha_fin_real', $proyecto->fecha_fin_real?->format('Y-m-d')) }}"
                           style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Estado *</label>
                    <select name="estado" required
                            style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box;background:#fff">
                        @foreach(['pendiente','activo','completado','suspendido'] as $estado)
                        <option value="{{ $estado }}" {{ old('estado', $proyecto->estado) == $estado ? 'selected' : '' }}>
                            {{ ucfirst($estado) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Presupuesto (S/)</label>
                    <input type="number" name="presupuesto" value="{{ old('presupuesto', $proyecto->presupuesto) }}" step="0.01" min="0"
                           style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box">
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px">
                <a href="{{ route('proyectos.index') }}"
                   style="padding:8px 20px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;color:#374151;text-decoration:none">
                    Cancelar
                </a>
                <button type="submit"
                        style="padding:8px 20px;background:#4f46e5;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:500;cursor:pointer">
                    Actualizar proyecto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

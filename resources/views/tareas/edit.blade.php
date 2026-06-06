@extends('layouts.app')
@section('titulo', 'Editar Tarea')

@section('contenido')
<div style="max-width:640px">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
        <a href="{{ route('tareas.index') }}" style="color:#6b7280;text-decoration:none;font-size:14px">← Volver</a>
        <h2 style="font-size:20px;font-weight:600;color:#111827;margin:0">Editar Tarea</h2>
    </div>

    <div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:28px">
        <form method="POST" action="{{ route('tareas.update', $tarea) }}">
            @csrf @method('PATCH')

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Proyecto *</label>
                <select name="id_proyecto" required
                        style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box;background:#fff">
                    @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id_proyecto }}"
                        {{ old('id_proyecto', $tarea->id_proyecto) == $proyecto->id_proyecto ? 'selected' : '' }}>
                        {{ $proyecto->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Título *</label>
                <input type="text" name="titulo" value="{{ old('titulo', $tarea->titulo) }}" required
                       style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box">
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Descripción</label>
                <textarea name="descripcion" rows="3"
                          style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box;resize:vertical">{{ old('descripcion', $tarea->descripcion) }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Prioridad *</label>
                    <select name="prioridad" required
                            style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box;background:#fff">
                        @foreach(['baja','media','alta','urgente'] as $p)
                        <option value="{{ $p }}" {{ old('prioridad', $tarea->prioridad) == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Estado *</label>
                    <select name="estado" required
                            style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box;background:#fff">
                        @foreach(['pendiente','en_progreso','completada','cancelada'] as $e)
                        <option value="{{ $e }}" {{ old('estado', $tarea->estado) == $e ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $tarea->fecha_inicio?->format('Y-m-d')) }}"
                           style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Fecha límite *</label>
                    <input type="date" name="fecha_limite" value="{{ old('fecha_limite', $tarea->fecha_limite?->format('Y-m-d')) }}" required
                           style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:14px;box-sizing:border-box">
                </div>
            </div>

            <div style="margin-bottom:24px">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:8px">Asignar a</label>
                <div style="border:1px solid #d1d5db;border-radius:8px;padding:12px;max-height:160px;overflow-y:auto">
                    @foreach($usuarios as $usuario)
                    <label style="display:flex;align-items:center;gap:8px;padding:4px 0;cursor:pointer;font-size:14px">
                        <input type="checkbox" name="asignados[]" value="{{ $usuario->id_usuario }}"
                               {{ in_array($usuario->id_usuario, old('asignados', $asignados)) ? 'checked' : '' }}>
                        {{ $usuario->nombre }} {{ $usuario->apellido }}
                        <span style="font-size:12px;color:#9ca3af">({{ $usuario->rol }})</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px">
                <a href="{{ route('tareas.index') }}"
                   style="padding:8px 20px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;color:#374151;text-decoration:none">
                    Cancelar
                </a>
                <button type="submit"
                        style="padding:8px 20px;background:#4f46e5;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:500;cursor:pointer">
                    Actualizar tarea
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

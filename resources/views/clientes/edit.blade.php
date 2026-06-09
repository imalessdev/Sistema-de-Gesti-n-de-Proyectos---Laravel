@extends('layouts.app')
@section('titulo', 'Editar Cliente')

@section('contenido')
<div style="max-width:640px">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
        <a href="{{ route('clientes.index') }}"
           style="color:#6b7280;text-decoration:none;font-size:14px">← Volver</a>
        <h2 style="font-size:20px;font-weight:600;color:#111827;margin:0">
            Editar Cliente
        </h2>
    </div>

    <div style="background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:28px">
        <form method="POST" action="{{ route('clientes.update', $cliente) }}">
            @csrf
            @method('PATCH')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">
                        Nombre *
                    </label>
                    <input type="text" name="nombre"
                           value="{{ old('nombre', $cliente->persona->nombre) }}"
                           style="width:100%;border:1px solid #d1d5db;border-radius:8px;
                                  padding:8px 12px;font-size:14px;box-sizing:border-box"
                           required>
                    @error('nombre')
                        <p style="color:#dc2626;font-size:12px;margin-top:4px">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">
                        Apellido *
                    </label>
                    <input type="text" name="apellido"
                           value="{{ old('apellido', $cliente->persona->apellido) }}"
                           style="width:100%;border:1px solid #d1d5db;border-radius:8px;
                                  padding:8px 12px;font-size:14px;box-sizing:border-box"
                           required>
                    @error('apellido')
                        <p style="color:#dc2626;font-size:12px;margin-top:4px">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">
                    Email *
                </label>
                <input type="email" name="email"
                       value="{{ old('email', $cliente->persona->email) }}"
                       style="width:100%;border:1px solid #d1d5db;border-radius:8px;
                              padding:8px 12px;font-size:14px;box-sizing:border-box"
                       required>
                @error('email')
                    <p style="color:#dc2626;font-size:12px;margin-top:4px">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">
                        Teléfono
                    </label>
                    <input type="text" name="telefono"
                           value="{{ old('telefono', $cliente->persona->telefono) }}"
                           style="width:100%;border:1px solid #d1d5db;border-radius:8px;
                                  padding:8px 12px;font-size:14px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">
                        Empresa
                    </label>
                    <input type="text" name="empresa"
                           value="{{ old('empresa', $cliente->empresa) }}"
                           style="width:100%;border:1px solid #d1d5db;border-radius:8px;
                                  padding:8px 12px;font-size:14px;box-sizing:border-box">
                </div>
            </div>

            <div style="margin-bottom:24px">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">
                    Dirección
                </label>
                <textarea name="direccion" rows="2"
                          style="width:100%;border:1px solid #d1d5db;border-radius:8px;
                                 padding:8px 12px;font-size:14px;box-sizing:border-box;resize:vertical">{{ old('direccion', $cliente->persona->direccion) }}</textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px">
                <a href="{{ route('clientes.index') }}"
                   style="padding:8px 20px;border:1px solid #d1d5db;border-radius:8px;
                          font-size:14px;color:#374151;text-decoration:none">
                    Cancelar
                </a>
                <button type="submit"
                        style="padding:8px 20px;background:#4f46e5;color:#fff;
                               border:none;border-radius:8px;font-size:14px;
                               font-weight:500;cursor:pointer">
                    Actualizar cliente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProyectoController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::with(['cliente.persona'])
                             ->orderBy('id_proyecto', 'desc')
                             ->paginate(10);
        return view('proyectos.index', compact('proyectos'));
    }

    public function create()
    {
        $clientes = Cliente::with('persona')->get();
        return view('proyectos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente'         => ['required', 'exists:cliente,id_cliente'],
            'nombre'             => ['required', 'string', 'max:200'],
            'descripcion'        => ['nullable', 'string'],
            'fecha_inicio'       => ['required', 'date'],
            'fecha_fin_estimada' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'presupuesto'        => ['nullable', 'numeric', 'min:0'],
            'estado'             => ['required', 'in:pendiente,activo,completado,suspendido'],
        ]);

        Proyecto::create([
            'id_cliente'          => $request->id_cliente,
            'id_usuario_creador'  => Auth::id(),
            'nombre'              => $request->nombre,
            'descripcion'         => $request->descripcion,
            'fecha_inicio'        => $request->fecha_inicio,
            'fecha_fin_estimada'  => $request->fecha_fin_estimada,
            'presupuesto'         => $request->presupuesto,
            'estado'              => $request->estado,
        ]);

        return redirect()->route('proyectos.index')
                         ->with('success', 'Proyecto creado correctamente.');
    }

    public function show(Proyecto $proyecto)
    {
        $proyecto->load(['cliente.persona', 'creador', 'tareas.asignados']);
        $avance = $proyecto->calcularAvance();
        return view('proyectos.show', compact('proyecto', 'avance'));
    }

    public function edit(Proyecto $proyecto)
    {
        $clientes = Cliente::with('persona')->get();
        return view('proyectos.edit', compact('proyecto', 'clientes'));
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        $request->validate([
            'id_cliente'         => ['required', 'exists:cliente,id_cliente'],
            'nombre'             => ['required', 'string', 'max:200'],
            'descripcion'        => ['nullable', 'string'],
            'fecha_inicio'       => ['required', 'date'],
            'fecha_fin_estimada' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'fecha_fin_real'     => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'presupuesto'        => ['nullable', 'numeric', 'min:0'],
            'estado'             => ['required', 'in:pendiente,activo,completado,suspendido'],
        ]);

        $proyecto->update($request->only([
            'id_cliente', 'nombre', 'descripcion',
            'fecha_inicio', 'fecha_fin_estimada', 'fecha_fin_real',
            'presupuesto', 'estado',
        ]));

        return redirect()->route('proyectos.index')
                         ->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Proyecto $proyecto)
    {
        $proyecto->delete();
        return redirect()->route('proyectos.index')
                         ->with('success', 'Proyecto eliminado correctamente.');
    }
}

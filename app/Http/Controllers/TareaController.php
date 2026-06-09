<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    public function index()
    {
        $tareas = Tarea::with(['proyecto', 'creador', 'asignados'])
                       ->orderBy('fecha_limite')
                       ->paginate(10);
        return view('tareas.index', compact('tareas'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        $usuarios  = User::where('activo', 1)->get();
        return view('tareas.create', compact('proyectos', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proyecto'  => ['required', 'exists:proyecto,id_proyecto'],
            'titulo'       => ['required', 'string', 'max:200'],
            'descripcion'  => ['nullable', 'string'],
            'prioridad'    => ['required', 'in:baja,media,alta,urgente'],
            'estado'       => ['required', 'in:pendiente,en_progreso,completada,cancelada'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_limite' => ['required', 'date'],
            'asignados'    => ['nullable', 'array'],
            'asignados.*'  => ['exists:usuario,id_usuario'],
        ]);

        $tarea = Tarea::create([
            'id_proyecto'        => $request->id_proyecto,
            'id_usuario_creador' => Auth::id(),
            'titulo'             => $request->titulo,
            'descripcion'        => $request->descripcion,
            'prioridad'          => $request->prioridad,
            'estado'             => $request->estado,
            'fecha_inicio'       => $request->fecha_inicio,
            'fecha_limite'       => $request->fecha_limite,
        ]);

        // Asignar usuarios seleccionados
        if ($request->asignados) {
            foreach ($request->asignados as $idUsuario) {
                if (!Asignacion::estaAsignado($tarea->id_tarea, $idUsuario)) {
                    Asignacion::create([
                        'id_tarea'         => $tarea->id_tarea,
                        'id_usuario'       => $idUsuario,
                        'fecha_asignacion' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('tareas.index')
                         ->with('success', 'Tarea creada correctamente.');
    }

    public function show(Tarea $tarea)
    {
        $tarea->load(['proyecto.cliente.persona', 'creador', 'asignados']);
        return view('tareas.show', compact('tarea'));
    }

    public function edit(Tarea $tarea)
    {
        $proyectos  = Proyecto::orderBy('nombre')->get();
        $usuarios   = User::where('activo', 1)->get();
        $asignados  = $tarea->asignados->pluck('id_usuario')->toArray();
        return view('tareas.edit', compact('tarea', 'proyectos', 'usuarios', 'asignados'));
    }

    public function update(Request $request, Tarea $tarea)
    {
        $request->validate([
            'id_proyecto'  => ['required', 'exists:proyecto,id_proyecto'],
            'titulo'       => ['required', 'string', 'max:200'],
            'descripcion'  => ['nullable', 'string'],
            'prioridad'    => ['required', 'in:baja,media,alta,urgente'],
            'estado'       => ['required', 'in:pendiente,en_progreso,completada,cancelada'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_limite' => ['required', 'date'],
            'asignados'    => ['nullable', 'array'],
            'asignados.*'  => ['exists:usuario,id_usuario'],
        ]);

        $tarea->actualizarEstado($request->estado);
        $tarea->update($request->only([
            'id_proyecto', 'titulo', 'descripcion',
            'prioridad', 'fecha_inicio', 'fecha_limite',
        ]));

        // Sincronizar asignaciones
        Asignacion::where('id_tarea', $tarea->id_tarea)->delete();
        if ($request->asignados) {
            foreach ($request->asignados as $idUsuario) {
                Asignacion::create([
                    'id_tarea'         => $tarea->id_tarea,
                    'id_usuario'       => $idUsuario,
                    'fecha_asignacion' => now(),
                ]);
            }
        }

        return redirect()->route('tareas.index')
                         ->with('success', 'Tarea actualizada correctamente.');
    }

    public function destroy(Tarea $tarea)
    {
        $tarea->delete();
        return redirect()->route('tareas.index')
                         ->with('success', 'Tarea eliminada correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Métricas principales ──────────────────────────
        $totalClientes  = Cliente::count();
        $totalUsuarios  = User::withoutGlobalScopes()
                              ->where('activo', 1)->count();

        $proyectos      = Proyecto::all();
        $totalProyectos = $proyectos->count();
        $proyActivos    = $proyectos->where('estado', 'activo')->count();
        $proyPendientes = $proyectos->where('estado', 'pendiente')->count();
        $proyCompletados= $proyectos->where('estado', 'completado')->count();
        $proySuspendidos= $proyectos->where('estado', 'suspendido')->count();

        $tareas         = Tarea::all();
        $totalTareas    = $tareas->count();
        $tareasVencidas = $tareas->filter(fn($t) => $t->estaVencida())->count();

        // ── Proyectos activos con avance ──────────────────
        $proyectosActivos = Proyecto::with(['cliente.persona', 'tareas'])
            ->where('estado', 'activo')
            ->orderBy('fecha_fin_estimada')
            ->take(5)
            ->get()
            ->map(function ($p) {
                $p->avance = $p->calcularAvance();
                return $p;
            });

        // ── Tareas próximas a vencer ──────────────────────
        $tareasPorVencer = Tarea::with(['proyecto'])
            ->whereNotIn('estado', ['completada', 'cancelada'])
            ->where('fecha_limite', '>=', Carbon::today())
            ->orderBy('fecha_limite')
            ->take(5)
            ->get();

        // ── Tareas vencidas sin resolver ──────────────────
        $tareasVencidasLista = Tarea::with(['proyecto'])
            ->whereNotIn('estado', ['completada', 'cancelada'])
            ->where('fecha_limite', '<', Carbon::today())
            ->orderBy('fecha_limite')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalClientes', 'totalUsuarios',
            'totalProyectos', 'proyActivos', 'proyPendientes',
            'proyCompletados', 'proySuspendidos',
            'totalTareas', 'tareasVencidas',
            'proyectosActivos',
            'tareasPorVencer',
            'tareasVencidasLista'
        ));
    }
}

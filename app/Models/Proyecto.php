<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Proyecto extends Model
{
    protected $table      = 'proyecto';
    protected $primaryKey = 'id_proyecto';
    public    $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_usuario_creador',
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin_estimada',
        'fecha_fin_real',
        'estado',
        'presupuesto',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio'        => 'date',
            'fecha_fin_estimada'  => 'date',
            'fecha_fin_real'      => 'date',
            'presupuesto'         => 'decimal:2',
        ];
    }

    /*
    |-----------------------------------------------------------
    | Lógica de negocio
    |-----------------------------------------------------------
    */

    // Porcentaje de avance según tareas completadas
    public function calcularAvance(): float
    {
        $total = $this->tareas()->count();
        if ($total === 0) return 0.0;

        $completadas = $this->tareas()
                            ->where('estado', 'completada')
                            ->count();

        return round(($completadas / $total) * 100, 1);
    }

    // ¿El proyecto ya pasó su fecha estimada sin completarse?
    public function estaVencido(): bool
    {
        return $this->fecha_fin_estimada < Carbon::today()
            && !in_array($this->estado, ['completado', 'suspendido']);
    }

    // Cambiar estado validando valores permitidos
    public function cambiarEstado(string $nuevoEstado): bool
    {
        $permitidos = ['pendiente', 'activo', 'completado', 'suspendido'];
        if (!in_array($nuevoEstado, $permitidos)) return false;

        $this->estado = $nuevoEstado;
        return $this->save();
    }

    /*
    |-----------------------------------------------------------
    | Scopes — filtros reutilizables
    |-----------------------------------------------------------
    */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeVencidos($query)
    {
        return $query->where('fecha_fin_estimada', '<', Carbon::today())
                     ->whereNotIn('estado', ['completado', 'suspendido']);
    }

    public function scopePorEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }

    /*
    |-----------------------------------------------------------
    | Relaciones
    |-----------------------------------------------------------
    */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'id_usuario_creador');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'id_proyecto');
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'id_proyecto');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tarea extends Model
{
    protected $table      = 'tarea';
    protected $primaryKey = 'id_tarea';
    public    $timestamps = false;

    protected $fillable = [
        'id_proyecto',
        'id_usuario_creador',
        'titulo',
        'descripcion',
        'prioridad',
        'estado',
        'fecha_inicio',
        'fecha_limite',
        'fecha_completada',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio'    => 'date',
            'fecha_limite'    => 'date',
            'fecha_completada'=> 'date',
        ];
    }

    /*
    |-----------------------------------------------------------
    | Lógica de negocio
    |-----------------------------------------------------------
    */

    // Marca la tarea como completada registrando la fecha real
    public function completar(): bool
    {
        $this->estado           = 'completada';
        $this->fecha_completada = Carbon::today();
        return $this->save();
    }

    // ¿La tarea pasó su fecha límite sin completarse?
    public function estaVencida(): bool
    {
        return $this->fecha_limite < Carbon::today()
            && !in_array($this->estado, ['completada', 'cancelada']);
    }

    // Actualiza estado; si es "completada" llama a completar()
    public function actualizarEstado(string $nuevoEstado): bool
    {
        $permitidos = ['pendiente', 'en_progreso', 'completada', 'cancelada'];
        if (!in_array($nuevoEstado, $permitidos)) return false;

        if ($nuevoEstado === 'completada') {
            return $this->completar();
        }

        $this->estado = $nuevoEstado;
        return $this->save();
    }

    /*
    |-----------------------------------------------------------
    | Scopes
    |-----------------------------------------------------------
    */
    public function scopePendientes($query)
    {
        return $query->whereIn('estado', ['pendiente', 'en_progreso']);
    }

    public function scopeVencidas($query)
    {
        return $query->where('fecha_limite', '<', Carbon::today())
                     ->whereNotIn('estado', ['completada', 'cancelada']);
    }

    public function scopePorPrioridad($query, string $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }

    /*
    |-----------------------------------------------------------
    | Relaciones
    |-----------------------------------------------------------
    */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'id_usuario_creador');
    }

    // Usuarios asignados a esta tarea (N:M via asignacion)
    public function asignados()
    {
        return $this->belongsToMany(
            User::class,
            'asignacion',
            'id_tarea',
            'id_usuario'
        )->withPivot('fecha_asignacion', 'fecha_entrega_estimada', 'observaciones');
    }
}

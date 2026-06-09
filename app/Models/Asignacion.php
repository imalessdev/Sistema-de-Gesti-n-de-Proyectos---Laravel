<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $table      = 'asignacion';
    protected $primaryKey = 'id_asignacion';
    public    $timestamps = false;

    protected $fillable = [
        'id_tarea',
        'id_usuario',
        'fecha_asignacion',
        'fecha_entrega_estimada',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_asignacion'       => 'datetime',
            'fecha_entrega_estimada' => 'date',
        ];
    }

    /*
    |-----------------------------------------------------------
    | Lógica de negocio
    |-----------------------------------------------------------
    */

    // Verifica si ya existe la asignación antes de insertar
    public static function estaAsignado(int $idTarea, int $idUsuario): bool
    {
        return self::where('id_tarea', $idTarea)
                   ->where('id_usuario', $idUsuario)
                   ->exists();
    }

    /*
    |-----------------------------------------------------------
    | Relaciones
    |-----------------------------------------------------------
    */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'id_tarea');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}

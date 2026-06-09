<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table      = 'cliente';
    protected $primaryKey = 'id_cliente';
    public    $timestamps = false;

    protected $fillable = [
        'id_persona',
        'empresa',
        'fecha_registro',
    ];

    protected function casts(): array
    {
        return [
            'fecha_registro' => 'date',
        ];
    }

    /*
    |-----------------------------------------------------------
    | Accessors: acceder a datos de persona directamente
    | desde el cliente. Ej: $cliente->nombre_completo
    |-----------------------------------------------------------
    */
    public function getNombreCompletoAttribute(): string
    {
        return $this->persona
            ? "{$this->persona->nombre} {$this->persona->apellido}"
            : '';
    }

    public function getEmailAttribute(): string
    {
        return $this->persona->email ?? '';
    }

    /*
    |-----------------------------------------------------------
    | Lógica de negocio
    |-----------------------------------------------------------
    */
    public function tieneProyectosActivos(): bool
    {
        return $this->proyectos()
                    ->whereIn('estado', ['activo', 'pendiente'])
                    ->exists();
    }

    /*
    |-----------------------------------------------------------
    | Relaciones
    |-----------------------------------------------------------
    */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'id_cliente');
    }
}

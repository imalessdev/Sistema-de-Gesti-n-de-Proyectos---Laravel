<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table      = 'persona';
    protected $primaryKey = 'id_persona';
    public    $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'direccion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_creacion' => 'datetime',
        ];
    }

    /*
    |-----------------------------------------------------------
    | Atributo calculado: nombre completo
    |-----------------------------------------------------------
    */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /*
    |-----------------------------------------------------------
    | Relaciones — una persona puede ser usuario Y cliente
    |-----------------------------------------------------------
    */
    public function usuario()
    {
        return $this->hasOne(User::class, 'id_persona');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id_persona');
    }
}

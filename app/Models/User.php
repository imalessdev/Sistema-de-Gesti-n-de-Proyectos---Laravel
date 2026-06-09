<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table      = 'usuario';
    protected $primaryKey = 'id_usuario';
    public    $timestamps = false;

    /*
    |-----------------------------------------------------------
    | Global Scope: JOIN automático con persona en cada consulta
    |-----------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::addGlobalScope('con_persona', function ($query) {
            $query->join('persona', 'usuario.id_persona', '=', 'persona.id_persona')
                  ->select(
                      'usuario.id_usuario',
                      'usuario.id_persona',
                      'usuario.password_hash',
                      'usuario.rol',
                      'usuario.activo',
                      'persona.nombre',
                      'persona.apellido',
                      'persona.email',
                      'persona.telefono',
                      'persona.direccion',
                      'persona.fecha_creacion'
                  );
        });
    }

    /*
    |-----------------------------------------------------------
    | Le dice a Laravel que "email" pertenece a tabla "persona".
    | Sin esto Breeze busca usuario.email que no existe
    | y el login falla con "credentials do not match".
    |-----------------------------------------------------------
    */
    public function qualifyColumn($column): string
    {
        if ($column === 'email') {
            return 'persona.email';
        }
        return parent::qualifyColumn($column);
    }

    protected $fillable = [
        'id_persona',
        'password_hash',
        'rol',
        'activo',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    /*
    |-----------------------------------------------------------
    | Breeze espera "password" pero nuestra columna es
    | "password_hash". Estos métodos hacen el puente.
    |-----------------------------------------------------------
    */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function getPasswordAttribute(): string
    {
        return $this->password_hash;
    }

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password_hash'] = $value;
    }

    /*
    |-----------------------------------------------------------
    | Atributo calculado
    |-----------------------------------------------------------
    */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
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

    public function proyectosCreados()
    {
        return $this->hasMany(Proyecto::class, 'id_usuario_creador');
    }

    public function tareasCreadas()
    {
        return $this->hasMany(Tarea::class, 'id_usuario_creador');
    }

    public function tareasAsignadas()
    {
        return $this->belongsToMany(
            Tarea::class,
            'asignacion',
            'id_usuario',
            'id_tarea'
        )->withPivot('fecha_asignacion', 'fecha_entrega_estimada', 'observaciones');
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'id_usuario');
    }
}
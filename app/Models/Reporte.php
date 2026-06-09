<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $table      = 'reporte';
    protected $primaryKey = 'id_reporte';
    public    $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_proyecto',
        'id_cliente',
        'tipo',
        'fecha_generacion',
        'nombre_archivo',
        'ruta_archivo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_generacion' => 'datetime',
        ];
    }

    /*
    |-----------------------------------------------------------
    | Lógica de negocio
    |-----------------------------------------------------------
    */

    // Genera el nombre del archivo con tipo + timestamp
    public static function generarNombre(string $tipo): string
    {
        return "{$tipo}_" . now()->format('Ymd_His') . '.pdf';
    }

    // Verifica que el archivo físico siga existiendo en disco
    public function archivoExiste(): bool
    {
        return file_exists(public_path($this->ruta_archivo));
    }

    /*
    |-----------------------------------------------------------
    | Relaciones
    |-----------------------------------------------------------
    */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // nullable: un reporte de clientes no tiene proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }

    // nullable: un reporte de tareas puede no tener cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}

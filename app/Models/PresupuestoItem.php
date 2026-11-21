<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoItem extends Model
{
    protected $fillable = [
        'presupuesto_id', 'nombre', 'porcentaje',
        'monto_presupuestado', 'monto_real', 'completado', 'orden'
    ];

    protected $casts = ['completado' => 'boolean'];
}
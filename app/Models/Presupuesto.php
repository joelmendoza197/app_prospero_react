<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $fillable = [
        'user_id',
        'nombre', 
        'mes',
        'monto_asignado', 
        'sobrante_real'
    ];

    public function items()
    {
        return $this->hasMany(PresupuestoItem::class)->orderBy('orden');
    }
}
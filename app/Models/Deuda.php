<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    protected $fillable = [
        'user_id', 'nombre', 'valor_total', 'fecha_inicio',
        'meses', 'tipo', 'tasa_anual', 'metodo'
    ];

    protected $casts = [
        'valor_total' => 'float',
        'tasa_anual'  => 'float',
        'fecha_inicio' => 'date',
    ];

    public function pagos()
    {
        return $this->hasMany(DeudaPago::class);
    }
}
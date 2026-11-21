<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $fillable = ['user_id', 'monto', 'descripcion', 'recurrencia', 'fecha'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

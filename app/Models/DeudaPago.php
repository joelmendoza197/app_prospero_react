<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeudaPago extends Model
{
    protected $fillable = ['deuda_id', 'numero_cuota', 'monto_pagado', 'fecha_pago'];
}
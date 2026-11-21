<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeudaPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('deuda_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deuda_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('numero_cuota');
            $table->decimal('monto_pagado', 10, 2);
            $table->date('fecha_pago');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deuda_pagos');
    }
}

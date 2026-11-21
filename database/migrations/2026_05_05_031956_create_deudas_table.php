<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeudasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('deudas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->decimal('valor_total', 10, 2);
            $table->date('fecha_inicio');
            $table->unsignedInteger('meses');
            $table->enum('tipo', ['sin_interes', 'con_interes'])->default('sin_interes');
            $table->decimal('tasa_anual', 5, 2)->nullable(); // ej: 12.00 = 12%
            $table->enum('metodo', ['frances', 'aleman'])->nullable();
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
        Schema::dropIfExists('deudas');
    }
}

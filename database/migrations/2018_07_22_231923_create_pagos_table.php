<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('propietario_id');
            $table->unsignedInteger('usuario_que_genera_el_pago_id');
            $table->unsignedInteger('factura_id');
            $table->date('fecha');
            $table->float('monto');
            $table->string('estado');
            $table->string('medio_de_pago');
            $table->string('codigo_comprobante')->nullable();
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
        Schema::dropIfExists('pagos');
    }
}

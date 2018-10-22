<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeudasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deudas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('informe_id');
            $table->unsignedInteger('mes');
            $table->unsignedInteger('anio');
            $table->unsignedInteger('usuario_id');
            $table->float('total_factura');
            $table->float('pago_parcial');
            $table->float('adeuda');
            $table->date('vencimiento');
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

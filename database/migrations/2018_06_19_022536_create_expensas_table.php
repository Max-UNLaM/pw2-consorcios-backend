<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expensas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('unidad_id');
            //$table->unsignedInteger('factura_id');
            $table->string('anio');
            $table->string('mes');
            $table->string('pago');
            $table->date('emision');
            $table->date('vencimiento');
            $table->float('importe');
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
        Schema::table('expensas', function (Blueprint $table){
            $table->dropForeign(['unidad_id']);
            $table->dropForeign(['factura_id']);
        });
        Schema::dropIfExists('expensas');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsorciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consorcios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('direccion');
            $table->string('localidad');
            $table->string('provincia');
            $table->string('telefono');
            $table->string('email');
            $table->unsignedInteger('codigo_postal');
            $table->string('cuit');
            $table->unsignedInteger('cantidad_de_pisos');
            $table->unsignedInteger('departamentos_por_piso');
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
        Schema::table('unidads', function (Blueprint $table){
            $table->dropForeign(['consorcio_id']);
        });
        Schema::dropIfExists('consorcios');
    }
}

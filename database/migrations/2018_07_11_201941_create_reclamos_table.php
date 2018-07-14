<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReclamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reclamos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usuario_id');
            $table->unsignedInteger('unidad_id');
            $table->string('motivo');
            $table->date('fecha_reclamo');
            $table->date('fecha_resolucion');
            $table->string('conforme');
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
        Schema::table('reclamos', function (Blueprint $table){
            $table->dropForeign(['unidad_id']);
        });

        Schema::dropIfExists('reclamos');
    }
}

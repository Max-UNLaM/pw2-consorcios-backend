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
            $table->unsignedInteger('estado_de_reclamo_id');
            $table->string('motivo');
            $table->date('fecha_reclamo');
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
            $table->dropForeign(['usuario_id']);
            $table->dropForeign(['estado_de_reclamo_id']);
        });
        Schema::dropIfExists('reclamos');
    }
}

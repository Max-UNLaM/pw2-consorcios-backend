<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInformesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mes');
            $table->unsignedInteger('anio');
            $table->unsignedInteger('consorcio_id');
            $table->unsignedInteger('liquidacion_id');
            $table->json('liquidacion')->nullable();
            $table->json('gastos_del_periodo')->nullable();
            $table->json('pagos_del_periodo')->nullable();
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
        Schema::dropIfExists('informes');
    }
}

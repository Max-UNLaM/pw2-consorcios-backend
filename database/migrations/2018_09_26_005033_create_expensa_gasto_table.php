<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensaGastoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expensa_gasto', function (Blueprint $table) {
            $table->unsignedInteger('expensa_id')->nullable();
            $table->foreign('expensa_id')->references('id')->on('expensas')->onDelete('cascade');

            $table->unsignedInteger('gasto_id')->nullable();
            $table->foreign('gasto_id')->references('id')->on('gastos')->onDelete('cascade');

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
        Schema::dropIfExists('expensa_gasto');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tableros', function (Blueprint $table) {
            $table->id();
            $table->char('campo_1', 1)->default('-');
            $table->char('campo_2', 1)->default('-');;
            $table->char('campo_3', 1)->default('-');;
            $table->char('campo_4', 1)->default('-');;
            $table->char('campo_5', 1)->default('-');;
            $table->char('campo_6', 1)->default('-');;
            $table->char('campo_7', 1)->default('-');;
            $table->char('campo_8', 1)->default('-');;
            $table->char('campo_9', 1)->default('-');;
            $table->foreignId('partida_id');
            $table->foreign('partida_id')->references('id')->on('partidas');
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
        Schema::dropIfExists('tablero');
    }
};

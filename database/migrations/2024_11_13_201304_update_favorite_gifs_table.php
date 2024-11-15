<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('favorite_gifs', function (Blueprint $table) {
            // Cambiar el tipo de la columna gif_id a string
            $table->string('gif_id')->change();
        });
    }

    public function down()
    {
        Schema::table('favorite_gifs', function (Blueprint $table) {
            // Volver al tipo anterior (si era necesario)
            $table->integer('gif_id')->change();
        });
    }
};

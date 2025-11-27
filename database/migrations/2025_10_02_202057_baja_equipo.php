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
        Schema::create('equipo_bajas', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->string('name');
            $table->string('month');
            $table->string('year');
            $table->integer('bajas');
            $table->integer('bajas_moneda');
            $table->integer('robados');
            $table->integer('robados_moneda');
            $table->integer('extraviados');
            $table->integer('extraviados_moneda');
            $table->integer('asignados')->default(0);
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
        Schema::dropIfExists('equipo_bajas');
    }
};

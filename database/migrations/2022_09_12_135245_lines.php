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
        //
        Schema::create('lines', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles');

            $table->unsignedBigInteger('personal_id');
            $table->foreign('personal_id')->references('id')->on('personal')->onUpdate('cascade');
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onUpdate('cascade');
            $table->unsignedBigInteger('receptor_id')->nullable();
            $table->foreign('receptor_id')->references('id')->on('users')->nullable();
            $table->binary('firma')->nullable();
            $table->binary('firma_entrega')->nullable();
            $table->string('comentario')->nullable();
            $table->string('entregado')->nullable();

            $table->enum('status', ['Activo', 'Inactivo', 'Entregado', 'Pendiente', 'Recibido'])->default("Inactivo");
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
        //
    }
};

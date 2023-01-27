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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('article');
            $table->string('description')->nullable();
            $table->string('ns')->unique();
            $table->string('marca');
            $table->string('modelo');
            $table->integer('precio_inicial');
            $table->integer('precio_actual')->nullable()->default("0");
            $table->string('comentario1')->nullable();

            
            $table->unsignedBigInteger('category_id')->default(1);
            $table->foreign('category_id')->references('id')->on('categories');

            $table->enum('status', ['Disponible', 'Asignado', 'En Reparacion', 'Robado', 'Extraviado', 'Baja'])->default("Disponible");
            
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

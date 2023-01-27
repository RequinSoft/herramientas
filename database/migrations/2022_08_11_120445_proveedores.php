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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('proveedor');
            $table->string('contacto');
            $table->string('email')->unique();
            $table->string('direccion');
            $table->string('telefono');
            $table->enum('status', ['activo', 'inactivo'])->default("activo");
            
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

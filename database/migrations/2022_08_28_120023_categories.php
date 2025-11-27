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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category')->unique();
            $table->string('description')->nullable();
            $table->integer('depreciacion');

            $table->unsignedBigInteger('group_id')->default(1);
            $table->foreign('group_id')->references('id')->on('groups');
            
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

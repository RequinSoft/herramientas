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
        Schema::create('ldap', function (Blueprint $table) {
            $table->id();
            $table->string('ldap_server')->nullable();
            $table->integer('ldap_port')->nullable();
            $table->integer('ldap_version')->nullable();
            $table->string('ldap_domain')->nullable();
            $table->string('ldap_user')->nullable();
            $table->string('ldap_password')->nullable();
            $table->boolean('ldap_status')->default(0);
            $table->string('action_by')->nullable();
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

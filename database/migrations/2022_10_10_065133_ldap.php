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
            $table->string('ldap_server');
            $table->string('ldap_port');
            $table->integer('ldap_version');
            $table->string('ldap_domain');
            $table->string('ldap_user');
            $table->string('ldap_password');
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

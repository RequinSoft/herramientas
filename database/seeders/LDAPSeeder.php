<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ldap;

class LDAPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $ldap = new Ldap();
        $ldap->ldap_server = "default";
        $ldap->ldap_port = 389;
        $ldap->ldap_version = 1;
        $ldap->ldap_domain = "domain";
        $ldap->ldap_user = "user";
        $ldap->ldap_password = "pass";
        $ldap->action_by = "admin";
        $ldap->ldap_status = 1;
        $ldap->save();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ldap\User;

class Ldap extends Controller
{
    //
    public function ldap_search(){

        //$logged_user = auth()->user()->id;

        $ldapUser = User::all();

        return $ldapUser;
    }

    public function ldap(){

        return "LDAP";
    }
}

<?php

namespace App\Ldap;

use LdapRecord\Models\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Models\Concerns\CanAuthenticate;

class User extends Authenticatable
{
    /**
     * The object classes of the LDAP model.
     *
     * @var array
     */

    use CanAuthenticate;

    public static $objectClasses = [
        'user',
    ];

    protected $guiKey = 'uuid';
}

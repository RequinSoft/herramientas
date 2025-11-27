<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Grupo de Administración
        $role1 = new Role();
        $role1->role = "administrador";
        $role1->description = "Grupo de Admnistración";
        $role1->save();

        //Autorizacion por grupos
        $role2 = new Role();
        $role2->role = "coadmin";
        $role2->description = "Administracion por grupos";
        $role2->save();

        //Grupo de Recepcion de materiales
        $role3 = new Role();
        $role3->role = "receptor";
        $role3->description = "Grupo de recepcion del sistema";
        $role3->save();

        
    }
}

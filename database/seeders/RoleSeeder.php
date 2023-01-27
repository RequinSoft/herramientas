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
        ////Grupo de Administración
        $role1 = new Role();
        $role1->role = "administrador";
        $role1->description = "Grupo de Admnistración";
        $role1->save();

        //Grupo de Autorizacion
        $role2 = new Role();
        $role2->role = "autorizador";
        $role2->description = "Grupo de autorización del sistema";
        $role2->save();

        //Grupo de Usuarios
        $role3 = new Role();
        $role3->role = "usuario";
        $role3->description = "Grupo de captura del sistema";
        $role3->save();

         //Grupo de Validador
         $role4 = new Role();
         $role4->role = "validador";
         $role4->description = "Grupo de ´validación del sistema";
         $role4->save();

         //Grupo de Revisor
         $role5 = new Role();
         $role5->role = "revisor";
         $role5->description = "Grupo de revisión del sistema";
         $role5->save();

        
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Personal;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $personal1 = new Personal();
        $personal1->nombre = "Gerardo Gonzalez";
        $personal1->puesto = "Ayudante";
        $personal1->group_id = "3";
        $personal1->status = "1";
        $personal1->save();
        
        $personal1 = new Personal();
        $personal1->nombre = "Mauricio Perez";
        $personal1->puesto = "Oficial";
        $personal1->group_id = "3";
        $personal1->status = "1";
        $personal1->save();
        
        $personal1 = new Personal();
        $personal1->nombre = "Jose Cuevas";
        $personal1->puesto = "Junior";
        $personal1->group_id = "4";
        $personal1->status = "1";
        $personal1->save();
        
        $personal1 = new Personal();
        $personal1->nombre = "Juan Lopez";
        $personal1->puesto = "Junior";
        $personal1->group_id = "2";
        $personal1->status = "1";
        $personal1->save();
        
        $personal1 = new Personal();
        $personal1->nombre = "Oswaldo Sanchez";
        $personal1->puesto = "Senior";
        $personal1->group_id = "2";
        $personal1->status = "1";
        $personal1->save();
        
        $personal1 = new Personal();
        $personal1->nombre = "Roberto Maciel";
        $personal1->puesto = "Senior";
        $personal1->group_id = "4";
        $personal1->status = "1";
        $personal1->save();
    }
}

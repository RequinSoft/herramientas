<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $group = new Group();
        $group->group = "default";
        $group->description = "Grupo General";
        $group->status = "1";
        $group->save();
        
        $group1 = new Group();
        $group1->group = "Sistemas";
        $group1->description = "";
        $group1->status = "1";
        $group1->save();
        
        $group2 = new Group();
        $group2->group = "Mantenimiento";
        $group2->description = "";
        $group2->status = "1";
        $group2->save();
        
        $group3 = new Group();
        $group3->group = "Finanzas";
        $group3->description = "";
        $group3->status = "1";
        $group3->save();
    }
}

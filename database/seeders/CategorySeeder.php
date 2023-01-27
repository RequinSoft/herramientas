<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $category1 = new Category();
        $category1->category = "Default";
        $category1->description = "";
        $category1->depreciacion = 1825;
        $category1->group_id = "1";
        $category1->status = "activo";
        $category1->save();
        
        $category2 = new Category();
        $category2->category = "Radios";
        $category2->description = "";
        $category2->depreciacion = 1825;
        $category2->group_id = "1";
        $category2->status = "activo";
        $category2->save();
        
        $category3 = new Category();
        $category3->category = "Bombas";
        $category3->description = "";
        $category3->depreciacion = 1825;
        $category3->group_id = "1";
        $category3->status = "activo";
        $category3->save();
        
        $category4 = new Category();
        $category4->category = "Herramienta Menor";
        $category4->description = "";
        $category4->depreciacion = 1825;
        $category4->group_id = "1";
        $category4->status = "activo";
        $category4->save();
        
        $category5 = new Category();
        $category5->category = "Equipo de Computo";
        $category5->description = "";
        $category5->depreciacion = 1825;
        $category5->group_id = "1";
        $category5->status = "activo";
        $category5->save();
    }
}

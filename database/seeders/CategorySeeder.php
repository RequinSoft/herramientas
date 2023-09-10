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
    }
}

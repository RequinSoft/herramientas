<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //admin
        $user = new User();
        $user->name = "admin";
        $user->user = "admin";
        $user->role_id = "1";
        $user->group_id = "1";
        $user->auten = "1";
        $user->email = "";
        $user->password = "admin";
        $user->status = "1";
        $user->save();
    }
}

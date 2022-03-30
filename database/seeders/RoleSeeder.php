<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ['name' => 'Super Admin', 'color' => '#3408FF', 'icon' => "<i class='fa-solid fa-crown'></i>"],
            ['name' => 'Admin', 'color' => '#1487FF', 'icon' => "<i class='fa-solid fa-gavel'></i>"],
            ['name' => 'Agent', 'color' => '#DEB21A', 'icon' => "<i class='fa-solid fa-user-tie'></i>"],

        ]);
    }
}

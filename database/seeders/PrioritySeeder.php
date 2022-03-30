<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Priority::insert([
            ['name' => 'Urgent', 'color' => '#FF0300'],
            ['name' => 'High', 'color' => '#B5503A'],
            ['name' => 'Medium', 'color' => '#0AC3FF'],
            ['name' => 'Low', 'color' => '#0287BA'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Label::insert([
            ['name' => 'Story', 'color' => '#856EB8', 'icon' => '<i class="fa-solid fa-rectangle-list"></i>'],
            ['name' => 'Task', 'color' => '#0DB831', 'icon' => '<i class="fa-solid fa-list-check"></i>'],
            ['name' => 'Bug', 'color' => '#DE0000', 'icon' => '<i class="fa-solid fa-bug"></i>'],
            ['name' => 'Ticket', 'color' => '#DEB21A', 'icon' => '<i class="fa-solid fa-ticket"></i>'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::insert([
            ['name' => 'To do', 'color' => '#0052FF'],
            ['name' => 'In progress', 'color' => '#0CB9FF'],
            ['name' => 'Pending', 'color' => '#CF7A4C'],
            ['name' => 'Completed', 'color' => '#1DC553'],
            ['name' => 'Verified', 'color' => '#9A63C0'],
            ['name' => 'Canceled', 'color' => '#E1431F'],
        ]);
    }
}

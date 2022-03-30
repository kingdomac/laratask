<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Mohamad Cheaib', 'email' => 'superadmin@admin.com', 'password' => bcrypt('12341234'), 'role_id' => 1
        ]);
        User::create([
            'name' => 'Jhon Dao', 'email' => 'admin@admin.com', 'password' => bcrypt('12341234'), 'role_id' => 2
        ]);
        User::create([
            'name' => 'Stephanie', 'email' => 'agent@admin.com', 'password' => bcrypt('12341234'), 'role_id' => 3
        ]);

        User::factory()->count(20)->create();
        User::factory()->count(2)->create(['role_id' => 1]);
    }
}

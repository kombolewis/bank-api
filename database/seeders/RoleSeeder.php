<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            ['name' => 'admin', 'description' => 'this user can assign roles'],
            ['name' => 'viewer', 'description' => 'this user can view transfer history and retrieve balances for an account'],
            ['name' => 'creator', 'description' => 'this user can create new bank accounts and initiate transfers between accounts'],
        ]);
    }
}

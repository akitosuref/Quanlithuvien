<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Thủ Thư',
            'email' => 'librarian@mail.com',
            'password' => Hash::make('password'),
            'role' => 'librarian',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Thành Viên',
            'email' => 'member@mail.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

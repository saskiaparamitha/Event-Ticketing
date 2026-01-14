<?php

namespace Database\Seeders; //utk bikin data dummy

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hapus atau komen line factory bawaan jika tidak ingin user random
        // User::factory(10)->create();

        // Akun Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Akun User Biasa
        User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);
    }
}
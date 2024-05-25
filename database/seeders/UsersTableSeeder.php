<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Pegawai',
            'email' => 'pegawai@example.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai', // Atur peran sebagai 'pegawai'
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Atur peran sebagai 'admin'
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            "id" => Uuid::uuid4(),
            'first_name' => 'Irembo',
            'last_name' => 'Admin',
            'email' => 'admin@adminz.com',
            'password' => bcrypt('Admin@1234510'),
            'role' => "ADMIN",
            'created_at' => now(),
        ]);
    }
}

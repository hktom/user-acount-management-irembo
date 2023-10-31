<?php

namespace Database\Seeders;

use App\Models\User;
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
        $user = User::where('role', 'ADMIN')->first();
        
        if ($user) {
            return;
        }

        DB::table('users')->insert([
            "id" => Uuid::uuid4(),
            'first_name' => 'Irembo',
            'last_name' => 'Admin',
            'email' =>  env('ADMIN_MAIL', 'admin@adminz.com'),
            'password' => bcrypt('Admin@1234510'),
            'role' => "ADMIN",
            'created_at' => now(),
        ]);
    }
}

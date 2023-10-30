<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class NationalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Rwanda',
            'code' => 'RW',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Burundi',
            'code' => 'BI',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Uganda',
            'code' => 'UG',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Kenya',
            'code' => 'KE',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Tanzania',
            'code' => 'TZ',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'South Sudan',
            'code' => 'SS',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Democratic Republic of Congo',
            'code' => 'CD',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Ethiopia',
            'code' => 'ET',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Somalia',
            'code' => 'SO',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Eritrea',
            'code' => 'ER',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Djibouti',
            'code' => 'DJ',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Sudan',
            'code' => 'SD',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Egypt',
            'code' => 'EG',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Libya',
            'code' => 'LY',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Tunisia',
            'code' => 'TN',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Algeria',
            'code' => 'DZ',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Morocco',
            'code' => 'MA',
        ]);

        DB::table('nationalities')->insert([
            "id" => Uuid::uuid4(),
            'name' => 'Mauritania',
            'code' => 'MR',
        ]);
    }
}

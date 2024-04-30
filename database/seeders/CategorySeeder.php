<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            "id" => "RMA",
            "name" => "Real Madrid",
            "description" => "hala madrid y nada mas",
            "created_at" => "2024-04-24 00:00:00",
        ]);
        DB::table('categories')->insert([
            "id" => "BAR",
            "name" => "Bayern Munchen",
            "description" => "mia san mia mama mia lezatos",
            "created_at" => "2024-09-24 00:00:00",
        ]);
        DB::table('categories')->insert([
            "id" => "MUN",
            "name" => "Manchester United",
            "description" => "Glory Glory Man United",
            "created_at" => "2024-12-24 21:00:00",
        ]);
        DB::table('categories')->insert([
            "id" => "JUV",
            "name" => "Juventus",
            "description" => "Fino alla Fine",
            "created_at" => "2024-11-24 10:00:00",
        ]);
        DB::table('categories')->insert([
            "id" => "ACM",
            "name" => "AC Milan",
            "created_at" => "2024-11-24 10:00:00",
        ]);

    }
}

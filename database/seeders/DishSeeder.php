<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // database/seeders/DishSeeder.php
        DB::table('dishes')->insert([
            ['name' => 'Adobo', 'price' => 120],
            ['name' => 'Sinigang', 'price' => 150],
            ['name' => 'Kare-Kare', 'price' => 180],
            ['name' => 'Menudo', 'price' => 130],
        ]);

    }
}

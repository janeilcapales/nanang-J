<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Dish;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = User::where('role', 'customer')->first();
        $adobo = Dish::where('name', 'Adobo')->first();
        $sinigang = Dish::where('name', 'Sinigang')->first();

        Reservation::create([
            'user_id' => $customer->id,
            'table_number' => 'T1',
            'reservation_time' => now()->addDays(1)->setTime(18, 0),
            'dish_id' => $adobo->id,
        ]);

        Reservation::create([
            'user_id' => $customer->id,
            'table_number' => 'T2',
            'reservation_time' => now()->addDays(2)->setTime(19, 30),
            'dish_id' => $sinigang->id,
        ]);
    }
}

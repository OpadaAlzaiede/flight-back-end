<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    protected const Governorates = [
        1 => 'lattakia',
        2 => 'Aleppo',
        3 => 'Idlib',
        4 => 'Homs',
        5 => 'Tartus',
        6 => 'Damascus',
        7 => 'Daraa',
        8 => 'Der-ElZor',
        9 => 'Al-Hasakah',
        10 => 'Raqaa',
        11 => 'Sweidaa',
        12 => 'Hama',
        13 => 'Quneitra',
    ];
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(100)->create();
        Trip::factory()->count(100)->create();
        
        for($i = 0; $i < 100; $i++) {
            DB::table('trip_user')->insert([
                'user_id' => rand(1, 100),
                'trip_id' => rand(1, 100),
                'seat' => rand(1, 30),
                'date' => Carbon::now()
            ]);
        }

        DB::table('roles')->insert([
            'name' => 'PASSENGER',
        ]);
        DB::table('roles')->insert([
            'name' => 'DRIVER',
        ]);

        for($i = 1; $i <= count(self::Governorates); $i++) {
            DB::table('governorates')->insert([
                'name' => self::Governorates[$i]
            ]);
        }
    }
}

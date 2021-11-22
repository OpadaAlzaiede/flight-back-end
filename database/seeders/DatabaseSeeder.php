<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
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
    }
}

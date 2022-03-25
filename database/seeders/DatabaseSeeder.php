<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Trip;
use App\Models\User;
use App\Models\Comment;
use App\Models\Role;
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
        $passengerRole = Role::create(['name' => 'PASSENGER']);
        $driverRole = Role::create(['name' => 'DRIVER']);
        $adminRole = Role::create(['name' => 'ADMIN']);
       
        User::factory()->create([
            'email' => 'driver@example.com',
            'role_id' => $driverRole->id
        ]);
        User::factory()->create([
            'email' => 'passenger@example.com',
            'role_id' => $passengerRole->id
        ]);
        User::factory()->create([
            'email' => 'admin@example.com',
            'role_id' => $adminRole->id
        ]);

        for($i = 1; $i <= count(self::Governorates); $i++) {
            DB::table('governorates')->insert([
                'name' => self::Governorates[$i]
            ]);
        }
    }
}

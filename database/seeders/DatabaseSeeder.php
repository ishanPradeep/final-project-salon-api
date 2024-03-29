<?php

namespace Database\Seeders;

use App\Models\Setting\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserLevelSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(DaySeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(PlaceOfferSeeder::class);
        $this->call(SalonTypeSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(SettingSeeder::class);



//        \App\Models\User\User::factory(3)->create();
//
//        \App\Models\User\User::factory()->create([
//            'name' => 'Admin User',
//            'email' => 'admin@example.com',
//        ]);
    }
}

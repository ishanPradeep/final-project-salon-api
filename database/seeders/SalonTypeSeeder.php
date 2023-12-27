<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SalonTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('salon_types')->insert([
            ['title' => 'Any Type','subtitle'=>'Salon', 'icon'=> ''],
            ['title' => 'Barber','subtitle'=>'Shop', 'icon'=> ''],
            ['title' => 'Beauty','subtitle'=>'Salon', 'icon'=> ''],
            ['title' => 'Bridal','subtitle'=>'Salon', 'icon'=> ''],
        ]);
    }
}

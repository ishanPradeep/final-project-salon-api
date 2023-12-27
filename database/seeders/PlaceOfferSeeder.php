<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PlaceOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('place_offers')->insert([
            ['name' => 'Free parking', 'icon'=> ''],
            ['name' =>"Security cameras", 'icon'=> ''],
            ['name' => 'TV', 'icon'=> ''],
            ['name' => 'AC unit', 'icon'=> ''],
            ['name' => 'Fast wifi', 'icon'=> ''],
            ['name' => 'Smoke alarm', 'icon'=> '']
        ]);
    }
}

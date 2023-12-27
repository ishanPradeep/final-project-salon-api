<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            ['name' => 'Hair', 'salon_type_id'=>1, 'icon'=> ''],
            ['name' =>"Nail", 'salon_type_id'=>2, 'icon'=> ''],
            ['name' => 'Facial', 'salon_type_id'=>3, 'icon'=> ''],
            ['name' => 'Wedding', 'salon_type_id'=>4, 'icon'=> '']
        ]);
    }
}

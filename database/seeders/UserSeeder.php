<?php
namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'fname' => 'Super',
                'lname' => 'Admin',
                'address' => 'Super Admin address line 01',
                'latitude' => 6.910213,
                'longitude' => 79.9439023,
                'contact_no' => '012544545',
                'profile_photo' => null,
                'have_salon' => 0,
                'email' => 'superadmin@gmail.com',
                'email_verified_at'=>'2020-06-18 19:43:54.000000',
                'password' => Hash::make('super_admin'),
                'user_level_id' => \App\Models\User\UserLevel::where('scope','super_admin')->first()->id,
            ]

        ]);

        $user = User::where('email', 'superadmin@gmail.com')->first();
        $user->assignRole('super_admin');
        $user->save();
    }
}

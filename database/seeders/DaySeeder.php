<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Day\Day;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days = array( 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');

        for($i=0; $i < 7; $i++) {
            $day = new Day();
            $day->name = $days[$i];
            $day->save();
        }
    }
}

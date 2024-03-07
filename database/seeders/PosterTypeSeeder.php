<?php

namespace Database\Seeders;

use App\Models\PosterType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PosterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('poster_types')->delete();

        \DB::table('poster_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'recurring',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'non_recurring',
            ),
        ));        

    }
}

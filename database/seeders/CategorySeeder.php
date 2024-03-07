<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('categories')->delete();
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'General' 
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Arts' 
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Children' 
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Faith' 
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Environment' 
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Politics' 
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Health' 
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Music' 
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Sports' 
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Education' 
            )
        ));               
    }
}

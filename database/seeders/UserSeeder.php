<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('users')->delete();
        User::create([
            'id' => 1,
            'first_name' => 'Jesmond',
            'last_name' => 'Library',
            'email' => 'admin@jesmond.com',
            'phone_number' => 2371717271,
            'is_admin' => true,
            'is_super_admin' => true,
            'password' => Hash::make('adminjesmond@1'),
            'email_verified_at' => now()
        ]);
    }
}

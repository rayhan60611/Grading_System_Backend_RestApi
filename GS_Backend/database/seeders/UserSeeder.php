<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $facker =  Factory::create();
        foreach (range(1 ,5 ) as $index){
            User::create([
                'userid'=> $facker->unique()->randomNumber,
                'fname'=> $facker->firstName,
                'lname'=> $facker->lastName,
                'role'=> '1',
                'password' => Hash::make('123456')

            ]);
        }
    }
}

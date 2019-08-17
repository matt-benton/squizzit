<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // default user
        $user1 = new App\User;
        $user1->email = 'test@demo.com';
        $user1->password = Hash::make('Password1');
        $user1->api_token = Str::random(60);
        $user1->save();
    }
}

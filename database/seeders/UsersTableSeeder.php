<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'ياسر العنيس',
            'email' => 'yasser@gmail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}

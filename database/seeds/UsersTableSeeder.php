<?php

use App\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Thibault Six',
            'email' => 'thibsix@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('castor'),
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'Benjamin Jonard',
            'email' => 'benjamin.jonard@student.unamur.be',
            'email_verified_at' => now(),
            'password' => bcrypt('whocares404'),
            'remember_token' => Str::random(10),
        ]);
    }
}

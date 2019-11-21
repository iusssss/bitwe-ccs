<?php

use Illuminate\Database\Seeder;
use App\User;
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
        User::UpdateOrCreate(['id' => 1, 'name' => 'admin', 'password' => Hash::make('secret'), 'email' => 'admin@gmail.com', 'privilege' => 'system admin']);
    }
}

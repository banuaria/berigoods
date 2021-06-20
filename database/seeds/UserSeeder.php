<?php

use Illuminate\Database\Seeder;
use App\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['name' => 'admin','email' => 'berigoods.team@gmail.com','password' => bcrypt('rahasia'),'role' => 'admin'];
        User::insert($data);
    }
}

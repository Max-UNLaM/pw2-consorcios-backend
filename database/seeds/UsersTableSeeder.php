<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $maxi     = User::create([
            'name'     => 'Maximiliano De Pietro',
            'email'    => 'maximiliano.depietro@gmail.com',
            'password' => bcrypt('changeme')
        ]);
        $dani     = User::create([
            'name'     => 'Daniel Marconi',
            'email'    => 'marconidaniel@outlook.com',
            'password' => bcrypt('changeme')
        ]);
        $esteban  = User::create([
            'name'     => 'Esteban',
            'email'    => 'estebanmg_27@yahoo.com.ar',
            'password' => bcrypt('changeme')
        ]);
        $user     = User::create([
            'name'     => 'Miles Davis',
            'email'    => 'user@consorcio.com',
            'password' => bcrypt('changeme')
        ]);
        $operator = User::create([
            'name'     => 'Smooth Operator',
            'email'    => 'operator@consorcio.com',
            'password' => bcrypt('changeme')
        ]);
        $maxi->createToken('ConsorcioLoco', ['user', 'operator', 'admin']);
        $dani->createToken('ConsorcioLoco', ['user', 'operator', 'admin']);
        $esteban->createToken('ConsorcioLoco', ['user', 'operator', 'admin']);
        $user->createToken('ConsorcioLoco', ['user']);
        $operator->createToken('ConsorcioLoco', ['operator, user']);
    }
}

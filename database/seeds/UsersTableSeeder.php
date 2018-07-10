<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Rol;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rol = new Rol();
        $admin = $rol->getFirstByName('Administrador');
        $operator = $rol->getFirstByName('Operador');
        $maxi     = User::create([
            'name'     => 'Maximiliano De Pietro',
            'email'    => 'maximiliano.depietro@gmail.com',
            'password' => bcrypt('changeme'),
            'rol_id' => $admin->id
        ]);
        $dani     = User::create([
            'name'     => 'Daniel Marconi',
            'email'    => 'marconidaniel@outlook.com',
            'password' => bcrypt('changeme'),
            'rol_id' => $admin->id
        ]);
        $esteban  = User::create([
            'name'     => 'Esteban',
            'email'    => 'estebanmg_27@yahoo.com.ar',
            'password' => bcrypt('changeme'),
            'rol_id' => $admin->id
        ]);
        $user     = User::create([
            'name'     => 'Miles Davis',
            'email'    => 'user@consorcio.com',
            'password' => bcrypt('changeme'),
            'rol_id' => $operator->id
        ]);
        $operator = User::create([
            'name'     => 'Smooth Operator',
            'email'    => 'operator@consorcio.com',
            'password' => bcrypt('changeme'),
            'rol_id' => $operator->id
        ]);
        $maxi->withAccessToken($maxi->createToken('ConsorcioLoco', ['user', 'operator', 'admin']));
        $maxi->withAccessToken($dani->createToken('ConsorcioLoco', ['user', 'operator', 'admin']));
        $maxi->withAccessToken($esteban->createToken('ConsorcioLoco', ['user', 'operator', 'admin']));
        $maxi->withAccessToken($user->createToken('ConsorcioLoco', ['user']));
        $maxi->withAccessToken($operator->createToken('ConsorcioLoco', ['operator', 'user']));
    }
}

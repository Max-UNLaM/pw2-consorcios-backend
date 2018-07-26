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
        $faker = \Faker\Factory::create();
        $cantidadDeUsuariosACrear = 18;

        $nombres = array('Mateo',
                        'Daniel',
                        'Pablo',
                        'Alvaro',
                        'Adrian',
                        'David',
                        'Diego',
                        'Javier',
                        'Mario',
                        'Sergio',
                        'Marcos',
                        'Manuel',
                        'Martin',
                        'Nicolas',
                        'Jorge',
                        'Ivan',
                        'Carlos',
                        'Miguel',
                        'Lucas',
                        'Lucia',
                        'Maria',
                        'Paula',
                        'Daniela',
                        'Sara',
                        'Carla',
                        'Martina',
                        'Sofia',
                        'Julia',
                        'Alba');

        $apellidos = array('Perez',
                        'Gomez',
                        'Suarez',
                        'Gonzalez',
                        'Marconi',
                        'Estoyanoff',
                        'Diaz',
                        'Romero',
                        'Sosa',
                        'Torres',
                        'Benitez',
                        'Acosta',
                        'Flores',
                        'Medina',
                        'Ravenna',
                        'Ruiz',
                        'Villa',
                        'Gomez');

        $separadorCorreo = array('', '_');
        $extraCorreo= array('', '2010', '2011', '2012', '2013', '2014', '2015', 'ciudadela', 'ramosmejia');
        $tipoCorreo = array("@hotmail.com", '@gmail.com', '@yahoo.com.ar', '@fibertel.com');

        $rol = new Rol();
        $admin = $rol->getFirstByName('Administrador');
        $operator = $rol->getFirstByName('Operador');
        $usuario = $rol->getFirstByName('Usuario');

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

        for($i = 0; $i < $cantidadDeUsuariosACrear; $i++){
            $nombre = $faker->randomElement($nombres);
            $apellido = $faker->randomElement($apellidos);
            $separador = $faker->randomElement($separadorCorreo);
            $extra = $faker->randomElement($extraCorreo);
            $correo = $faker->randomElement($tipoCorreo);


            User::create([
               'name' => $nombre.' '.$apellido,
               'email' => strtolower($nombre).$separador.strtolower($apellido).$extra.$correo,
               'password' => bcrypt('changeme'),
               'rol_id' =>  $usuario->id
            ]);
        }
    }
}

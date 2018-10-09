<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'administra_consorcio', 'dni', 'estado'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getUserIdsByConsorcioId($consorcioId){
        $usuarios = DB::table('unidads')
            ->where('consorcio_id', $consorcioId)
            ->get(['usuario_id'])
            ->unique();

        foreach ($usuarios as $usuario){
            $respuesta[] = $usuario->usuario_id;
        }

        return $respuesta;

    }

    public function isAdmin(){
        return ($this->rol_id == 1) ? 1 : 0; //1 es el id del rol admin
    }

    public function isOperator(){
        return ($this->rol_id == 2) ? 1 : 0; //2 es el id del rol operator
    }

    public static function createRandomUser(){
        $faker = \Faker\Factory::create();
        $rol = new Rol();
        $usuario = $rol->getFirstByName('Usuario');

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

        $nombre = $faker->randomElement($nombres);
        $apellido = $faker->randomElement($apellidos);
        $separador = $faker->randomElement($separadorCorreo);
        $extra = $faker->randomElement($extraCorreo);
        $correo = $faker->randomElement($tipoCorreo);


        return User::create([
            'name' => $nombre.' '.$apellido,
            'email' => strtolower($nombre).$separador.strtolower($apellido).$extra.$correo,
            'password' => bcrypt('changeme'),
            'rol_id' =>  $usuario->id,
            'dni' => $faker->numberBetween(8000000, 38000000),
            'estado' => 'ACTIVO'
        ]);
    }
}

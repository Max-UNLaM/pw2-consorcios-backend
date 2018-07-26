<?php

use Illuminate\Database\Seeder;
use App\Proveedor;
use \Faker\Factory;

class ProveedorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $telefonosPrefijo = array('4653', '4656', '4657', '4488', '5237');
        $nombres = array('Mateo',
            'Daniel',
            'Pablo',
            'Álvaro',
            'Adrián',
            'David',
            'Diego',
            'Javier',
            'Mario',
            'Sergio',
            'Marcos',
            'Manuel',
            'Martín',
            'Nicolás',
            'Jorge',
            'Iván',
            'Carlos',
            'Miguel',
            'Lucas',
            'Lucía',
            'María',
            'Paula',
            'Daniela',
            'Sara',
            'Carla',
            'Martina',
            'Sofía',
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
            'Benítez',
            'Acosta',
            'Flores',
            'Medina',
            'Ravenna',
            'Ruíz',
            'Villa',
            'Gómez');

        $separadorCorreo = array('', '_');
        $extraCorreo= array('', '2010', '2011', '2012', '2013', '2014', '2015', 'ciudadela', 'ramosmejia');
        $tipoCorreo = array("@hotmail.com", '@gmail.com', '@yahoo.com.ar', '@fibertel.com');
        $rubros = array('Electricista', 'Construccion', 'Plomería', 'Gasista', 'Otros', 'Otros', 'Otros');

        foreach ($rubros as $rubro){
            for ($i = 0; $i < 10; $i++) {
                $nombre = $faker->randomElement($nombres);
                $apellido = $faker->randomElement($apellidos);
                $separador = $faker->randomElement($separadorCorreo);
                $extra = $faker->randomElement($extraCorreo);
                $correo = $faker->randomElement($tipoCorreo);
                $email = strtolower($nombre).$separador.strtolower($apellido).$extra.$correo;

                Proveedor::create([
                    'nombre'    => $nombre.' '.$apellido,
                    'tel' => $faker->randomElement($telefonosPrefijo).'-'.$faker->randomNumber(4, true),
                    'email' => $email,
                    'rubro' => $rubro
                ]);
            }
        }
    }
}

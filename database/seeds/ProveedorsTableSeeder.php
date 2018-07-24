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
        $nombres=array('Juan', 'Carlos', 'Roberto', 'Luis', 'Marcelo', 'Eduardo', 'Alejandro', 'Rodrigo');
        $apellidos = array('Perez', 'Gomez', 'Suarez', 'Gonzalez', 'Marconi', 'Estoyanoff');
        $separadorCorreo = array('', '_', '.');
        $extraCorreo= array('', '2010', '2011', '2012', '2013', '2014', '2015', 'ciudadela', 'ramosmejia');
        $tipoCorreo = array("@hotmail.com", '@gmail.com', '@yahoo.com.ar', '@fibertel.com');
        $rubros = array('Electricista', 'Limpieza', 'Construccion', 'Plomería');

        for ($i = 0; $i < 25; $i++) {
            $nombre = $faker->randomElement($nombres);
            $apellido = $faker->randomElement($apellidos);
            $separador = $faker->randomElement($separadorCorreo);
            $extra = $faker->randomElement($extraCorreo);
            $correo = $faker->randomElement($tipoCorreo);
            $email = strtolower($nombre).$separador.strtolower($apellido).$extra.$correo;
            $rubro = $faker->randomElement($rubros);

            Proveedor::create([
                'nombre'    => $nombre.' '.$apellido,
                'tel' => $faker->randomElement($telefonosPrefijo).'-'.$faker->randomNumber(4, true),
                'email' => $email,
                'rubro' => $rubro
            ]);
        }
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Consorcio;
use \Faker\Factory;

class ConsorciosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

            Consorcio::create([
                'nombre' => 'Torre de Mayo',
                'direccion' => 'Avenida de Mayo 665',
                'localidad' => 'Ramos Mejia', 
                'provincia' => 'Buenos Aires',
                'telefono' => '4656-8007',
                'email' => 'torre.de.mayo@gmail.com',
                'codigo_postal' => 1704,
                'cuit' => '20-'.$faker->randomNumber(8, true).'-'.$faker->randomDigitNotNull,
                'cantidad_de_pisos' => $faker->numberBetween(2, 4),
                'departamentos_por_piso' => $faker->numberBetween(2, 4)
            ]);

            Consorcio::create([
                'nombre' => 'Edificio Rivadavia',
                'direccion' => 'Rivadavia 2512',
                'localidad' => 'CABA',
                'provincia' => 'Buenos Aires',
                'telefono' => '4383-3225',
                'email' => 'edificiorivadavia@yahoo.com.ar',
                'codigo_postal' => 1827,
                'cuit' => '20-'.$faker->randomNumber(8, true).'-'.$faker->randomDigitNotNull,
                'cantidad_de_pisos' => $faker->numberBetween(2, 4),
                'departamentos_por_piso' => $faker->numberBetween(2, 4)
            ]);

            Consorcio::create([
                'nombre' => 'Gran Ciudadela',
                'direccion' => 'Venezuela 1545',
                'localidad' => 'Ciudadela',
                'provincia' => 'Buenos Aires',
                'telefono' => '4654-3264',
                'email' => 'gran_ciudadela@hotmail.com',
                'codigo_postal' => 1702,
                'cuit' => '20-'.$faker->randomNumber(8, true).'-'.$faker->randomDigitNotNull,
                'cantidad_de_pisos' => $faker->numberBetween(2, 4),
                'departamentos_por_piso' => $faker->numberBetween(2, 4)
            ]);

            Consorcio::create([
                'nombre' => 'Complejo 9 de julio',
                'direccion' => '9 de Julio 437',
                'localidad' => 'Morón',
                'provincia' => 'Buenos Aires',
                'telefono' => '4627-4200',
                'email' => 'complejo9dejulio@gmail.com',
                'codigo_postal' => 1755,
                'cuit' => '20-'.$faker->randomNumber(8, true).'-'.$faker->randomDigitNotNull,
                'cantidad_de_pisos' => $faker->numberBetween(2, 4),
                'departamentos_por_piso' => $faker->numberBetween(2, 4)
            ]);

            Consorcio::create([
                'nombre' => 'Altos de San Justo',
                'direccion' => 'Arieta 2345',
                'localidad' => 'San Justo',
                'provincia' => 'Buenos Aires',
                'telefono' => '4441-0265',
                'email' => 'altosdesanjusto@gmail.com',
                'codigo_postal' => 1754,
                'cuit' => '20-'.$faker->randomNumber(8, true).'-'.$faker->randomDigitNotNull,
                'cantidad_de_pisos' => $faker->numberBetween(2, 4),
                'departamentos_por_piso' => $faker->numberBetween(2, 4)
            ]);
    }
}

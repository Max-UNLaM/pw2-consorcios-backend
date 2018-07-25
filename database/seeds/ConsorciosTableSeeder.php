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
                'nombre' => 'Consorcio 1',
                'direccion' => 'Avenida de Mayo 665',
                'localidad' => 'Ramos Mejia', 
                'provincia' => 'Buenos Aires',
                'telefono' => '4656-8007',
                'email' => 'consorcio1@gmail.com',
                'codigo_postal' => 1704,
                'cuit' => '20-'.$faker->randomNumber(8, true).'-'.$faker->randomDigitNotNull,
                'cantidad_de_pisos' => $faker->numberBetween(2, 8),
                'departamentos_por_piso' => $faker->numberBetween(4, 8)
            ]);

            Consorcio::create([
                'nombre' => 'Consorcio 2',
                'direccion' => 'Rivadavia 2512',
                'localidad' => 'CABA',
                'provincia' => 'Buenos Aires',
                'telefono' => '4383-3225',
                'email' => 'consorcio2@gmail.com',
                'codigo_postal' => 1827,
                'cuit' => '20-'.$faker->randomNumber(8, true).'-'.$faker->randomDigitNotNull,
                'cantidad_de_pisos' => $faker->numberBetween(2, 8),
                'departamentos_por_piso' => $faker->numberBetween(4, 8)
            ]);

            Consorcio::create([
                'nombre' => 'Consorcio 3',
                'direccion' => 'Venezuela 1545',
                'localidad' => 'Ciudadela',
                'provincia' => 'Buenos Aires',
                'telefono' => '4654-3264',
                'email' => 'consorcio3@gmail.com',
                'codigo_postal' => 1702,
                'cuit' => '20-'.$faker->randomNumber(8, true).'-'.$faker->randomDigitNotNull,
                'cantidad_de_pisos' => $faker->numberBetween(2, 8),
                'departamentos_por_piso' => $faker->numberBetween(4, 8)
            ]);

            Consorcio::create([
                'nombre' => 'Consorcio 4',
                'direccion' => '9 de Julio 437',
                'localidad' => 'Morón',
                'provincia' => 'Buenos Aires',
                'telefono' => '4627-4200',
                'email' => 'consorcio4@gmail.com',
                'codigo_postal' => 1755,
                'cuit' => '20-'.$faker->randomNumber(8, true).'-'.$faker->randomDigitNotNull,
                'cantidad_de_pisos' => $faker->numberBetween(2, 8),
                'departamentos_por_piso' => $faker->numberBetween(4, 8)
            ]);

            Consorcio::create([
                'nombre' => 'Consorcio 5',
                'direccion' => 'Arieta 2345',
                'localidad' => 'San Justo',
                'provincia' => 'Buenos Aires',
                'telefono' => '4441-0265',
                'email' => 'consorcio5@gmail.com',
                'codigo_postal' => 1754,
                'cuit' => '20-'.$faker->randomNumber(8, true).'-'.$faker->randomDigitNotNull,
                'cantidad_de_pisos' => $faker->numberBetween(2, 8),
                'departamentos_por_piso' => $faker->numberBetween(4, 8)
            ]);
    }
}

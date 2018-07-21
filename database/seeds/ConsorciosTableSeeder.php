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

        $cantidadDeConsorcios = 5;

        for ($i = 0; $i < $cantidadDeConsorcios; $i++) {
            Consorcio::create([
                'nombre' => "Consorcio ".($i+1),
                'direccion' => $faker->streetAddress,
                'localidad' => $faker->randomElement(array('CABA', 'Ramos Mejia', 'Ciudadela', 'Morón', 'San Justo', 'Pilar')),
                'provincia' => 'Buenos Aires',
                'telefono' => $faker->phoneNumber,
                'email' => $faker->email,
                'codigo_postal' => $faker->numberBetween(1111, 1702),
                'cuit' => '20-'.$faker->randomNumber(8, true).'-'.$faker->randomDigitNotNull
            ]);
        }

    }
}

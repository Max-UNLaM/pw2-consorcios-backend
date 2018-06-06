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

        for ($i = 0; $i < 10; $i++) {
            Consorcio::create([
                'nombre' => "Consorcio_$i",
                'direccion' => $faker->streetAddress,
                'localidad' => "Localidad_$i",
                'provincia' => 'Buenos Aires',
                'telefono' => $faker->phoneNumber
            ]);
        }

        for ($i = 10; $i < 20; $i++) {
            Consorcio::create([
                'nombre' => "Consorcio_$i",
                'direccion' => $faker->streetAddress,
                'localidad' => "Localidad_$i",
                'provincia' => 'Córdoba',
                'telefono' => $faker->phoneNumber
            ]);
        }
    }
}

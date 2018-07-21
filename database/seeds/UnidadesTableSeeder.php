<?php

use Illuminate\Database\Seeder;
use \Faker\Factory;
use \App\Unidad;

class UnidadesTableSeeder extends Seeder
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
            Unidad::create([
                'nombre' => $faker->name,
                'direccion' => $faker->streetAddress,
                'localidad' => $faker->titleMale,
                'provincia' => 'Buenos Aires',
                'usuario_id' => $faker->numberBetween(1, 5),
                'consorcio_id' => $faker->numberBetween(1, 40)
            ]);
        }
        for ($i = 0; $i < 10; $i++) {
            Unidad::create([
                'nombre' => $faker->name,
                'direccion' => $faker->streetAddress,
                'localidad' => $faker->titleMale,
                'provincia' => 'Buenos Aires',
                'usuario_id' => $faker->numberBetween(1, 5),
                'consorcio_id' => $faker->numberBetween(1, 40)
            ]);
        }
        for ($i = 0; $i < 10; $i++) {
            Unidad::create([
                'nombre' => $faker->name,
                'direccion' => $faker->streetAddress,
                'localidad' => $faker->titleMale,
                'provincia' => 'Buenos Aires',
                'usuario_id' => $faker->numberBetween(1, 5),
                'consorcio_id' => $faker->numberBetween(1, 40)
            ]);
        }
        for ($i = 0; $i < 10; $i++) {
            Unidad::create([
                'nombre' => $faker->name,
                'direccion' => $faker->streetAddress,
                'localidad' => $faker->titleMale,
                'provincia' => 'Buenos Aires',
                'consorcio_id' => $faker->numberBetween(1, 40),
                'usuario_id' => $faker->numberBetween(1, 5)
            ]);
        }
    }
}

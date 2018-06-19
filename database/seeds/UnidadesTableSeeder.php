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
                'consorcio_id' => 1
            ]);
        }
        for ($i = 0; $i < 10; $i++) {
            Unidad::create([
                'nombre' => $faker->name,
                'direccion' => $faker->streetAddress,
                'localidad' => $faker->titleMale,
                'provincia' => 'Buenos Aires',
                'consorcio_id' => 2
            ]);
        }
        for ($i = 0; $i < 10; $i++) {
            Unidad::create([
                'nombre' => $faker->name,
                'direccion' => $faker->streetAddress,
                'localidad' => $faker->titleMale,
                'provincia' => 'Buenos Aires',
                'consorcio_id' => 3
            ]);
        }
        for ($i = 0; $i < 10; $i++) {
            Unidad::create([
                'nombre' => $faker->name,
                'direccion' => $faker->streetAddress,
                'localidad' => $faker->titleMale,
                'provincia' => 'Buenos Aires',
                'consorcio_id' => 4
            ]);
        }
    }
}

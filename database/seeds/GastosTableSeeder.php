<?php

use App\Gasto;
use Faker\Factory;
use Illuminate\Database\Seeder;

class GastosTableSeeder extends Seeder
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
            Gasto::create([
                'nombre' => $faker->name,
                'valor' => $faker->numberBetween(0,5000),
                'fecha' => "2018-05-10",
                'proveedor_id' => 1,
                'consorcio_id' => 1
            ]);
        }
        for ($i = 0; $i < 10; $i++) {
            Gasto::create([
                'nombre' => $faker->name,
                'valor' => $faker->numberBetween(0,5000),
                'fecha' => "2018-04-10",
                'proveedor_id' => 2,
                'consorcio_id' => 2
            ]);
        }for ($i = 0; $i < 10; $i++) {
        Gasto::create([
            'nombre' => $faker->name,
            'valor' => $faker->numberBetween(0,5000),
            'fecha' => "2018-02-10",
            'proveedor_id' => 3,
            'consorcio_id' => 3
        ]);
        }for ($i = 0; $i < 10; $i++) {
            Gasto::create([
                'nombre' => $faker->name,
                'valor' => $faker->numberBetween(0,5000),
                'fecha' => "2018-03-10",
                'proveedor_id' => 4,
                'consorcio_id' => 4
            ]);
        }
    }
}

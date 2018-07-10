<?php

use Illuminate\Database\Seeder;
use \Faker\Factory;

class ExpensasTableSeeder extends Seeder
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
            Expensa::create([
                'id' => "Consorcio_$i",
                'unidad' => "Consorcio_$i",
                'año' => $faker->year,
                'mes' => $faker->monthName,
                'estado' => $faker->randomElement(['pago', 'impago']),
                'emision' => $faker->date,
                'vencimiento' => $faker->date,
                'importe' => $faker->randomFloat,
            ]);
        }
    }
}

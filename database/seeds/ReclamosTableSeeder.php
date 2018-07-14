<?php

use Illuminate\Database\Seeder;
use \Faker\Factory;
use \app\Reclamo;

class ReclamosTableSeeder extends Seeder
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
            Reclamo::create([
                'motivo' => $faker->text,
                'fecha_reclamo' => $faker->date,
                'fecha_resolucion' => $faker->date,
                'conforme' => $faker->randomElement(['no', 'si'])
            ]);
        }
    }
}

<?php

use Illuminate\Database\Seeder;
use \Faker\Factory;
use \App\Reclamo;

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
                'usuario_id' => 1,
                'unidad_id' => 1,
                'estado_de_reclamo_id' => 4,
                'motivo' => $faker->name,
                'fecha_reclamo' => $faker->date(),
            ]);
        }
    }
}

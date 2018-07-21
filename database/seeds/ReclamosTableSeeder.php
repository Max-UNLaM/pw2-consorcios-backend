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
                'motivo' => $faker->randomElement(array('Cañería rota', 'Ruidos molestos', 'Malos vecinos', 'Vecino insoportable', 'No me dejan dormir', 'El administrador es un ladrón', 'Las expensas son caras', 'La pintura esta horrible', 'El portero es mal tipo', 'No anda la luz del pasillo', 'El ascensor no funciona')),
                'fecha_reclamo' => $faker->dateTimeThisYear,
            ]);
        }
    }
}

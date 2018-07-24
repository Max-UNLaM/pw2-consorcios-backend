<?php

use App\Consorcio;
use App\Unidad;
use App\User;
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
        $reclamosMinimosPorConsorcio = 20;
        $reclamosMaximosPorConsorcio = 80;

        $faker = Factory::create();
        $motivos = array(
            'Cañería rota',
            'Ruidos molestos',
            'Malos vecinos',
            'Vecino insoportable',
            'No me dejan dormir',
            'El administrador es un ladrón',
            'Las expensas son caras',
            'La pintura esta horrible',
            'El portero es mal tipo',
            'No anda la luz del pasillo',
            'El ascensor no funciona',
            'Vecino tiene un bebé que llora mucho',
            'Música fuerte en horas nocturnas',
            'No hay agua',
            'Falla eléctrica',
            'Las expensas no son transparentes',
            'Las áreas de uso común no están cuidadas'
            );

        $consorcios = Consorcio::all();

        foreach ($consorcios as $consorcio){

            $unidadesDelConsorcio = Unidad::getUnidadsIdByConsorcioId($consorcio->id);
            $usuariosDelConsorcio = User::getUserIdsByConsorcioId($consorcio->id);
            $cantidadDeReclamos = $faker->numberBetween($reclamosMinimosPorConsorcio, $reclamosMaximosPorConsorcio);

            for ($i = 0; $i < $cantidadDeReclamos; $i++) {
                Reclamo::create([
                    'usuario_id' => $faker->randomElement($usuariosDelConsorcio),
                    'unidad_id' => $faker->randomElement($unidadesDelConsorcio),
                    'estado_de_reclamo_id' => $faker->numberBetween(1,4),
                    'motivo' => $faker->randomElement($motivos),
                    'fecha_reclamo' => $faker->dateTimeThisYear,
                ]);
            }
        }

    }
}

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

        $cantidadDeUsuarios = sizeof(\App\User::all());
        $cantidadDeConsorcios = sizeof(\App\Consorcio::all());

        $cantidadMinimaDeUnidadesPorConsorcio = 5;
        $cantidadDeUnidadesQueSeVanACrearDeManeraAleatoria = 100;


        $acumuladorDeUnidades = 1;

        //Aca se crea la cantidad minima de unidades por consorcio
        for($j = 0; $j < $cantidadMinimaDeUnidadesPorConsorcio; $j++){
            for ($i = 1; $i <= $cantidadDeConsorcios; $i++) {
                Unidad::create([
                    'nombre' => 'Unidad '.$acumuladorDeUnidades,
                    'usuario_id' => $faker->numberBetween(1, $cantidadDeUsuarios/2),
                    'consorcio_id' => $i
                ]);
                $acumuladorDeUnidades++;
            }
        }

        //Creo mas unidades que se van a repartir entre los consorcios de manera aleatoria
        for ($i = 0; $i < $cantidadDeUnidadesQueSeVanACrearDeManeraAleatoria; $i++) {
            Unidad::create([
                'nombre' => 'Unidad '.$acumuladorDeUnidades,
                'usuario_id' => $faker->numberBetween(1, $cantidadDeUsuarios/2),
                'consorcio_id' => $faker->numberBetween(1, $cantidadDeConsorcios)
            ]);
            $acumuladorDeUnidades++;
        }
    }
}

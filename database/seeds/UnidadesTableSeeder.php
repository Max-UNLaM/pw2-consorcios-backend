<?php

use App\Consorcio;
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

        //Nota: el numero de cantidad de departamentos por piso del seeder de consorcios no puede superar la cantidad de elementos de este array
        $letrasDeUnidades = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');

        //Aca se crea la cantidad minima de unidades por consorcio
        for ($i = 1; $i <= $cantidadDeConsorcios; $i++){

            $consorcio = Consorcio::find($i);
            $cantidadDePisos = $consorcio->cantidad_de_pisos;
            $cantidadDeDepartamentosPorPiso = $consorcio->departamentos_por_piso;

            for($j = 0; $j < $cantidadDePisos; $j++){
                for($k = 0; $k < $cantidadDeDepartamentosPorPiso; $k++){
                    Unidad::create([
                        'nombre' => ($j==0) ? 'PB '.$letrasDeUnidades[$k] : $j.'° '.$letrasDeUnidades[$k],
                        'usuario_id' => $faker->numberBetween(1, $cantidadDeUsuarios),
                        'consorcio_id' => $i
                    ]);
                }
            }
        }

    }
}

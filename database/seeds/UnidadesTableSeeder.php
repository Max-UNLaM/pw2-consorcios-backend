<?php

use App\Consorcio;
use Illuminate\Database\Seeder;
use \Faker\Factory;
use \App\Unidad;
use \App\User;

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

        $cantidadDeUsuarios = sizeof(User::all());
        $consorcios = \App\Consorcio::all();
        $cantidadDeConsorcios = sizeof($consorcios);
        $cantidadDeUnidadesQueSeVanACrear = Consorcio::cantidadTotalDeUnidades();
        $cantidadDeUsuariosFaltante = $cantidadDeUnidadesQueSeVanACrear - $cantidadDeUsuarios;

        for($i = 0; $i < $cantidadDeUsuariosFaltante; $i++){
            User::createRandomUser();
        }

        $userIds=array();
        $users = User::all();
        foreach ($users as $user){
            $userIds[] = $user->id;
        }

        //Nota: el numero de cantidad de departamentos por piso del seeder de consorcios no puede superar la cantidad de elementos de este array
        $letrasDeUnidades = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');

        //Aca se crea la cantidad minima de unidades por consorcio
        for ($i = 1; $i <= $cantidadDeConsorcios; $i++){

            $consorcio = Consorcio::find($i);
            $cantidadDePisos = $consorcio->cantidad_de_pisos;
            $cantidadDeDepartamentosPorPiso = $consorcio->departamentos_por_piso;

            for($j = 0; $j < $cantidadDePisos; $j++){
                for($k = 0; $k < $cantidadDeDepartamentosPorPiso; $k++){
                    shuffle($userIds);
                    $usuarioActual = array_pop($userIds);

                    Unidad::create([
                        'nombre' => ($j==0) ? 'PB '.$letrasDeUnidades[$k] : $j.'° '.$letrasDeUnidades[$k],
                        'usuario_id' => $usuarioActual,
                        'consorcio_id' => $i
                    ]);
                }
            }
        }

    }
}

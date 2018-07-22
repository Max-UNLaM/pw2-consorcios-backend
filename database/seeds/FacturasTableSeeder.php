<?php

use App\Consorcio;
use App\Expensa;
use \App\Factura;
use App\Unidad;
use \Faker\Factory;
use Illuminate\Database\Seeder;

class FacturasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cantidadDeMeses = 7;
        $anio = '2018';
        $consorcios = Consorcio::all();

        foreach ($consorcios as $consorcio){
            $propietarios = Consorcio::obtenerPropietarios($consorcio->id);

            for($mes = 1; $mes <= $cantidadDeMeses; $mes++){
                foreach ($propietarios as $propietario){
                    $idUnidades = Unidad::obtenerIdDeUnidadesPorUsuarioYConsorcio($propietario->id, $consorcio->id);

                    Factura::create([
                        'consorcio_id' => $consorcio->id,
                        'usuario_id' => $propietario->id,
                        'mes' => $mes,
                        'anio' => $anio,
                        'emision' => "$anio-$mes-10",
                        'vencimiento' => "$anio-$mes-20",
                        'total' => Expensa::obtenerImporteMensualPorMesAnioUnidades($mes, $anio, $idUnidades)
                    ]);
                }
            }
        }
    }
}

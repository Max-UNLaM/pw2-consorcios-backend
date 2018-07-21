<?php

use App\EstadoDeReclamos;
use Illuminate\Database\Seeder;

class EstadoDeReclamosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        EstadoDeReclamos::create([
            'id' => '1',
            'detalle' => 'Resuelto'
        ]);

        EstadoDeReclamos::create([
            'id' => '2',
            'detalle' => 'No Resuelto'
        ]);

        EstadoDeReclamos::create([
            'id' => '3',
            'detalle' => 'Rechazado'
        ]);

        EstadoDeReclamos::create([
            'id' => '4',
            'detalle' => 'Esperando confirmacion'
        ]);
    }
}


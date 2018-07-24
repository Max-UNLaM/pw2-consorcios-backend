<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Factura;
use App\Unidad;
use Illuminate\Http\Request;

class EstadisticaController extends Controller
{
    public function index(Request $request){
        $consorcioId = $request->get('consorcio_id');

        if($consorcioId){
            return $this->estadisticasPorConsorcio($consorcioId);
        } else {
            $consorcios = Consorcio::all();

            foreach ($consorcios as $consorcio){
                $respuesta[] = $this->estadisticasPorConsorcio($consorcio->id);
            }

            return $respuesta;
        }
    }

    public function estadisticasPorConsorcio($consorcioId){
        $consorcio = Consorcio::find($consorcioId);
        $cantadadDeUnidades = sizeof(Unidad::obtenerUnidadesPorIdConsorcio($consorcioId));

        $facturasDelConsorcio = Factura::obtenerFacturasPorConsorcio($consorcioId);

        $facturasPagas = 0;
        $facturasConPagoParcial = 0;
        $facturasImpagas = 0;
        
        foreach ($facturasDelConsorcio as $factura){
            if($factura->adeuda == 0) $facturasPagas++;
            if($factura->pago_parcial == 0) $facturasImpagas++;
            if(($factura->adeuda != 0) && ($factura->pago_parcial != $factura->total)) $facturasConPagoParcial++;
        }

        return ([
            'consorcio_id' => $consorcioId,
            'consorcio_nombre' =>$consorcio->nombre,
            'cantidad_de_unidades' => $cantadadDeUnidades,
            'cantidad_de_facturas' => sizeof($facturasDelConsorcio),
            'facturas_pagas' => $facturasPagas,
            'facturas_con_pago_parcial' => $facturasConPagoParcial,
            'facturas_impagas' => $facturasImpagas,
            'reclamos_abiertos' => 160,
            'reclamos_cerrados' => 4
        ]);
    }
}

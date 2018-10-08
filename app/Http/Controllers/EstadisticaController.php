<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Factura;
use App\Reclamo;
use App\Unidad;
use Illuminate\Http\Request;

class EstadisticaController extends Controller
{
    public function index(Request $request){
        $consorcioId = $request->get('consorcio_id');
        $mes = $request->get('mes');
        $anio = $request->get('anio');

        if($consorcioId){
            return $this->estadisticasPorConsorcio($consorcioId, $mes, $anio);
        } else {
            $consorcios = Consorcio::all();

            foreach ($consorcios as $consorcio){
                $respuesta[] = $this->estadisticasPorConsorcio($consorcio->id, $mes, $anio);
            }

            return $respuesta;
        }
    }

    public function estadisticasPorConsorcio($consorcioId, $mes, $anio){
        if($mes && $anio){
            $facturasDelConsorcio = Factura::obtenerFacturasPorConsorcioMesAnio($consorcioId, $mes, $anio);
            $reclamosDelConsorcio = Reclamo::obtenerReclamosPorConsorcioMesAnio($consorcioId, $mes, $anio);
        } else {
            $facturasDelConsorcio = Factura::obtenerFacturasPorConsorcio($consorcioId);
            $reclamosDelConsorcio = Reclamo::obtenerReclamosPorConsorcio($consorcioId);
        }

        $consorcio = Consorcio::find($consorcioId);
        $cantadadDeUnidades = sizeof(Unidad::obtenerUnidadesPorIdConsorcio($consorcioId));

        $facturasPagas = 0;
        $facturasConPagoParcial = 0;
        $facturasImpagas = 0;
        
        foreach ($facturasDelConsorcio as $factura){
            if($factura->adeuda == 0) $facturasPagas++;
            if($factura->pago_parcial == 0) $facturasImpagas++;
            if(($factura->adeuda != 0) && ($factura->adeuda != $factura->total)) $facturasConPagoParcial++;
        }

        $reclamosResueltos = 0;
        $reclamosNoResueltos = 0;
        $reclamosRechazados = 0;
        $reclamosEsperandoRespuesta = 0;

        foreach ($reclamosDelConsorcio as $reclamo){
            if($reclamo->estado_de_reclamo_id == 1) $reclamosResueltos++;
            if($reclamo->estado_de_reclamo_id == 2) $reclamosNoResueltos++;
            if($reclamo->estado_de_reclamo_id == 3) $reclamosRechazados++;
            if($reclamo->estado_de_reclamo_id == 4) $reclamosEsperandoRespuesta++;
        }

        return ([
            'consorcio_id' => $consorcioId,
            'consorcio_nombre' =>$consorcio->nombre,
            'cantidad_de_unidades' => $cantadadDeUnidades,
            'cantidad_de_facturas' => sizeof($facturasDelConsorcio),
            'facturas_pagas' => $facturasPagas,
            'facturas_con_pago_parcial' => $facturasConPagoParcial,
            'facturas_impagas' => $facturasImpagas,
            'total_reclamos' => sizeof($reclamosDelConsorcio),
            'reclamos_resueltos' => $reclamosResueltos,
            'reclamos_no_resueltos' => $reclamosNoResueltos,
            'reclamos_rechazados' => $reclamosRechazados,
            'reclamos_esperando_respuesta' => $reclamosEsperandoRespuesta,
            "monto_adeudado" => $montoAdeudado
        ]);
    }  
}
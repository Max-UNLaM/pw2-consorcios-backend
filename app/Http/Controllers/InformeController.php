<?php

namespace App\Http\Controllers;

use App\Informe;
use Illuminate\Http\Request;

class InformeController extends Controller
{
    public function index(Request $request){
        $id = $request->get('id');
        if($id){
            $informe = Informe::find($id);
            Informe::agregarInformacion($informe);
            return $informe;
        }

        $size = $request->get('size') ? $request->get('size') : 5;
        $mes = $request->get('mes');
        $anio = $request->get('anio');
        $consorcioId = $request->get('consorcio_id');

        if($mes && $anio && $consorcioId){
            $informes = Informe::filterByMesAnioConsorcio($mes, $anio, $consorcioId)->get();
            foreach ($informes as $item){
                $informeId = $item->id;
            }
            $inf = Informe::find($informeId);
            Informe::agregarInformacion($inf);
            return $inf;
        } else {
            $informes = Informe::list()->paginate($size);

            foreach ($informes->items() as $informe){
                Informe::agregarInformacion($informe);
            }

            return $informes;
        }


    }
}

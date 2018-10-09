<?php

namespace App\Http\Controllers;

use App\Informe;
use Illuminate\Http\Request;

class InformeController extends Controller
{
    public function index(Request $request){
        $size = $request->get('size') ? $request->get('size') : 5;

        $informes = Informe::list()->paginate($size);

        foreach ($informes->items() as $informe){
            Informe::agregarInformacion($informe);
        }

        return $informes;
    }
}

<?php

namespace App\Http\Controllers;

use App\Expensa;
use App\Gasto;
use App\Pago;
use App\Unidad;
use App\User;

class TestController extends Controller
{
    public function index(){
        return Gasto::importeGastosMensualConsorcio("2018", 1, 1);
        return Unidad::calcularCoeficiente(1);
        $idUnidades = Unidad::getUnidadsIdByConsorcioId(1);

        return Expensa::obtenerImporteMensualPorMesAnioUnidades(01, 2018, $idUnidades);
    }

}

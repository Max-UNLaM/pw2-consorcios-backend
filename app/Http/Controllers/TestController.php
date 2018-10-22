<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Deuda;
use App\Expensa;
use App\Factura;
use App\Gasto;
use App\Http\Resources\LiquidacionCollection;
use App\Informe;
use App\Liquidacion;
use App\Pago;
use App\Unidad;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index(Request $request){
        $pago = Pago::find(1);

        $mes = (int) explode("-", $pago->fecha)[1];
        $anio = (int) explode("-", $pago->fecha)[0];

        return $anio;

    }
}

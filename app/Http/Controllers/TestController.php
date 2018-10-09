<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Expensa;
use App\Factura;
use App\Gasto;
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
        return Liquidacion::find(1)->toJson();
    }
}

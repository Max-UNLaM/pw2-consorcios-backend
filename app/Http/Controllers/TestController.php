<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Expensa;
use App\Factura;
use App\Gasto;
use App\Pago;
use App\Unidad;
use App\User;

class TestController extends Controller
{
    public function index(){
        return Factura::facturarPeriodo(1, 10, 2018);
    }
}

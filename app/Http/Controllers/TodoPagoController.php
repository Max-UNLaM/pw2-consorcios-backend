<?php

namespace App\Http\Controllers;

use App\Library\Services\TodoPago;
use Illuminate\Http\Request;

class TodoPagoController extends Controller
{
    public function firstStep(TodoPago $todoPagoLoco, Request $request) {
        $todoPagoLoco->firstStep($request->toArray());
        return response();
    }
}

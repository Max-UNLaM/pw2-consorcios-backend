<?php

namespace App\Http\Controllers;

use App\Factura;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
	public function index() {
		return Factura::all();
	}
}

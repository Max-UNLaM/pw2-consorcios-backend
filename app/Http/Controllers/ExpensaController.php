<?php

namespace App\Http\Controllers;

use App\Expensa;
use App\Gasto;
use App\Unidad;
use App\Consorcio;
use Illuminate\Http\Request;

class ExpensaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('page')) {
            return $this->paginate($request);
        } else if ($request->get('id')) {
            return $this->show($request->get('id'));
        } else {
            return Expensa::all();
        }
    }

    public function paginate(Request $request)
    {
        return Expensa::paginate($request->get('size'));
    }

    public function store(Request $request)
    {
        $gasto = new Gasto();

        if (Expensa::find($request->get('id')) != null) $this->delete($request);

        $expensaNueva = $request->all();
        $expensaNueva['importe'] = ($gasto->importeGastosMensual($expensaNueva['año'], $expensaNueva['mes']) * 1.2) * Unidad::calcularCoeficiente($expensaNueva['unidad_id']);

        Expensa::create($expensaNueva);

        return response([
            'expensa' => $expensaNueva
        ]);
    }

    public function show($id)
    {
        return Expensa::find($id);
    }

    public function delete(Request $request)
    {
        $resp = Expensa::destroy($request->get('id'));

        if ($resp) {
            return 'ID ' . $request->get('id') . ' deleted OK';
        } else {
            return 'ID ' . $request->get('id') . ' not found';
        }
    }
}
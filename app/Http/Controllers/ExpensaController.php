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
        } else if ($request->get('puerta')) {
            return "PATOVA";
        } else if ($request->get('id')) {
            return $this->show($request->get('id'));
        } else if ($request->get('unidad_id')) {
            return $this->listByUnidad($request->get('unidad_id'));
        } else {
            return Expensa::all();
        }
    }

    public function user(Request $request) {
        if ($request->get('page')) {
            return $this->userPaginate($request);
        } else if ($request->get('puerta')) {
            return "PATOVA";
        } else if ($request->get('id')) {
            return $this->show($request->get('id'));
        } else if ($request->get('unidad_id')) {
            return $this->listByUnidad($request->get('unidad_id'));
        } else {
            return Expensa::all();
        }
    }

    protected function userPaginate(Request $request) {
        return Expensa::paginate($request->get('size'))
            ->where('unidad_id', $request->get('unidad_id'));
    }

    protected function listByUnidad(int $unidadId)
    {
        return Expensa::listByUnidad($unidadId);
    }

    public function paginate(Request $request)
    {
        return Expensa::paginate($request->get('size'));
    }

    public function store(Request $request)
    {
        # if (Expensa::find($request->get('id')) != null) $this->delete($request);
        $expensaNueva = Expensa::crearExpensaConImporte($request->all());
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
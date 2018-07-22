<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Expensa;
use App\Gasto;
use Illuminate\Http\Request;
use App\Unidad;
use Illuminate\Support\Facades\Auth;

class UnidadController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('page')) {
            return $this->paginate($request);
        } else if ($request->get('id')) {
            return $this->show($request->get('id'));
        } else {
            return Unidad::all();
        }
    }

    public function user(Request $request)
    {
        if ($request->get('puerta')) {
            return "PATOVA";
        } elseif ($request->get('page')) {
            return $this->getAllUnidadesOfUserPaginada($request, Auth::user()->getAuthIdentifier())->get(['*']);
        } else {
            return $this->getAllUnidadesOfUser(Auth::user()->getAuthIdentifier())->get(['*']);
        }
    }

    protected function getAllUnidadesOfUserPaginada(Request $request, $userId)
    {
        $size = $request->get('size') ? $request->get('size') : 10;
        return $this->getAllUnidadesOfUser($userId)->paginate($size);
    }

    protected function getAllUnidadesOfUser($userId)
    {
        return Unidad::getIdAllUnidadOfUser($userId);
    }

    public function paginate(Request $request)
    {
        return Unidad::paginate($request->get('size'));
    }

    public function show($id)
    {
        return Unidad::find($id);
    }

    public function store(Request $request)
    {
        if (Unidad::find($request->get('id'))) $this->delete($request->get('id'));

        Unidad::create($request->all());

        return $request->all();
    }

    public function delete(Request $request)
    {
        return $this->deleteById($request->get('id'));
    }

    public function deleteById($id)
    {
        $expensas = Expensa::where('unidad_id', $id)->get();
        foreach ($expensas as $expensa) {
            Expensa::destroy($expensa->id);
        }

        $resp = Unidad::destroy($id);

        if ($resp) {
            return 'ID ' . $id . ' deleted OK';
        } else {
            return 'ID ' . $id . ' not found';
        }
    }

}
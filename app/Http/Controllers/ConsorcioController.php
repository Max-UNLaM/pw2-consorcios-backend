<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Unidad;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsorcioController extends Controller
{
	public function index(Request $request) {
	    $size = $request->get('size') ? $request->get('size') : 5;
	    $id = $request->get('id');

        $paginado = $request->get('sin_paginar') ? 0 : 1;
        if(!$paginado) return Consorcio::all();

	    if($id) return Consorcio::find($id);

	    return Consorcio::paginate($size);
	}

	public function user(Request $request) {
        $size = $request->get('size') ? $request->get('size') : 5;
        $id = $request->get('id');
        $user = User::find(Auth::user()->getAuthIdentifier());

        if($id) return Consorcio::find($id);
        if($user->isAdmin()) return Consorcio::paginate($size);
        return $this->getAllConsorciosofUserPaginada($request, Auth::user()->getAuthIdentifier());

    }

    protected function getAllConsorciosofUserPaginada(Request $request, $userId)
    {
        $size = $request->get('size') ? $request->get('size') : 10;
        return $this->filterAllConsorciosOfUser($userId)->paginate($size);
    }

    protected function getAllConsorciosOfUser($userId)
    {
        return $this->filterAllConsorciosOfUser($userId)->get(['*']);
    }

    protected function filterAllConsorciosOfUser($userId)
    {
        return Consorcio::getAllConsorciosOfUser($userId);
    }


    public function paginate(Request $request)
    {
        return Consorcio::paginate($request->get('size'));
    }

    public function show($id)
    {
        return Consorcio::find($id);
    }

    public function store(Request $request)
    {
        if(Consorcio::find($request->get('id'))) $this->delete($request);

        return Consorcio::create($request->all());
    }

    public function delete(Request $request){
        $unidadController = new UnidadController();

        $unidades = Unidad::where('consorcio_id', $request->get('id'))->get();

        foreach ($unidades as $unidad) {
            $unidadController->deleteById($unidad->id);
        }
	    $resp = Consorcio::destroy($request->get('id'));

        if($resp){
            return 'ID '.$request->get('id').' deleted OK';
        } else {
            return 'ID '.$request->get('id').' not found';
        }
    }

    public function update(Request $request)
    {
        //Busco el consorcio correspondiente
        $consorcio = Consorcio::find($request->get('id'));

        //Pregunto si encontro un consorcio con ese id
        if ($consorcio) {
            //Actualizo los atributos del consorcio encontrado
            $consorcio->nombre = $request->get('nombre');
            $consorcio->direccion = $request->get('direccion');
            $consorcio->localidad = $request->get('localidad');
            $consorcio->provincia = $request->get('provincia');
            $consorcio->telefono =  $request->get('telefono');
            $consorcio->email = $request->get('email');
            $consorcio->codigo_postal = $request->get('codigo_postal');
            $consorcio->cuit = $request->get('cuit');
            $consorcio->cantidad_de_pisos = $request->get('cantidad_de_pisos');
            $consorcio->departamentos_por_piso = $request->get('departamentos_por_piso');

            //Guardo los cambios
            $consorcio->save();

            return response([
                'consorcioActualizado' => $consorcio
            ]);
        } else {
            //Si no lo encuentra respondo un codigo 404 (not found)
            return response(['No se encontro el consorcio que se quiere actualizar'], 404);
        }
    }
}

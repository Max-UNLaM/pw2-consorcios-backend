<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consorcio extends Model
{
    protected $fillable = ['nombre', 'direccion', 'cuit', 'codigo_postal', 'localidad', 'provincia', 'telefono', 'email', 'cantidad_de_pisos', 'departamentos_por_piso'];

    public static function obtenerPropietarios($consorcio_id){
        $unidadesDelConsorcio = Unidad::obtenerPropietariosPorIdConsorcio($consorcio_id);

        return $unidadesDelConsorcio;


        $idDePropietarios = $unidadesDelConsorcio->get(['usuario_id']);//->unique();

        return $idDePropietarios;

        $propietarios = array();

        foreach($idDePropietarios as $idPropietario){
            $propietarios.push(User::find($idPropietario));
        }

        return $propietarios;
    }
}

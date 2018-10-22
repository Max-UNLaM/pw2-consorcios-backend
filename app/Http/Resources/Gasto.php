<?php

namespace App\Http\Resources;

use App\Consorcio;
use App\Proveedor;
use Illuminate\Http\Resources\Json\JsonResource;

class Gasto extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "nombre" => $this->nombre,
            "valor" => $this->valor,
            "mes" => $this->mes,
            "anio" => $this->anio,
            "fecha" => $this->fecha,
            "es_gasto_fijo" => $this->es_gasto_fijo,
            'consorcio' => Consorcio::find($this->consorcio_id),
            'proveedor' => Proveedor::find($this->proveedor_id)
        ];
    }
}

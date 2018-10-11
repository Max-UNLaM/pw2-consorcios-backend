<?php

namespace App\Http\Resources;

use App\Consorcio;
use App\Unidad;
use App\User;
use App\Gasto;
use Illuminate\Http\Resources\Json\JsonResource;

class Expensa extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $unidad = Unidad::find($this->unidad_id);
        $user = User::find($unidad->usuario_id);
        $consorcio = Consorcio::find($unidad->consorcio_id);

        return [
            "id" => $this->id,
            "mes" => $this->mes,
            "anio" => $this->anio,
            "emision" => $this->emision,
            "vencimiento" => $this->vencimiento,
            "importe" => $this->importe,
            "pago" => $this->pago,
            'unidad' => $unidad,
            'usuario' => $user,
            'consorcio' => $consorcio,
            'gastos_del_periodo' => Gasto::where('mes', $this->mes)
                                            ->where('anio', $this->anio)
                                            ->where('consorcio_id', $consorcio->id)
                                            ->get()
        ];
    }
}

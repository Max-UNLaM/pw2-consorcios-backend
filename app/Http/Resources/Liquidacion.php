<?php

namespace App\Http\Resources;

use App\Consorcio;
use App\Gasto;
use Illuminate\Http\Resources\Json\JsonResource;

class Liquidacion extends JsonResource
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
            'id' => $this->id,
            'mes' => $this->mes,
            'anio' => $this->anio,
            'valor_sin_coeficiente' => $this->valor_sin_coeficiente,
            'valor' => $this->valor,
            'consorcio' => Consorcio::find($this->consorcio_id),
            'gastos_del_periodo' => Gasto::where('mes', $this->mes)
                                            ->where('anio', $this->anio)
                                            ->where('consorcio_id', $this->consorcio_id)
                                            ->get()
        ];
    }
}

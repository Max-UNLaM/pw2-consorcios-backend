<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 24/07/18
 * Time: 18:15
 */

namespace App\Dto\Pago;


class ExpensaDto
{
    private $unidad = "";
    private $concepto = "";
    private $valorTotal = 0.0;
    private $participacion = "";
    private $valor = 0.0;

    public function __construct(array $expensa)
    {
        $this->setUnidad($expensa['unidad']);
        $this->setConcepto($expensa['concepto']);
        $this->setValorTotal($expensa['valor_total']);
        $this->setParticipacion($expensa['participacion']);
        $this->setValor($expensa['valor']);
    }

    /**
     * @return string
     */
    public function getUnidad(): string
    {
        return $this->unidad;
    }

    /**
     * @param string $unidad
     */
    public function setUnidad(string $unidad): void
    {
        $this->unidad = $unidad;
    }

    /**
     * @return string
     */
    public function getConcepto(): string
    {
        return $this->concepto;
    }

    /**
     * @param string $concepto
     */
    public function setConcepto(string $concepto): void
    {
        $this->concepto = $concepto;
    }

    /**
     * @return float
     */
    public function getValorTotal(): float
    {
        return $this->valorTotal;
    }

    /**
     * @param float $valorTotal
     */
    public function setValorTotal(float $valorTotal): void
    {
        $this->valorTotal = $valorTotal;
    }

    /**
     * @return string
     */
    public function getParticipacion(): string
    {
        return $this->participacion;
    }

    /**
     * @param string $participacion
     */
    public function setParticipacion(string $participacion): void
    {
        $this->participacion = $participacion;
    }

    /**
     * @return float
     */
    public function getValor(): float
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     */
    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }


}
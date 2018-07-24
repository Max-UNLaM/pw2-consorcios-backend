<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 24/07/18
 * Time: 17:00
 */

namespace App\Library\Services;

use TodoPago\Sdk;


class TodoPago
{
    private $connector;

    public function firstStep(array $expensas)
    {
        $httpHeader = [
            'Authorization' => env("TODOPAGO_AUTHORIZATION"),
            'user_agent' => 'PHPSoapClient'

        ];
        $this->connector = new Sdk($httpHeader, 'test');
    }
}
<?php

namespace PagoDigital;

use PagoDigital\Controllers\PaymentController;

class PagoDigital
{

    public $payment;
    public function __construct($commerceId, $commerceToken)
    {
        $this->payment = new PaymentController(
            $commerceId,
            $commerceToken
        );
    }
}

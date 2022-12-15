<?php

require __DIR__ . '/vendor/autoload.php';

use App\Controllers\PaymentController;

class PagoDigital
{

    public $payment;
    public function __construct($commerceToken, $commereId)
    {
        $this->payment = new PaymentController(
            $commereId,
            $commerceToken
        );
    }
}

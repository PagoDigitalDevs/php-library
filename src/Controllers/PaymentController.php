<?php

namespace PagoDigital\Controllers;

define('FRONTBASEURL', "https://pago.pagodigital.com.py");
define('BACKBASEURL', "https://backend.pagodigital.com.py");

use GuzzleHttp\Client;
use Blocktrail\CryptoJSAES\CryptoJSAES;


class PaymentController
{
    public  $commerceToken;
    public $commerceId;

    public function __construct($commerceId, $commerceToken, $reactNative = false)
    {
        $this->commerceId = $commerceId;
        $this->commerceToken = $commerceToken;
    }

    /**
     * @description Crea un pago a través de cualquier plataforma
     * @param number $amount Monto que se va a cobrar
     * @param string $description Descripción del cobro realizada por el comercio
     * @param string $email Correo electrónico de la persona que está pagando
     * @param string $payerIdentification Identificación de la persona que realiza el pago
     * @param string $payerName Nombre de la persona que realiza el pago
     * @param string $phone Número telefonico de la persona que está pagando
     * @param string $platform Plataforma en la que se va a realizar el pago
     * @param string $reference Referencia de pago creada por el comercio
     * @param string $currency Moneda en la que se va a cobrar (Así la moneda se coloque en USD, el precio amount debe ir en Gs.)
     * @param string $location Localización del pago (Opcional)
     * @returns array Link de redireccionamiento junto a su ID generado dentro de PagoDigital
     */
    public function paymentWithPlatform(
        $amount,
        $description,
        $email,
        $payerIdentification,
        $payerName,
        $phone,
        $platform,
        $reference,
        $currency = 'PYG',
        $location
    ) {
        try {
            $token = hash('sha256', $reference.strval($amount).$this->commerceToken);
            $client = new Client([
                'base_uri' => BACKBASEURL,
                'timeout'  => 5.0,
            ]);
            $information = [
                'phone' => $phone,
                'amount' => $amount,
                'platform' => $platform,
                'email' => $email,
                'commerceId'=> $this->commerceId,
                'description' => $description,
                'token' => $token,
                'transactionId' => $reference,
                'payerName' => $payerName,
                'payerIdentification' => $payerIdentification,
                'location' => $location,
                'currency' => $currency,
            ];
            $res = $client->request('POST', "/transaction", ['json' => $information]);
            if ($res->getStatusCode() == '200') {
                $json =(string) $res->getBody();
                $json = json_decode($json, true);
                return print_r($json['data'], true);
            }
            return $res;
        } catch (\Exception $e) {
            echo 'Error',  $e->getMessage(), "\n";
        }
    }

    /**
     * @description Realiza un pago a través del link de pago de PagoDigital
     * @param number $amount Monto que se va a cobrar
     * @param string $reference Referencia de pago creada por el comercio
     * @param string $description Descripción del cobro realizada por el comercio
     * @param string $currency Moneda en la que se va a cobrar (Así la moneda se coloque en USD, el precio amount debe ir en Gs.)
     * @param string $suscriptionInterval Intervalo de suscripción (enviar solo si desea que el producto cuente con suscripción)
     * @param number $productId ID del producto de la suscripción (Opcional: Solo válido para suscripción)
     * @returns array Link de pago junto a su ID generado dentro de PagoDigital
     */
    public  function paymentWithLink(
        $amount,
        $reference,
        $description,
        $currency = 'PYG',
        $suscriptionInterval = null,
        $productId = null
    ) {
        try {
            $merchantTransactionId = strval(round(microtime(true) * 1000));
            $baseLink = FRONTBASEURL . "/link";
            $dataForEncode = [
                'amount' => $amount,
                'commerceId' => $this->commerceId,
                'description' => $description,
                'reference' => $reference,
                'commerceToken' => $this->commerceToken,
                'merchantTransactionId' => $merchantTransactionId,
                'currency' => $currency,
                'suscriptionInterval' => $suscriptionInterval,
                'productId' => $productId,
            ];
            $text = json_encode($dataForEncode);
            $key = $this->commerceToken;
            $encrypted = CryptoJSAES::encrypt($text, $key);
            $ciphertext = base64_encode($encrypted);
            $data64 = base64_encode($ciphertext."|". $this->commerceId);
            $link = $baseLink.'/'.$data64;
            $res=[
                'redirectUrl' => $link,
                'transactionId' => $merchantTransactionId
            ];
            return print_r($res, true);
        } catch (\Exception $e) {
            echo 'Error',  $e->getMessage(), "\n";
        }
    }
}

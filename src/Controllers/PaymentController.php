<?php

namespace PagoDigital\Controllers;

define('FRONTBASEURL',  "https://pago.pagodigital.com.py");
define('BACKBASEURL',   "https://backend.pagodigital.com.py");

use GuzzleHttp\Client;
use Defuse\Crypto\Crypto;
use Base64Url\Base64Url;

class PaymentController
{
    public  $commerceToken;
    public $commerceId;

    public function __construct($commerceId,$commerceToken, $reactNative = false)
    {
        $this->commerceId = $commerceId;
        $this->commerceToken = $commerceToken;
       
    }

    /**
     * @description Crea un pago a través de cualquier plataforma
     * @param {IPaymentPlatform} payment Párametros de pago con una plataforma
     * @param {number} payment.amount Monto que se va a cobrar
     * @param {string} payment.reference Referencia de pago creada por el comercio
     * @param {string} payment.description Descripción del cobro realizada por el comercio
     * @param {string} payment.phone Número telefonico de la persona que está pagando
     * @param {TPlatform} payment.platform Plataforma en la que se va a realizar el pago
     * @param {string} payment.email Correo electrónico de la persona que está pagando
     * @param {string} payment.payerName Nombre de la persona que realiza el pago
     * @param {string} payment.payerIdentification Identificación de la persona que realiza el pago
     * @param {ECurrency} payment.currency Moneda en la que se va a cobrar (Así la moneda se coloque en USD, el precio amount debe ir en Gs.)
     * @param {string} payment.location Localización del pago (Opcional)
     * @returns {Promise<IPaymentLinkResponse>} Link de redireccionamiento junto a su ID generado dentro de PAgoDigital
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
        $location,
        $currency = 'PYG'
    ) {
        try {
            echo 'transactionId:'.$reference.'monto:'.$amount.'commerceToken:'.$this->commerceToken.'\n';
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
            echo print_r($information);
            $res = $client->request('POST', "/transaction", ['json' => $information]);
            if ($res->getStatusCode() == '200') {
                $json =(string) $res->getBody();
                echo $json;
                return $json;
            }
            return $res;
        } catch (\Exception $e) {
            echo 'Error',  $e->getMessage(), "\n";
        }
    }

    /**
     * @description Realiza un pago a través del link de pago de PagoDigital
     * @param {IPaymentLink} payment Datos de la transacción de pago
     * @param {number} payment.amount Monto que se va a cobrar
     * @param {string} payment.reference Referencia de pago creada por el comercio
     * @param {string} payment.description Descripción del cobro realizada por el comercio
     * @param {ECurrency} payment.currency Moneda en la que se va a cobrar (Así la moneda se coloque en USD, el precio amount debe ir en Gs.)
     * @returns {Promise<string>} Link de pago
     */
    public  function paymentWithLink(
        $amount,
        $reference,
        $description,
        $currency = 'PYG'
    ) {
        try {
            $merchantTransactionId =  strval(strtotime("now"));
            $baseLink = FRONTBASEURL . "/link";
            $dataForEncode = [
                'amount' => $amount,
                'commerceId' => $this->commerceId,
                'description' => $description,
                'reference' => $reference,
                'commerceToken' => $this->commerceToken,
                'merchantTransactionId' => $merchantTransactionId,
                'currency' => $currency,
            ];
            $dataEncode = Crypto::encrypt($dataForEncode, $this->commerceToken);
            $data64 = Base64Url::encode($dataEncode . "|" . $this->commerceId);
            $link = $baseLink . '/' . $data64;
            return [
                'link' => $link,
                'mechantTransactionId' => $merchantTransactionId
            ];
        } catch (\Exception $e) {
            echo 'Error',  $e->getMessage(), "\n";
        }
    }
}

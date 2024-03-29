# PagoDigital

PagoDigital es la mejor solución para todos los comercios de implementar todos los medios de pagos.

## Instalación

Para instalar la librería de PagoDigital se puede hacer con composer:

```bash
composer require pagodigital/php-library
```

Para evitar conflictos, en el archivo composer.json, establece:

```bash
"minimum-stability": "dev"
```

## Como usar

Para comenzar a utilizar es necesario estar registrado en
[PagoDigital](https://www.pagodigital.com.py) y tener uno o mas comercios habilitados y verificados.

Primeramente se debe importar y crear una instancia de PagoDigital

```php
use PagoDigital\PagoDigital;
...

$pagoDigital = new PagoDigital(
    $commerceId = 120,
    $token = "791a736e949d4ec57af5684679bea5d5a9f485c5"
);
```

Luego de instanciar PagoDigital vas a poder utilizar todas las funciones dentro de la librería

| Parámetro  |  Tipo  |                    Descripción                     |
| :--------: | :----: | :------------------------------------------------: |
| commerceId | number |   ID del comercio que va a utilizar la librería    |
|   token    | string | Token público del comercio que utiliza la librería |

### Realizar un pago con plataforma especifica

Vas a poder realizar un pago a través de todas nuestras plataformas registradas.

```php
use PagoDigital\PagoDigital;
...

$pagoDigital = new PagoDigital(
    $commerceId = 120,
    $token = "791a736e949d4ec57af5684679bea5d5a9f485c5"
);

$paymentResponse = $pagoDigital->payment->paymentWithPlatform(
    $amount = 12500,
    $description = "Pago de prueba con la librería",
    $email = "example@gmail.com",
    $payerIdentification = "485987",
    $payerName = "Andrés López",
    $phone = "0984856321",
    $platform = "tigo",
    $reference = "pago-22",
    $location = "-27.55486, -2744157"
)

```

#### Parámetros

|      Parámetro      |  Tipo  |                           Descripción                           |
| :-----------------: | :----: | :-------------------------------------------------------------: |
|       amount        | number |                    Monto que se va a cobrar                     |
|     description     | string |                      Descripción del pago                       |
|        email        | string |              Correo de la persona que esta pagando              |
| payerIdentification | string |        Identificación del usuario que está pagando (C.I)        |
|      payerName      | string |               Nombre del usuario que esta pagando               |
|        phone        | string |              Teléfono del usuario que está pagando              |
|      platform       | string |               Plataforma con la que se va a pagar               |
|      reference      | string | Referencia de pago, generalmente es el ID del pago del comercio |
|      location       | string | Coordenadas indicando desde donde se realizó el pago (opcional) |

El parámetro `platform` es un enum que solo admite los siguientes datos:

|  Plataforma  |     Valor      |
| :----------: | :------------: |
|     Tigo     |     "tigo"     |
|   Personal   |   "personal"   |
|    Wally     |    "wally"     |
|   Bancard    |   "bancard"    |
|    Zimple    |    "zimple"    |
|   InfoNET    |   "infonet"    |
|  Aqui Pago   |  "aqui pago"   |
| Pago Express | "pago express" |
|    PayPal    |    "paypal"    |
|     Wepa     |     "wepa"     |
|  Bancard QR  |  "bancard qr"  |
|    Stripe    |    "stripe"    |

En caso contrario de no enviar uno de esos párametros en el platform, va a devolver error.

#### Respuesta

|   Parámetro   |  Tipo  |                                     Descripción                                      |
| :-----------: | :----: | :----------------------------------------------------------------------------------: |
|  redirectUrl  | string | Url de redireccionamiento a donde se va a redirigir al cliente para proceder al pago |
| transactionId | string |                    ID de la transacción generado por PagoDigital                     |

### Realizar pago por link

Este proceso es similar al anterior, solo que en vez de generar para una plataforma especifica,
puedes redirigir directamente a todas nuestras plataformas dentro de una página especial de
PagoDigital y dejar que nosostros nos encarguemos del resto.

```php
use PagoDigital\PagoDigital;
...

$pagoDigital = new PagoDigital(
    $commerceId = 120,
    $token = "791a736e949d4ec57af5684679bea5d5a9f485c5"
);

$paymentResponse = $pagoDigital->payment->paymentWithLink(
    $amount = 12500,
    $description = "Pago de prueba con la librería",
    $reference = "pago-22",
    $currency = "PYG",
    $subscriptionInterval = "monthly",
)
```

#### Parámetros

|      Párametro       |  Tipo  |                                           Descripción                                            |
| :------------------: | :----: | :----------------------------------------------------------------------------------------------: |
|        amount        | number |                                     Monto que se va a cobrar                                     |
|     description      | string |                                       Descripción del pago                                       |
|      reference       | string |                 Referencia de pago, generalmente es el ID del pago del comercio                  |
|       currency       | string |     Moneda en la que se va a realizar el pago (USD o PYG)(Opcional: Por defecto está en PYG)     |
| subscriptionInterval | string | Intervalo de suscripción, enviar solamente cuando quieras dar la opción de suscribirse a un pago |
|      productId       | string | ID del producto de la suscripción, enviar solamente cuando quieras dar la opción de suscripción  |

#### Respuesta

|   Parámetro   |  Tipo  |                                     Descripción                                      |
| :-----------: | :----: | :----------------------------------------------------------------------------------: |
|  redirectUrl  | string | Url de redireccionamiento a donde se va a redirigir al cliente para proceder al pago |
| transactionId | string |                    ID de la transacción generado por PagoDigital                     |

## Respuesta

La respuesta del pago será enviada a la URL de callback especificada en el panel de PagoDigital en
el apartado de editar comercio -> desarrollo.

La respuesta será enviada en formato JSON y contiene los siguientes elementos:

|           key           |  tipo  |                                                                                  descripción                                                                                  |
| :---------------------: | :----: | :---------------------------------------------------------------------------------------------------------------------------------------------------------------------------: |
|          token          | string | Token generado por seguridad, es recomendable utilizarlo para validar los pagos. Se conforma de la siguiente manera: SHA256(merchant_transaction_id + amount + private_token) |
| merchant_transaction_id | string |                                                             Identificador de la transacción dentro de PAgoDigital                                                             |
| commerce_transaction_id | string |                                                      Identificador o referencia de la transacción creada por el comercio                                                      |
|       payer_email       | string |                                                               Correo electrónico de la persona que esta pagando                                                               |
|  payer_identification   | string |                                                                identificación de la paersona que esta pagando                                                                 |
|       payer_name        | string |                                                                     Nombre de la persona que esta pagando                                                                     |
|          phone          | string |                                                               Número telefónico de la persona que esta pagando                                                                |
|        platform         | string |                                                                     Plataforma con la que se esta pagando                                                                     |
|         amount          | number |                                                                            Monto de la transacción                                                                            |
|           fee           | number |                                                                  Comisión de la transacción por PagoDigital                                                                   |
|       accredited        | number |                                                         Monto que se le acreditó al comercio descontando la comisión                                                          |
|       description       | string |                                                                             Descripción del pago                                                                              |
|         status          | string |                                                                 Estado del pago (APPROVED, REFUSED, PENDING)                                                                  |

## License

[MIT](https://choosealicense.com/licenses/mit/)

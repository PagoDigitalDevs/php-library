# PagoDigital

PagoDigital es la mejor solución para todos los comercios de implementar todos los medios de pagos.

## Instalación

Para instalar la librería de PagoDigital se puede hacer con composer:

```bash
composer require pago-digital/php-library
```

## Como usar

Para comenzar a utilizar es necesario estar registrado en
[PagoDigital](https://www.pagodigital.com.py) y tener uno o mas comercios habilitados y verificados.

Primeramente se debe importar y crear una instancia de PagoDigital

```php
use pago-digital/php-library;
...

$pagoDigital = new PagoDigital(
    commerceId: 120,
    token: '791a736e949d4ec57af5684679bea5d5a9f485c5'
);
```

Luego de instanciar PagoDigital vas a poder utilizar todas las funciones dentro de la librería

| Párametro  |  Tipo  |                    Descripción                     |
| :--------: | :----: | :------------------------------------------------: |
| commerceId | number |   ID del comercio que va a utilizar la librería    |
|   token    | string | Token público del comercio que utiliza la librería |

### Realizar un pago con plataforma especifica

Vas a poder realizar un pago a través de todas nuestras plataformas registradas.

```php
use PagoDigital/PagoDigital;
...
$pagoDigital = new PagoDigital(
    commerceId: 120,
    token: '791a736e949d4ec57af5684679bea5d5a9f485c5'
);

pagoDigital->payment->paymentWithPlatform(
    $amount= 12500,
    $description= "Pago de prueba con la librería",
    $email= "example@gmail.com",
    $payerIdentification= "485987",
    $payerName= "Adolf Hitler",
    $phone= "0984856321",
    $platform= "tigo",
    $reference= "pago-22",
    $location= "-27.55486,-2744157"
)

```

#### Párametros

|      Párametro      |  Tipo  |                           Descripción                           |
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

El párametro `platform` es un enum que solo admite los siguientes datos:

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

|   Párametro   |  Tipo  |                                     Descripción                                      |
| :-----------: | :----: | :----------------------------------------------------------------------------------: |
|  redirectUrl  | string | Url de redireccionamiento a donde se va a redirigir al cliente para proceder al pago |
| transactionId | string |                    ID de la transacción generado por PagoDigital                     |

### Realizar pago por link

Este proceso es similar al anterior, solo que en vez de generar para una plataforma especifica,
puedes redirigir directamente a todas nuestras plataformas dentro de una página especial de
PagoDigital y dejar que nosostros nos encarguemos del resto.

```php
use PagoDigital/PagoDigital;
...
$pagoDigital = new PagoDigital(
    commerceId: 120,
    token: '791a736e949d4ec57af5684679bea5d5a9f485c5'
);


pagoDigital->payment->paymentWithPlatform(
    $amount= 12500,
    $description= "Pago de prueba con la librería",
    $reference= "pago-22",
)
```

#### Párametros

|  Párametro  |  Tipo  |                                       Descripción                                        |
| :---------: | :----: | :--------------------------------------------------------------------------------------: |
|   amount    | number |                                 Monto que se va a cobrar                                 |
| description | string |                                   Descripción del pago                                   |
|  reference  | string |             Referencia de pago, generalmente es el ID del pago del comercio              |
|  currency   | string | Moneda en la que se va a realizar el pago (USD o PYG)(Opcional: Por defecto está en PYG) |

#### Respuesta

|   Párametro   |  Tipo  |                                     Descripción                                      |
| :-----------: | :----: | :----------------------------------------------------------------------------------: |
|  redirectUrl  | string | Url de redireccionamiento a donde se va a redirigir al cliente para proceder al pago |
| transactionId | string |                    ID de la transacción generado por PagoDigital                     |

## License

[MIT](https://choosealicense.com/licenses/mit/)

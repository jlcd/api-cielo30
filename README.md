# Client para API Cielo 3.0

## Instalação

Via composer: `composer require jlcd/api-cielo3.0`

Caso esteja utilizando Laravel (5+), sugiro trabalhar com o Provider criado especialmente para esse Client: [jlcd/api-cielo3.0-laravel](https://github.com/jlcd/api-cielo3.0-laravel)

Versão mínima do PHP: `5.6`

## Configuração

_Merchant ID_, _Merchant Key_ e _Environment_ deverão ser passados diretamente no construtor da classe `Cielo`.

Ex.:

```php
<?php

use jlcd\Cielo\Cielo;

$cielo = new Cielo(CIELO_ID, CIELO_KEY, CIELO_ENVIRONMENT);

```

Os valores aceitos para `CIELO_ENVIRONMENT` são:

- `sandbox`
- `production`

## Utilização

Os métodos atualmente desenvolvidos neste client são:
- `payment`: Realiza pagamento (apenas via Cartão de Crédito ou token de Cartão de Crédito)
- `cancelPayment`: Cancelamento de pagamento já realizado
- `capturePayment`: Captura de pagamento já realizado
- `tokenizeCreditCard`: Tokenização de Cartão

### Realizar pagamento

```php
<?php

use jlcd\Cielo\Cielo;
use jlcd\Cielo\Resources\CieloPayment;
use jlcd\Cielo\Resources\CieloCreditCard;
use jlcd\Cielo\Resources\CieloCustomer;
use jlcd\Cielo\Resources\CieloOrder;

$cielo = new Cielo(CIELO_ID, CIELO_KEY, CIELO_ENVIRONMENT);

$payment = new CieloPayment();
$payment->setValue(1541);

$creditCard = new CieloCreditCard();
$creditCard->setCardNumber('1234432112344321');
$creditCard->setExpirationDate('12/2018');
$creditCard->setBrand('visa');
$creditCard->setSecurityCode('888');
$creditCard->setHolder('Fulano');
$payment->setCreditCard($creditCard);

$order = new CieloOrder();
$order->setId('123'); // Numero de identificacao personalizado

$customer = new CieloCustomer();
$customer->setName('Fulano');

$payment = $cielo->payment($payment, $order, $customer);

var_dump($payment);

```

### Cancelar pagamento

```php
<?php

use jlcd\Cielo\Cielo;
use jlcd\Cielo\Resources\CieloPayment;

$cielo = new Cielo(CIELO_ID, CIELO_KEY, CIELO_ENVIRONMENT);

$payment = new CieloPayment();
$payment->setId('PAYMENT_ID'); // Id retornado de um pagamento realizado na Cielo
$payment->setValue(1541);

$payment = $cielo->cancelPayment($payment);

var_dump($payment);

```

### Capturar pagamento

```php
<?php

use jlcd\Cielo\Cielo;
use jlcd\Cielo\Resources\CieloPayment;

$cielo = new Cielo(CIELO_ID, CIELO_KEY, CIELO_ENVIRONMENT);

$payment = new CieloPayment();
$payment->setId('PAYMENT_ID'); // Id retornado de um pagamento realizado na Cielo
$payment->setValue(1541);

$payment = $cielo->capturePayment($payment);

var_dump($payment);

```

### Tokenizar Cartão

```php
<?php

use jlcd\Cielo\Cielo;
use jlcd\Cielo\Resources\CieloCreditCard;
use jlcd\Cielo\Resources\CieloCustomer;

$cielo = new Cielo(CIELO_ID, CIELO_KEY, CIELO_ENVIRONMENT);

$creditCard = new CieloCreditCard();
$creditCard->setCardNumber("1234432112344321");
$creditCard->setHolder("Comprador T Cielo");
$creditCard->setExpirationDate("12/2018");
$creditCard->setBrand("Visa");

$customer = new CieloCustomer();
$customer->setName('Fulano');

$token = $cielo->tokenizeCreditCard($creditCard, $customer);

var_dump($token);

```

### Realizar pagamento via Token de Cartão

```php
<?php

use jlcd\Cielo\Cielo;
use jlcd\Cielo\Resources\CieloPayment;
use jlcd\Cielo\Resources\CieloCreditCard;
use jlcd\Cielo\Resources\CieloCustomer;
use jlcd\Cielo\Resources\CieloOrder;

$cielo = new Cielo(CIELO_ID, CIELO_KEY, CIELO_ENVIRONMENT);

$payment = new CieloPayment();
$payment->setValue(1541);

$creditCard = new CieloCreditCard();
$creditCard->setBrand('visa');
$creditCard->setToken('TOKEN_DO_CARTAO'); // Gerado via tokenizeCreditCard
$creditCard->setSecurityCode('888');
$payment->setCreditCard($creditCard);

$order = new CieloOrder();
$order->setId('123'); // Numero de identificacao personalizado

$customer = new CieloCustomer();
$customer->setName('Fulano');

$payment = $cielo->payment($payment, $order, $customer);

var_dump($payment);

```

### Restrições

Versão mínima do PHP: `5.6`

Os valores aceitos para `CieloCreditCard::brand` são como vistos no campo `CreditCard.Brand` em https://developercielo.github.io/Webservice-3.0/?json#criando-uma-transação-simples .

---

Para sugestões ou reportar bugs, utilize [jlcd/api-cielo3.0/issues](https://github.com/jlcd/api-cielo3.0/issues).
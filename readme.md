# Emailage PHP SDK

## Installation

This SDK can be installed easily through composer
```
composer require placetopay/emailage
```

## Usage

```
$emailage = new \PlacetoPay\Emailage\Validator([
    'account' => 'YOUR_MERCHANT',
    'token' => 'THE_API_KEY_PROVIDED',
    'sandbox' => false,
]);
```

Also can provide "verify_ssl" boolean to check or ignore valid ssl certificates


### Additional parameters

You can send additional information to Emailage with the following structure

```
$additional = [
    // Person related
    'payer' => [
        'name' => 'Diego',
        'surname' => 'Calle',
        'email' => 'dnetix@gmail.com',
        'document' => '1040035000',
        'documentType' => 'CC',
        'address' => [
            'street' => 'Fake street 123',
            'city' => 'Medellin',
            'state' => 'Antioquia',
            'postalCode' => '050012',
            'country' => 'CO',
            'phone' => '4442310',
        ],
    ],
    'payment' => [
        'reference' => '1234',
        'amount' => [
            'currency' => 'COP',
            'total' => '12300',
        ],
        'shipping' => [
            'name' => 'Diego',
            'surname' => 'Calle',
            'email' => 'fake@email.com',
            'address' => [
                'street' => 'Fake street 321',
                'city' => 'Sabaneta',
                'state' => 'Antioquia',
                'postalCode' => '050013',
                'country' => 'CO',
                'phone' => '4442310',
            ],
        ],
    ],
    'instrument' => [
        'card' => [
            'number' => '9bbef19476623ca56c17da75fd57734dbf82530686043a6e491c6d71befe8f6e',
            'bin' => '411111',
        ],
    ],
    'userAgent' => 'Chrome XYZ',
];
```

### Validating an email

Keep in mind that additional it's optional

```php
$result = $emailage->query('test@example.com', $additional);
if ($result->isSuccessful()) {
    // There are more information about the response, see src/Messages/RiskResponse
    $score = $result->score();
    // Handle the score
} else {
    // There is some error, you can handle it
    print_r($result->errorMessage());
}
```

### Validating an email and ip

```php
$result = $emailage->query('test@example.com+127.0.0.1');
if ($result->isSuccessful()) {
    // There are more information about the response, see src/Messages/RiskResponse
    $score = $result->score();
    // Handle the score
} else {
    // There is some error, you can handle it
    print_r($result->errorMessage());
}
```

### Validating an ip

```php
$result = $emailage->query('127.0.0.1');
if ($result->isSuccessful()) {
    $riskLevel = $result->ipRiskLevel();
} else {
    // There is some error, you can handle it
    print_r($result->errorMessage());
}
```

### Flag an email as fraud

```php
$result = $emailage->flagFraudEmail('test@example.com', \PlacetoPay\Emailage\Validator::FR_CARD_NOT_PRESENT_FRAUD);
if ($result->isSuccessful()) {
    // Email has been flagged
} else {
    // There is some error, you can handle it
    print_r($result->errorMessage());
}
```

### Flag an email as good

```php
$result = $emailage->flagGoodEmail('test@example.com');
if ($result->isSuccessful()) {
    // Email has been flagged
} else {
    // There is some error, you can handle it
    print_r($result->errorMessage());
}
```

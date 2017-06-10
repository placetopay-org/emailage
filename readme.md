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

### Validating an email

```php
$result = $emailage->query('test@example.com');
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

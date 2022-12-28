# Emailage PHP SDK

## Installation

This SDK can be installed easily through composer
```bash
composer require placetopay/emailage
```

## Usage

```php
$emailage = new \PlacetoPay\Emailage\Validator([
    'account' => 'YOUR_MERCHANT',
    'token' => 'THE_API_KEY_PROVIDED',
    'sandbox' => false,
]);
```

Also can provide "verify_ssl" boolean to check or ignore valid ssl certificates


### Additional parameters

You can send additional information to Emailage with the following structure

```php
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

### Getting the EmailAge Response
Although Emailage delivers the results in a one-level-deep array, you have access to convenient methods for getting 
specifically the information we need from the returned response. You can organize the data in a grouped way according to 
the type of data:

```php
$result = $emailage->query('test@gmail.com+12.12.12.12');

$response = [
    'score' => $result->score(),
    'riskReason' => $result->riskReason(),
    'riskReasonMessage' => $result->riskReasonMessage(),
    'riskStatus' => $result->riskStatus(),
    'riskAdvice' => $result->riskAdvice(),
    'riskAdviceMessage' => $result->riskAdviceMessage(),
    'riskBand' => $result->riskBand(),
    'riskBandMessage' => $result->riskBandMessage(),
    'ipRiskLevel' => $result->ipRiskLevel(),
    'ipRiskLevelMessage' => $result->ipRiskLevelMessage(),
    'sourceIndustry' => $result->sourceIndustry(),
    'lastFlaggedOn' => $result->lastFlaggedOn(),
    'fraudType' => $result->fraudType(),
    'disOverallScore' => $result->disOverallScore(),
    'disOverallScoreDescrition' => $result->disOverallScoreDescrition(),
    'addressInformation' => $result->addressInformation(),
    'digitalIdentityScoreInformation' => $result->digitalIdentityScoreInformation(),
    'emailInformation' => $result->emailInformation(),
    'ipInformation' => $result->ipInformation(),
    'ipRiskInformation' => $result->ipRiskInformation(),
    'ipLocationInformation' => $result->ipLocationInformation(),
    'ownerInformation' => $result->ownerInformation(),
    'phoneInformation' => $result->phoneInformation(),
    'socialMediaInformation' => $result->socialMediaInformation(),
];
```

The above will create an array with the following structure:
```php
[
    'score' => '995',
    'riskReason' => '1',
    'riskReasonMessage' => 'Fraud Level 6',
    'riskStatus' => '3',
    'riskAdvice' => '1',
    'riskAdviceMessage' => 'Fraud Review',
    'riskBand' => '6',
    'riskBandMessage' => 'Fraud Score 900 to 999',
    'ipRiskLevel' => '3',
    'ipRiskLevelMessage' => 'Moderate',
    'sourceIndustry' => 'Anti-Fraud Solution Provider',
    'lastFlaggedOn' => '2018-05-29T17:29:00Z',
    'fraudType' => 'Customer Dispute (Chargeback)',
    'disOverallScore' => '48',
    'disOverallScoreDescrition' => 'Moderate Confidence',
    'addressInformation' => [
        'billRiskCountry' => 'No',
        'cityPostalMatch' => '',
        'domainCountryMatch' => 'Yes',
        'shippingCityPostalMatch' => 'No',
        'shippingForward' => '',
    ],
    'digitalIdentityScoreInformation' => [
        'description' => 'Moderate Confidence',
        'overallScore' => '48',
        'billAddressToFullNameConfidence' => '10',
        'billAddressToLastNameConfidence' => '10',
        'emailToIpConfidence' => '72',
        'emailToBillAddressConfidence' => '30',
        'emailToFullNameConfidence' => '30',
        'emailToLastNameConfidence' => '29',
        'emailToPhoneConfidence' => '32',
        'emailToShipAddressConfidence' => '56',
        'ipToBillAddressConfidence' => '10',
        'ipToFullNameConfidence' => '29',
        'ipToLastNameConfidence' => '68',
        'ipToPhoneConfidence' => '34',
        'ipToShipAddressConfidence' => '29',
        'phoneToBillAddressConfidence' => '43',
        'phoneToFullNameConfidence' => '40',
        'phoneToLastNameConfidence' => '38',
        'phoneToShipAddressConfidence' => '43',
        'shipAddressToBillAddressConfidence' => '40',
        'shipAddressToFullNameConfidence' => '32',
        'shipAddressToLastNameConfidence' => '32',
    ],
    'emailInformation' => [
        'email' => 'test@example.com',
        'age' => '',
        'country' => 'US',
        'company' => '',
        'exists' => 'Not Anymore',
        'status' => 'EmailInexistent',
        'firstSeen' => '2005-05-30T00:00:00Z',
        'firstSeenDays' => '4758',
        'lastSeen' => '2018-06-08T08:15:49Z',
        'imageUrl' => '',
        'hits' => '510',
        'uniqueHits' => '3',
        'creationDays' => '',
        'domain' => [
            'name' => 'gmail.com',
            'age' => '1995-08-13T04:00:00Z',
            'category' => 'Webmail',
            'corporate' => 'No',
            'exists' => 'Yes',
            'company' => 'Google',
            'countryName' => 'United States',
            'riskLevel' => '3',
            'riskCountry' => 'No',
            'riskLevelMessage' => 'Moderate',
            'relevantInfo' => '508',
            'relevantInfoMessage' => 'Valid Webmail Domain from United States',
            'creationDays' => '8335',
            'fraudRisk' => '995 Very High',
        ],
    ],
    'ipInformation' => [
        'ip' => '12.12.12.12',
        'anonymousDetected' => 'No',
        'autonomousSystemNumber' => '32328 alascom inc.',
        'city' => 'fairbanks',
        'corporateProxy' => 'Yes',
        'country' => 'US',
        'countryMatch' => 'Yes',
        'domain' => 'att.com',
        'isp' => 'att services inc.',
        'region' => 'alaska',
        'latitude' => '64.8363',
        'longitude' => '-147.715',
        'netSpeed' => 'broadband',
        'organization' => 'alascom inc.',
        'reputation' => 'Good',
        'userType' => 'wifi',
    ],
    'ipRiskInformation' => [
        'level' => '3',
        'levelMessage' => 'Moderate',
        'reasonId' => '311',
        'reason' => 'Moderate By Proxy Reputation And Country Code',
        'riskCountry' => '',
    ],
    'ipLocationInformation' => [
        'callingCode' => '907',
        'city' => 'fairbanks',
        'cityConfidence' => '95',
        'continentCode' => 'na',
        'country' => 'United States',
        'countryCode' => 'US',
        'countryConfidence' => '99',
        'distanceMil' => '5366',
        'distanceKm' => '8633',
        'latitude' => '64.8363',
        'longitude' => '-147.715',
        'map' => 'https://app.emailage.com/query/GoogleMaps?latLng=64.8363,-147.715',
        'metroCode' => '745',
        'region' => 'alaska',
        'regionConfidence' => '99',
        'postalCode' => '99701',
        'postalConfidence' => '50',
        'timeZone' => '-800',
    ],
    'ownerInformation' => [
        'eName' => 'Test EA',
        'location' => '',
        'title' => '',
    ],
    'phoneInformation' => [
        'carrierName' => 'Verizon Wireless',
        'carrierType' => 'mobile',
        'inBillingLocation' => 'Not Found',
        'owner' => 'Test EA',
        'ownerMatch' => 'Y',
        'ownerType' => 'CONSUMER',
        'status' => 'Valid',
    ],
    'socialMediaInformation' => [
        'smFriends' => '2',
        'smLinks' => [
            [
                'source' => 'GooglePlus',
                'link' => 'https://plus.google.com/10886018',
            ],
        ],
    ],
]
```

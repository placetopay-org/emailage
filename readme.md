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

### Recover the whole result from the service response

```php
print_r($emailage->fullServiceResponse());

/*
[
    'email' => 'test@gmail.com', 
    'ipaddress' => '127.0.0.1', 
    'eName' => '', 
    'emailAge' => '', 
    'email_creation_days' => '', 
    'domainAge' => '1995-08-13T00:00:00Z', 
    'domain_creation_days' => '9964', 
    'firstVerificationDate' => '2013-08-07T07:00:00Z', 
    'first_seen_days' => '3395', 
    'lastVerificationDate' => '2022-11-23T23:22:17Z', 
    'status' => 'Verified', 
    'country' => 'US', 
    'fraudRisk' => '003 Very Low', 
    'EAScore' => '3', 
    'EAReason' => 'Very High Digital Confidence', 
    'EAStatusID' => '2', 
    'EAReasonID' => '183', 
    'EAAdviceID' => '3', 
    'EAAdvice' => 'Lower Fraud Risk', 
    'EARiskBandID' => '1', 
    'EARiskBand' => 'Fraud Score 1 to 100', 
    'source_industry' => '', 
    'fraud_type' => '', 
    'lastflaggedon' => '', 
    'dob' => '', 
    'gender' => '', 
    'location' => '', 
    'smfriends' => '', 
    'totalhits' => '2', 
    'uniquehits' => '1', 
    'imageurl' => '', 
    'emailExists' => 'Yes', 
    'domainExists' => 'Yes', 
    'company' => '', 
    'title' => '', 
    'domainname' => 'gmail.com', 
    'domaincompany' => 'Google', 
    'domaincountryname' => 'United States', 
    'domaincategory' => 'Webmail', 
    'domaincorporate' => 'No', 
    'domainrisklevel' => 'Moderate', 
    'domainrelevantinfo' => 'Valid Webmail Domain from United States', 
    'domainrisklevelID' => '3', 
    'domainrelevantinfoID' => '508', 
    'domaincountrymatch' => 'No', 
    'domainriskcountry' => 'No', 
    'smlinks' => [],
    'ip_risklevel' => 'Moderate', 
    'ip_riskreasonid' => '311', 
    'ip_riskreason' => 'Moderate By Proxy Reputation And Country Code', 
    'ip_reputation' => 'Good', 
    'ip_risklevelid' => '3', 
    'ip_anonymousdetected' => 'No', 
    'ip_isp' => 'epm telecomunicaciones s.a. e.s.p.', 
    'ip_org' => 'epm telecomunicaciones s.a. e.s.p.', 
    'ip_userType' => 'wifi', 
    'ip_netSpeedCell' => 'broadband', 
    'ip_corporateProxy' => 'No', 
    'ip_continentCode' => 'sa', 
    'ip_country' => 'Colombia', 
    'ip_countryCode' => 'CO', 
    'ip_region' => 'antioquia', 
    'ip_city' => 'medellin', 
    'ip_callingcode' => '', 
    'ip_metroCode' => '', 
    'ip_latitude' => '6.210000', 
    'ip_longitude' => '-75.599998', 
    'ip_map' => 'https://app.emailage.com/query/GoogleMaps?latLng=6.21,-75.6&radius=&title=medellin,antioquia,Colombia', 
    'ipcountrymatch' => 'Yes', 
    'ipriskcountry' => '', 
    'ipdistancekm' => '6', 
    'ipdistancemil' => '3', 
    'ipaccuracyradius' => '', 
    'iptimezone' => '-500', 
    'ipasnum' => '13489 epm telecomunicaciones s.a. e.s.p.', 
    'ipdomain' => 'une.net.co', 
    'ip_countryconf' => '99', 
    'ip_regionconf' => '99', 
    'ip_cityconf' => '95', 
    'ip_postalcode' => '050023', 
    'ip_postalconf' => '90', 
    'ip_riskscore' => '', 
    'custphoneInbillingloc' => '', 
    'citypostalmatch' => '', 
    'shipcitypostalmatch' => '', 
    'phone_status' => 'Valid', 
    'shipforward' => 'No', 
    'billriskcountry' => 'No', 
    'phoneowner' => '', 
    'phoneownertype' => '', 
    'phonecarriertype' => 'mobile', 
    'phonecarriernetworkcode' => '', 
    'phonecarriername' => 'Colombia Telecomunicaciones', 
    'phoneownermatch' => '', 
    'overallDigitalIdentityScore' => '81', 
    'emailToIpConfidence' => '63', 
    'emailToPhoneConfidence' => '97', 
    'emailToBillAddressConfidence' => '', 
    'emailToShipAddressConfidence' => '', 
    'emailToFullNameConfidence' => '', 
    'emailToLastNameConfidence' => '', 
    'ipToPhoneConfidence' => '53', 
    'ipToBillAddressConfidence' => '', 
    'ipToShipAddressConfidence' => '', 
    'ipToFullNameConfidence' => '', 
    'ipToLastNameConfidence' => '', 
    'phoneToBillAddressConfidence' => '', 
    'phoneToShipAddressConfidence' => '', 
    'phoneToFullNameConfidence' => '', 
    'phoneToLastNameConfidence' => '', 
    'billAddressToFullNameConfidence' => '', 
    'billAddressToLastNameConfidence' => '', 
    'shipAddressToBillAddressConfidence' => '', 
    'shipAddressToFullNameConfidence' => '', 
    'shipAddressToLastNameConfidence' => '', 
    'disDescription' => 'Very High Confidence',
]
*/
```

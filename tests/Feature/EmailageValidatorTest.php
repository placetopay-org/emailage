<?php


class EmailageValidatorTest extends BaseTestCase
{

    public function testItParsesTheAdditionalInformationCorrectly()
    {
        $emailage = new \PlacetoPay\Emailage\Validator([
            'account' => 'testing',
            'token' => 'testing',
            'sandbox' => true,
        ]);

        $data = [
            // Person related
            'payer' => [
                'name' => 'Maryse',
                'surname' => 'Gorczany',
                'email' => 'maryse@gmail.com',
                'document' => '1040035000',
                'documentType' => 'CC',
                'mobile' => '3006108300',
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
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Chrome XYZ',
        ];

        $parsed = $emailage->parseAdditional(['query' => 'maryse@gmail.com+'], $data);

        $this->assertEquals([
            'EMAIL' => 'maryse@gmail.com',
            'FIRSTNAME' => $data['payer']['name'],
            'LASTNAME' => $data['payer']['surname'],
            'BILLADDRESS' => $data['payer']['address']['street'],
            'BILLCITY' => $data['payer']['address']['city'],
            'BILLREGION' => $data['payer']['address']['state'],
            'BILLPOSTAL' => $data['payer']['address']['postalCode'],
            'BILLCOUNTRY' => $data['payer']['address']['country'],
            'SHIPADDRESS' => $data['payment']['shipping']['address']['street'],
            'SHIPCITY' => $data['payment']['shipping']['address']['city'],
            'SHIPREGION' => $data['payment']['shipping']['address']['state'],
            'SHIPPOSTAL' => $data['payment']['shipping']['address']['postalCode'],
            'SHIPCOUNTRY' => $data['payment']['shipping']['address']['country'],
            'PHONE' => $data['payer']['mobile'],
            'TRANSAMOUNT' => $data['payment']['amount']['total'],
            'TRANSCURRENCY' => $data['payment']['amount']['currency'],
            'USERAGENT' => $data['userAgent'],
            'HASHEDCARDNUMBER' => $data['instrument']['card']['number'],
            'CARDFIRSTSIX' => $data['instrument']['card']['bin'],
            'IP_ADDRESS' => $data['ipAddress'],
        ], $parsed);
    }

    public function testItParsesTheAdditionalInformationWhenMissingData()
    {
        $emailage = new \PlacetoPay\Emailage\Validator([
            'account' => 'testing',
            'token' => 'testing',
        ]);

        $data = [
            // Person related
            'payer' => [
                'name' => 'Maryse',
                'surname' => 'Gorczany',
                'email' => 'maryse@gmail.com',
                'document' => '1040035000',
                'documentType' => 'CC',
                'address' => [
                    'street' => 'Fake street 123',
                    'city' => 'Medellin',
                    'state' => 'Antioquia',
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
            ],
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Chrome XYZ',
        ];

        $parsed = $emailage->parseAdditional(['query' => 'maryse@gmail.com+'], $data);

        $this->assertEquals([
            'EMAIL' => 'maryse@gmail.com',
            'FIRSTNAME' => $data['payer']['name'],
            'LASTNAME' => $data['payer']['surname'],
            'BILLADDRESS' => $data['payer']['address']['street'],
            'BILLCITY' => $data['payer']['address']['city'],
            'BILLREGION' => $data['payer']['address']['state'],
            'BILLCOUNTRY' => $data['payer']['address']['country'],
            'PHONE' => $data['payer']['address']['phone'],
            'TRANSAMOUNT' => $data['payment']['amount']['total'],
            'TRANSCURRENCY' => $data['payment']['amount']['currency'],
            'USERAGENT' => $data['userAgent'],
            'IP_ADDRESS' => $data['ipAddress'],
        ], $parsed);
    }

    public function testItParsesTheIPFromTheQuery()
    {
        $emailage = new \PlacetoPay\Emailage\Validator([
            'account' => 'testing',
            'token' => 'testing',
            'sandbox' => true,
        ]);

        $data = [
            // Person related
            'payer' => [
                'name' => 'Maryse',
                'surname' => 'Gorczany',
                'email' => 'maryse@gmail.com',
                'document' => '1040035000',
                'documentType' => 'CC',
                'mobile' => '3006108300',
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

        $parsed = $emailage->parseAdditional(['query' => 'maryse@gmail.com+127.0.0.1'], $data);

        $this->assertEquals([
            'EMAIL' => 'maryse@gmail.com',
            'FIRSTNAME' => $data['payer']['name'],
            'LASTNAME' => $data['payer']['surname'],
            'BILLADDRESS' => $data['payer']['address']['street'],
            'BILLCITY' => $data['payer']['address']['city'],
            'BILLREGION' => $data['payer']['address']['state'],
            'BILLPOSTAL' => $data['payer']['address']['postalCode'],
            'BILLCOUNTRY' => $data['payer']['address']['country'],
            'SHIPADDRESS' => $data['payment']['shipping']['address']['street'],
            'SHIPCITY' => $data['payment']['shipping']['address']['city'],
            'SHIPREGION' => $data['payment']['shipping']['address']['state'],
            'SHIPPOSTAL' => $data['payment']['shipping']['address']['postalCode'],
            'SHIPCOUNTRY' => $data['payment']['shipping']['address']['country'],
            'PHONE' => $data['payer']['mobile'],
            'TRANSAMOUNT' => $data['payment']['amount']['total'],
            'TRANSCURRENCY' => $data['payment']['amount']['currency'],
            'USERAGENT' => $data['userAgent'],
            'HASHEDCARDNUMBER' => $data['instrument']['card']['number'],
            'CARDFIRSTSIX' => $data['instrument']['card']['bin'],
            'IP_ADDRESS' => '127.0.0.1',
        ], $parsed);
    }
}
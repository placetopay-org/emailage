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
            'email' => 'maryse@gmail.com',
            'firstname' => $data['payer']['name'],
            'lastname' => $data['payer']['surname'],
            'customerid' => $data['payer']['document'],
            'billaddress' => $data['payer']['address']['street'],
            'billcity' => $data['payer']['address']['city'],
            'billregion' => $data['payer']['address']['state'],
            'billpostal' => $data['payer']['address']['postalCode'],
            'billcountry' => $data['payer']['address']['country'],
            'shipaddress' => $data['payment']['shipping']['address']['street'],
            'shipcity' => $data['payment']['shipping']['address']['city'],
            'shipregion' => $data['payment']['shipping']['address']['state'],
            'shippostal' => $data['payment']['shipping']['address']['postalCode'],
            'shipcountry' => $data['payment']['shipping']['address']['country'],
            'phone' => $data['payer']['mobile'],
            'urid' => $data['payment']['reference'],
            'transamount' => $data['payment']['amount']['total'],
            'transcurrency' => $data['payment']['amount']['currency'],
            'useragent' => $data['userAgent'],
            'hashedcardnumber' => $data['instrument']['card']['number'],
            'cardfirstsix' => $data['instrument']['card']['bin'],
            'ip' => $data['ipAddress'],
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
            'email' => 'maryse@gmail.com',
            'firstname' => $data['payer']['name'],
            'lastname' => $data['payer']['surname'],
            'customerid' => $data['payer']['document'],
            'billaddress' => $data['payer']['address']['street'],
            'billcity' => $data['payer']['address']['city'],
            'billregion' => $data['payer']['address']['state'],
            'billcountry' => $data['payer']['address']['country'],
            'phone' => $data['payer']['address']['phone'],
            'urid' => $data['payment']['reference'],
            'transamount' => $data['payment']['amount']['total'],
            'transcurrency' => $data['payment']['amount']['currency'],
            'useragent' => $data['userAgent'],
            'ip' => $data['ipAddress'],
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
            'email' => 'maryse@gmail.com',
            'firstname' => $data['payer']['name'],
            'lastname' => $data['payer']['surname'],
            'customerid' => $data['payer']['document'],
            'billaddress' => $data['payer']['address']['street'],
            'billcity' => $data['payer']['address']['city'],
            'billregion' => $data['payer']['address']['state'],
            'billpostal' => $data['payer']['address']['postalCode'],
            'billcountry' => $data['payer']['address']['country'],
            'shipaddress' => $data['payment']['shipping']['address']['street'],
            'shipcity' => $data['payment']['shipping']['address']['city'],
            'shipregion' => $data['payment']['shipping']['address']['state'],
            'shippostal' => $data['payment']['shipping']['address']['postalCode'],
            'shipcountry' => $data['payment']['shipping']['address']['country'],
            'phone' => $data['payer']['mobile'],
            'urid' => $data['payment']['reference'],
            'transamount' => $data['payment']['amount']['total'],
            'transcurrency' => $data['payment']['amount']['currency'],
            'useragent' => $data['userAgent'],
            'hashedcardnumber' => $data['instrument']['card']['number'],
            'cardfirstsix' => $data['instrument']['card']['bin'],
            'ip' => '127.0.0.1',
        ], $parsed);
    }
}
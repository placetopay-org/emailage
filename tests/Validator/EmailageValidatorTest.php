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
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Chrome XYZ',
        ];

        $parsed = $emailage->parseAdditional($data);

        $this->assertEquals([
            'firstname' => $data['payer']['name'],
            'lastname' => $data['payer']['surname'],
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
            'transamount' => $data['payment']['amount']['total'],
            'transcurrency' => $data['payment']['amount']['currency'],
            'useragent' => $data['userAgent'],
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

        $parsed = $emailage->parseAdditional($data);

        $this->assertEquals([
            'firstname' => $data['payer']['name'],
            'lastname' => $data['payer']['surname'],
            'billaddress' => $data['payer']['address']['street'],
            'billcity' => $data['payer']['address']['city'],
            'billregion' => $data['payer']['address']['state'],
            'billcountry' => $data['payer']['address']['country'],
            'phone' => $data['payer']['address']['phone'],
            'transamount' => $data['payment']['amount']['total'],
            'transcurrency' => $data['payment']['amount']['currency'],
            'useragent' => $data['userAgent'],
        ], $parsed);
    }
}
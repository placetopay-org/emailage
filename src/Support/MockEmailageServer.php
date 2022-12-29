<?php

namespace PlacetoPay\Emailage\Support;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class MockEmailageServer
{
    private static $instance;

    protected $authentication = [];
    protected $parameters = [];

    private function __construct()
    {
    }

    public function authentication(): array
    {
        return $this->authentication;
    }

    public function parameters(string $key = '')
    {
        if ($key) {
            return $this->parameters[$key] ?? null;
        }
        return $this->parameters;
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function MockClient(): Client
    {
        return new Client([
            'handler' => HandlerStack::create(self::getInstance()),
        ]);
    }

    public function response($code, $body, $headers = [], $reason = null)
    {
        if (is_array($body)) {
            $body = json_encode($body);
        }

        $headers = array_replace([
            'Content-Type' => 'application/json',
            'Cache-Control' => 'private',
            'Access-Control-Allow-Origin' => '*',
            'X-Xss-Protection' => '1; mode=block',
            'X-Content-Type-Options' => 'nosniff',
        ], $headers);

        return new FulfilledPromise(
            new Response($code, $headers, utf8_decode($body), '1.1', utf8_decode($reason))
        );
    }

    public function __invoke(RequestInterface $request, array $options)
    {
        parse_str($request->getUri()->getQuery(), $authentication);
        parse_str($request->getBody()->getContents(), $parameters);

        $this->authentication = $authentication;
        $this->parameters = $parameters;

        $token = $this->authentication()['oauth_consumer_key'];

        if ($token == 'unauthorized') {
            return $this->response(200, $this->unauthorizedResponse());
        }

        return $this->response(200, $this->successfulResponse());
    }

    protected function successfulResponse(): array
    {
        $email = $this->parameters('email');

        preg_match('/\\w+_(\\d+)\\@(\\w+\.\\w+)/', $email, $matches);
        $risk = $matches[1] ?? 0;
        $domain = $matches[2] ?? 'gmail.com';

        return [
            'query' => [
                'email' => $email,
                'queryType' => 'EmailAgeVerification',
                'count' => 1,
                'created' => gmdate('Y-m-dTH:i:sZ'),
                'lang' => 'en-US',
                'responseCount' => 1,
                'results' => [
                    [
                        'ipaddress' => $this->parameters('ip'),
                        'email' => $email,
                        'eName' => $this->parameters('firstname') . ' ' . $this->parameters('lastname'),
                        'emailAge' => '',
                        'email_creation_days' => '',
                        'domainAge' => '1995-08-13T04:00:00Z',
                        'domain_creation_days' => '9069',
                        'firstVerificationDate' => '2010-07-17T07:00:00Z',
                        'first_seen_days' => '3617',
                        'lastVerificationDate' => '2020-06-12T03:37:56Z',
                        'status' => 'Verified',
                        'country' => 'US',
                        'fraudRisk' => '071 Very Low',
                        'EAScore' => $risk,
                        'EAReason' => 'Email Created at least 9.9 Years Ago',
                        'EAStatusID' => '2',
                        'EAReasonID' => '14',
                        'EAAdviceID' => '3',
                        'EAAdvice' => 'Lower Fraud Risk',
                        'EARiskBandID' => '1',
                        'EARiskBand' => 'Fraud Score 1 to 100',
                        'source_industry' => '',
                        'fraud_type' => '',
                        'lastflaggedon' => '',
                        'dob' => '',
                        'gender' => 'male',
                        'location' => 'Colombia',
                        'smfriends' => '53',
                        'totalhits' => '2',
                        'uniquehits' => '1',
                        'imageurl' => '',
                        'emailExists' => 'Yes',
                        'domainExists' => 'Yes',
                        'company' => '',
                        'title' => '',
                        'domainname' => $domain,
                        'domaincompany' => 'Google',
                        'domaincountryname' => 'United States',
                        'domaincategory' => 'Webmail',
                        'domaincorporate' => 'No',
                        'domainrisklevel' => 'Moderate',
                        'domainrelevantinfo' => 'Valid Webmail Domain from United States',
                        'domainrisklevelID' => '3',
                        'domainrelevantinfoID' => '508',
                        'domainriskcountry' => 'No',
                        'smlinks' => [
                            [
                                'source' => 'GoogleSearch',
                                'link' => 'https://plus.google.com/115619223171371600000',
                            ],
                            [
                                'source' => 'Facebook',
                                'link' => 'https://www.facebook.com/user',
                            ],
                        ],
                        'phone_status' => 'VALID',
                        'shipforward' => '',
                        'billriskcountry' => 'No',
                        'citypostalmatch' => 'Yes',
                        'domaincountrymatch' => 'Yes',
                        'shipcitypostalmatch' => 'No',
                        'disDescription' => 'High Confidence',
                        'overallDigitalIdentityScore' => 87,
                        'billAddressToFullNameConfidence' => 66,
                        'billAddressToLastNameConfidence' => 55,
                        'emailToIpConfidence' => 80,
                        'emailToBillAddressConfidence' => 78,
                        'emailToFullNameConfidence' => 88,
                        'emailToLastNameConfidence' => 89,
                        'emailToPhoneConfidence' => 44,
                        'emailToShipAddressConfidence' => 78,
                        'ipToBillAddressConfidence' => 90,
                        'ipToFullNameConfidence' => 45,
                        'ipToLastNameConfidence' => 56,
                        'ipToPhoneConfidence' => 71,
                        'ipToShipAddressConfidence' => 100,
                        'phoneToBillAddressConfidence' => 100,
                        'phoneToFullNameConfidence' => 97,
                        'phoneToLastNameConfidence' => 34,
                        'phoneToShipAddressConfidence' => 87,
                        'shipAddressToBillAddressConfidence' => 77,
                        'shipAddressToFullNameConfidence' => 78,
                        'shipAddressToLastNameConfidence' => 34,
                        'ip_risklevelid' => '3',
                        'ip_risklevel' => 'Moderate',
                        'ip_riskreasonid' => '311',
                        'ip_riskreason' => 'Moderate By Proxy Reputation And Country Code',
                        'ipriskcountry' => '',
                        'ip_callingcode' => '907',
                        'ip_city' => 'fairbanks',
                        'ip_cityconf' => '95',
                        'ip_continentCode' => 'na',
                        'ip_country' => 'Colombia',
                        'ip_countryCode' => 'CO',
                        'ip_countryconf' => '99',
                        'ipdistancemil' => '5366',
                        'ipdistancekm' => '8637',
                        'ip_latitude' => '64.8363',
                        'ip_longitude' => '-147.715',
                        'ip_map' => 'https://app.emailage.com/query/GoogleMaps?latLng=64.8363,-147.715',
                        'ip_metroCode' => '745',
                        'ip_region' => 'latinamerica',
                        'ip_regionconf' => '99',
                        'ip_postalcode' => '99701',
                        'ip_postalconf' => '50',
                        'iptimezone' => '-500',
                        'phonecarriername' => 'Verizon Wireless',
                        'phonecarriertype' => 'mobile',
                        'custphoneInbillingloc' => 'Not Found',
                        'phoneowner' => 'Test EA',
                        'phoneownermatch' => 'Y',
                        'phoneownertype' => 'CONSUMER',
                    ],
                ],
            ],
            'responseStatus' => [
                'status' => 'success',
                'errorCode' => '0',
                'description' => '',
            ],
        ];
    }

    private function unauthorizedResponse()
    {
        return [
            'query' => [
                'email' => $this->parameters('email'),
                'queryType' => 'EmailAgeVerification',
                'count' => 1,
                'created' => gmdate('Y-m-dTH:i:sZ'),
                'lang' => 'en-US',
                'responseCount' => 0,
                'results' => [],
            ],
            'responseStatus' => [
                'status' => 'failed',
                'errorCode' => '3002',
                'description' => 'Authentication Error: Your account status is inactive or disabled. Please contact us at support@emailage.com to activate your account.',
            ],
        ];
    }
}

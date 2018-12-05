<?php


namespace PlacetoPay\Emailage;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use PlacetoPay\Emailage\Exceptions\EmailageValidatorException;
use PlacetoPay\Emailage\Messages\FlagResponse;
use PlacetoPay\Emailage\Messages\RiskResponse;

class Validator
{
    protected $apiUrl = 'https://api.emailage.com/';
    protected $sandboxUrl = 'https://sandbox.emailage.com/';

    protected $signatureMethod = 'sha256';

    /**
     * Account SID
     * @var string
     */
    protected $account;

    /**
     * API Token
     * @var string
     */
    protected $token;

    /**
     * @var bool
     */
    protected $sandbox = false;

    /**
     * @var bool
     */
    protected $verify_ssl = true;

    protected $client;

    protected $logger;

    const FR_CARD_NOT_PRESENT_FRAUD = 1;
    const FR_CUSTOMER_DISPUTE = 2;
    const FR_FIRST_PARTY_FRAUD = 3;
    const FR_FIRST_PAYMENT_DEFAULT = 4;
    const FR_APPLICATION_IDENTITY_THEFT = 5;
    const FR_ACCOUNT_IDENTITY_THEFT = 6;
    const FR_SUSPECTED_FRAUD = 7;
    const FR_SYNTHETIC_ID = 8;
    const FR_OTHER = 9;
    const FR_SYSTEM_AUTO_REJECT = 10;

    public static $FRAUD_REASONS = [
        self::FR_CARD_NOT_PRESENT_FRAUD => 'FR_CARD_NOT_PRESENT_FRAUD',
        self::FR_CUSTOMER_DISPUTE => 'FR_CUSTOMER_DISPUTE',
        self::FR_FIRST_PARTY_FRAUD => 'FR_FIRST_PARTY_FRAUD',
        self::FR_FIRST_PAYMENT_DEFAULT => 'FR_FIRST_PAYMENT_DEFAULT',
        self::FR_APPLICATION_IDENTITY_THEFT => 'FR_APPLICATION_IDENTITY_THEFT',
        self::FR_ACCOUNT_IDENTITY_THEFT => 'FR_ACCOUNT_IDENTITY_THEFT',
        self::FR_SUSPECTED_FRAUD => 'FR_SUSPECTED_FRAUD',
        self::FR_SYNTHETIC_ID => 'FR_SYNTHETIC_ID',
        self::FR_OTHER => 'FR_OTHER',
        self::FR_SYSTEM_AUTO_REJECT => 'FR_SYSTEM_AUTO_REJECT',
    ];

    /**
     * @param array $settings
     */
    public function __construct($settings)
    {
        $this->account = $settings['account'];
        $this->token = $settings['token'];

        if (isset($settings['sandbox'])) {
            $this->sandbox = filter_var($settings['sandbox'], FILTER_VALIDATE_BOOLEAN);
        }

        if (isset($settings['verify_ssl'])) {
            $this->verify_ssl = filter_var($settings['verify_ssl'], FILTER_VALIDATE_BOOLEAN);
        }

        if (isset($settings['client'])) {
            $this->client = $settings['client'];
        } else {
            $this->client = new Client();
        }
    }

    /**
     * @param string $url
     * @param array $parameters
     * @param array $additional
     * @return null|string
     */
    private function execute($url, $parameters, $additional = [])
    {
        $auth = [
            'format' => 'json',
            'oauth_consumer_key' => $this->account,
            'oauth_nonce' => rand(),
            'oauth_signature_method' => 'HMAC-' . strtoupper($this->signatureMethod),
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0',
        ];
        $auth['oauth_signature'] = $this->signature($url, $auth);

        $url .= '?' . http_build_query(array_merge($auth, $parameters));

        try {
            $body = version_compare($this->client::VERSION, '6.0.0', '>=') ? 'form_params' : 'body';
            $response = $this->client->post($url, [
                $body => $this->parseAdditional($parameters, $additional),
                'verify' => $this->verify_ssl,
            ]);
            return $response->getBody()->getContents();
        } catch (BadResponseException $e) {
            return null;
        }
    }

    /**
     * @param string $url
     * @param $auth
     * @return string
     */
    protected function signature($url, $auth)
    {
        $hash_Params = [];
        $hash_Params[] = strtoupper('POST');
        $hash_Params[] = urlencode($url);
        $hash_Params[] = urlencode(http_build_query($auth));

        // Make and return the Hash.
        return base64_encode(hash_hmac(strtolower($this->signatureMethod), join('&', $hash_Params), $this->token . '&', TRUE));
    }

    /**
     * @param array $data
     * @return array
     */
    public function parseAdditional($parameters, $data)
    {
        $parsed = [];

        $parameters = explode('+', $parameters['query']);

        if (isset($parameters[1])) {
            $parsed['EMAIL'] = $parameters[0];
            $parsed['IP_ADDRESS'] = $parameters[1];
        } else {
            if (filter_var($parameters[0], FILTER_VALIDATE_IP)) {
                $parsed['IP_ADDRESS'] = $parameters[0];
            } else {
                $parsed['EMAIL'] = $parameters[0];
            }
        }

        if (isset($data['ipAddress'])) {
            $parsed['IP_ADDRESS'] = $data['ipAddress'];
        }

        if (isset($data['payer'])) {
            $payer = $data['payer'];

            $parsed = array_merge($parsed, [
                'FIRSTNAME' => isset($payer['name']) ? $payer['name'] : null,
                'LASTNAME' => isset($payer['surname']) ? $payer['surname'] : null,
                'PHONE' => isset($payer['mobile']) ? $payer['mobile'] : null,
            ]);

            if (isset($payer['address'])) {
                $parsed = array_merge($parsed, [
                    'BILLADDRESS' => isset($payer['address']['street']) ? $payer['address']['street'] : null,
                    'BILLCITY' => isset($payer['address']['city']) ? $payer['address']['city'] : null,
                    'BILLREGION' => isset($payer['address']['state']) ? $payer['address']['state'] : null,
                    'BILLPOSTAL' => isset($payer['address']['postalCode']) ? $payer['address']['postalCode'] : null,
                    'BILLCOUNTRY' => isset($payer['address']['country']) ? $payer['address']['country'] : null,
                ]);

                if (!isset($payer['mobile'])) {
                    $parsed['PHONE'] = isset($payer['address']['phone']) ? $payer['address']['phone'] : null;
                }
            }

        }

        if (isset($data['payment'])) {
            $payment = $data['payment'];

            if (isset($payment['amount'])) {
                $parsed = array_merge($parsed, [
                    'TRANSAMOUNT' => isset($payment['amount']['total']) ? $payment['amount']['total'] : null,
                    'TRANSCURRENCY' => isset($payment['amount']['currency']) ? $payment['amount']['currency'] : null,
                ]);
            }
            if (isset($payment['shipping']) && isset($payment['shipping']['address'])) {
                $shipping = $payment['shipping']['address'];
                $parsed = array_merge($parsed, [
                    'SHIPADDRESS' => isset($shipping['street']) ? $shipping['street'] : null,
                    'SHIPCITY' => isset($shipping['city']) ? $shipping['city'] : null,
                    'SHIPREGION' => isset($shipping['state']) ? $shipping['state'] : null,
                    'SHIPPOSTAL' => isset($shipping['postalCode']) ? $shipping['postalCode'] : null,
                    'SHIPCOUNTRY' => isset($shipping['country']) ? $shipping['country'] : null,
                ]);
            }
        }

        if (isset($data['instrument']) && isset($data['instrument']['card'])) {
            if (isset($data['instrument']['card']['number'])) {
                $parsed['HASHEDCARDNUMBER'] = $data['instrument']['card']['number'];
            }
            if (isset($data['instrument']['card']['bin'])) {
                $parsed['CARDFIRSTSIX'] = $data['instrument']['card']['bin'];
            }
        }

        if (isset($data['userAgent'])) {
            $parsed['USERAGENT'] = $data['userAgent'];
        }

        return array_filter($parsed);
    }

    /**
     * Cleans the last + if no ip provided
     * @param string $email
     * @return string
     */
    protected function clearEmail($email)
    {
        return preg_replace('/\+$/', '', trim($email));
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        if ($this->sandbox) {
            return $this->sandboxUrl;
        }

        return $this->apiUrl;
    }

    /**
     * Receives a email, ip or email+ip information and returns a validation response
     * @param string $email
     * @param array $parameters
     * @return RiskResponse
     */
    public function query($email, $parameters = [])
    {
        $url = $this->getUrl() . 'emailagevalidator/';
        $email = $this->clearEmail($email);

        $response = $this->execute($url, ['query' => $email], $parameters);

        return new RiskResponse($response);
    }

    /**
     * @param string $email
     * @param string $reason
     * @param array $parameters
     * @return FlagResponse
     * @throws EmailageValidatorException
     */
    public function flagFraudEmail($email, $reason, $parameters = [])
    {
        if (!array_key_exists($reason, self::$FRAUD_REASONS)) {
            throw new EmailageValidatorException('Invalid reason for fraud provided');
        }

        $url = $this->getUrl() . 'emailagevalidator/flag/';
        $email = $this->clearEmail($email);

        $response = $this->execute($url, [
            'query' => $email,
            'flag' => 'fraud',
            'fraudcodeID' => $reason,
        ], $parameters);

        return new FlagResponse($response);
    }

    /**
     * @param string $email
     * @param array $parameters
     * @return FlagResponse
     */
    public function flagGoodEmail($email, $parameters = [])
    {
        $url = $this->getUrl() . 'emailagevalidator/flag/';
        $email = $this->clearEmail($email);

        $response = $this->execute($url, [
            'query' => $email,
            'flag' => 'good',
        ], $parameters);

        return new FlagResponse($response);
    }

}
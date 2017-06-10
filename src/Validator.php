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

    protected $sandbox = false;

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

    public function __construct($settings)
    {
        $this->account = $settings['account'];
        $this->token = $settings['token'];

        if (isset($settings['sandbox']))
            $this->sandbox = filter_var($settings['sandbox'], FILTER_VALIDATE_BOOLEAN);
    }

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

        $client = new Client();

        try {
            $response = $client->request('POST', $url, [
                'form_params' => $additional,
            ]);
            return $response->getBody()->getContents();
        } catch (BadResponseException $e) {
            return null;
        }
    }

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
     * Cleans the last + if no ip provided
     * @param $email
     * @return mixed
     */
    protected function clearEmail($email)
    {
        return preg_replace('/\+$/', '', $email);
    }

    protected function getUrl()
    {
        if ($this->sandbox)
            return $this->sandboxUrl;

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
        return new RiskResponse($this->execute($url, [
            'query' => $email,
        ], $parameters));
    }

    /**
     * @param $email
     * @param $reason
     * @param array $parameters
     * @return FlagResponse
     * @throws EmailageValidatorException
     */
    public function flagFraudEmail($email, $reason, $parameters = [])
    {
        if (!array_key_exists($reason, self::$FRAUD_REASONS))
            throw new EmailageValidatorException('Invalid reason for fraud provided');

        $url = $this->getUrl() . 'emailagevalidator/flag/';
        $email = $this->clearEmail($email);
        return new FlagResponse($this->execute($url, [
            'query' => $email,
            'flag' => 'fraud',
            'fraudcodeID' => $reason,
        ], $parameters));
    }

    public function flagGoodEmail($email, $parameters = [])
    {
        $url = $this->getUrl() . 'emailagevalidator/flag/';
        $email = $this->clearEmail($email);
        return new FlagResponse($this->execute($url, [
            'query' => $email,
            'flag' => 'good',
        ], $parameters));
    }

}
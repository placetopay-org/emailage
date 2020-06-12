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

    public function parameters()
    {
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

        return $this->response(200, '{"query":{"email":"dnetix%40gmail.com","queryType":"EmailAgeVerification","count":1,"created":"2020-06-12T03:44:11Z","lang":"en-US","responseCount":0,"results":[]},"responseStatus":{"status":"failed","errorCode":"3002","description":"Authentication Error: Your account status is inactive or disabled. Please contact us at support@emailage.com to activate your account."}}');
    }
}

<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use PlacetoPay\Emailage\Support\MockEmailageServer;
use PlacetoPay\Emailage\Validator;

class BaseTestCase extends TestCase
{
    public function service(array $overrides = [])
    {
        return new Validator(array_replace([
            'account' => 'testing',
            'token' => 'testing',
            'verify_ssl' => false,
            'sandbox' => true,
            'client' => MockEmailageServer::MockClient(),
        ], $overrides));
    }

    public function serialize($data)
    {
        return base64_encode(serialize($data));
    }

    public function unserialize($coded)
    {
        return unserialize(base64_decode($coded));
    }
}

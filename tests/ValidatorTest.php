<?php

namespace Tests;

use PlacetoPay\Emailage\Exceptions\EmailageValidatorException;
use PlacetoPay\Emailage\Support\MockEmailageServer;
use PlacetoPay\Emailage\Validator;

class ValidatorTest extends BaseTestCase
{
    /**
     * @test
     */
    public function it_can_be_created_without_settings()
    {
        $emailage = new Validator();
        $this->assertInstanceOf(Validator::class, $emailage);
    }

    /**
     * @test
     */
    public function it_fallback_if_no_client_provided()
    {
        $emailage = new Validator([
            'account' => 'testing',
            'token' => 'testing',
        ]);
        $this->assertInstanceOf(Validator::class, $emailage);
    }

    /**
     * @test
     */
    public function it_allows_to_setup_the_settings_after_the_instance_is_created()
    {
        $emailage = new Validator();

        $emailage->setSettings([
            'account' => 'AA39B910BAD84D7EBD5A5C3833468E28',
            'token' => '58027C8B66424ACD9FC498F00591224E',
            'sandbox' => true,
            'verify_ssl' => true,
            'client' => MockEmailageServer::MockClient(),
        ]);

        $result = $emailage->query('dnetix@gmail.com');

        $authenticationSent = MockEmailageServer::getInstance()->authentication();

        $this->assertEquals($authenticationSent['oauth_consumer_key'], 'AA39B910BAD84D7EBD5A5C3833468E28');
        $this->assertEquals('dnetix@gmail.com', $result->query());
    }

    /**
     * @test
     */
    public function it_handles_an_unauthorized_request()
    {
        $this->expectException(EmailageValidatorException::class);
        $this->expectExceptionCode(3002);

        $emailage = $this->service([
            'account' => 'unauthorized',
        ]);

        $emailage->query('191.168.0.1');
    }

    /**
     * @test
     */
    public function it_handles_a_case_with_bad_settings()
    {
        $this->expectException(EmailageValidatorException::class);
        $this->expectExceptionCode(400);

        $emailage = new Validator();

        $emailage->query('some@mail.com');
    }

    /**
     * @test
     */
    public function it_handles_risk_on_mock_with_email()
    {
        $result = $this->service()->query('risk_890@yopmail.com');
        $this->assertEquals(890, $result->score());

        $result = $this->service()->query('someother_20@yopmail.com');
        $this->assertEquals(20, $result->score());
        $this->assertEquals('yopmail.com', $result->emailInformation()['domain']['name']);
    }
}

<?php

namespace Tests\Feature;

use PlacetoPay\Emailage\Messages\FlagResponse;
use Tests\BaseTestCase;

class FlagResponseTest extends BaseTestCase
{
    /**
     * @test
     */
    public function it_parses_an_email_flag_response()
    {
        $result = $this->unserialize('czoyMjM6Iu+7v3sicXVlcnkiOnsiZW1haWwiOiJwcnVlYmFzcDJwJTQwZ21haWwuY29tIiwiZmxhZyI6ImZyYXVkIiwiZnJhdWRjb2RlSUQiOiI3IiwicXVlcnlUeXBlIjoiRW1haWxGbGFnIiwiY3JlYXRlZCI6IjIwMTctMDYtMTBUMDA6MDA6NDVaIiwibGFuZyI6ImVuLVVTIn0sInJlc3BvbnNlU3RhdHVzIjp7InN0YXR1cyI6InN1Y2Nlc3MiLCJlcnJvckNvZGUiOiIwIiwiZGVzY3JpcHRpb24iOiIifX0iOw==');
        $response = new FlagResponse($result);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('7', $response->flagReason());
    }
}

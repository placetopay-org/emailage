<?php

namespace Tests\Feature;

use PlacetoPay\Emailage\Exceptions\EmailageValidatorException;
use PlacetoPay\Emailage\Messages\RiskResponse;
use Tests\BaseTestCase;

class RiskResponseTest extends BaseTestCase
{
    /**
     * @test
     */
    public function it_parses_a_simple_response()
    {
        $result = $this->unserialize('czoxMzI1OiJ7InF1ZXJ5Ijp7ImVtYWlsIjoicHJ1ZWJhc3AycDIwMTYlNDBnbWFpbC5jb20iLCJxdWVyeVR5cGUiOiJFbWFpbEFnZVZlcmlmaWNhdGlvbiIsImNvdW50IjoxLCJjcmVhdGVkIjoiMjAxNy0wNi0wOVQyMzo1ODoyNloiLCJsYW5nIjoiZW4tVVMiLCJyZXNwb25zZUNvdW50IjoxLCJyZXN1bHRzIjpbeyJlbWFpbCI6InBydWViYXNwMnAyMDE2JTQwZ21haWwuY29tIiwiZU5hbWUiOiIiLCJlbWFpbEFnZSI6IiIsImRvbWFpbkFnZSI6IjE5OTUtMDgtMTNUMDc6MDA6MDBaIiwiZmlyc3RWZXJpZmljYXRpb25EYXRlIjoiMjAxNy0wNi0wOVQyMzo1ODoyNloiLCJsYXN0VmVyaWZpY2F0aW9uRGF0ZSI6IjIwMTctMDYtMDlUMjM6NTg6MjZaIiwic3RhdHVzIjoiVmFsaWREb21haW4iLCJjb3VudHJ5IjoiVVMiLCJmcmF1ZFJpc2siOiI1MDAgTW9kZXJhdGUiLCJFQVNjb3JlIjoiNTAwIiwiRUFSZWFzb24iOiJMaW1pdGVkIEhpc3RvcnkgZm9yIEVtYWlsIiwiRUFTdGF0dXNJRCI6IjQiLCJFQVJlYXNvbklEIjoiOCIsIkVBQWR2aWNlSUQiOiI0IiwiRUFBZHZpY2UiOiJNb2RlcmF0ZSBGcmF1ZCBSaXNrIiwiRUFSaXNrQmFuZElEIjoiMyIsIkVBUmlza0JhbmQiOiJGcmF1ZCBTY29yZSAzMDEgdG8gNjAwIiwic291cmNlX2luZHVzdHJ5IjoiIiwiZnJhdWRfdHlwZSI6IiIsImxhc3RmbGFnZ2Vkb24iOiIiLCJkb2IiOiIiLCJnZW5kZXIiOiIiLCJsb2NhdGlvbiI6IiIsInNtZnJpZW5kcyI6IiIsInRvdGFsaGl0cyI6IjEiLCJ1bmlxdWVoaXRzIjoiMSIsImltYWdldXJsIjoiIiwiZW1haWxFeGlzdHMiOiJOb3QgU3VyZSIsImRvbWFpbkV4aXN0cyI6IlllcyIsImNvbXBhbnkiOiIiLCJ0aXRsZSI6IiIsImRvbWFpbm5hbWUiOiJnbWFpbC5jb20iLCJkb21haW5jb21wYW55IjoiR29vZ2xlIiwiZG9tYWluY291bnRyeW5hbWUiOiJVbml0ZWQrU3RhdGVzIiwiZG9tYWluY2F0ZWdvcnkiOiJXZWJtYWlsIiwiZG9tYWluY29ycG9yYXRlIjoiTm8iLCJkb21haW5yaXNrbGV2ZWwiOiJNb2RlcmF0ZSIsImRvbWFpbnJlbGV2YW50aW5mbyI6IlZhbGlkIFdlYm1haWwgRG9tYWluIGZyb20gVW5pdGVkIFN0YXRlcyIsImRvbWFpbnJpc2tsZXZlbElEIjoiMyIsImRvbWFpbnJlbGV2YW50aW5mb0lEIjoiNTA4Iiwic21saW5rcyI6W10sInBob25lX3N0YXR1cyI6IiIsInNoaXBmb3J3YXJkIjoiIiwgIm92ZXJhbGxEaWdpdGFsSWRlbnRpdHlTY29yZSI6ICI0OCIsICJkaXNEZXNjcmlwdGlvbiI6ICJNb2RlcmF0ZSBDb25maWRlbmNlIn1dfSwicmVzcG9uc2VTdGF0dXMiOnsic3RhdHVzIjoic3VjY2VzcyIsImVycm9yQ29kZSI6IjAiLCJkZXNjcmlwdGlvbiI6IiJ9fSI7');
        $riskResponse = new RiskResponse($result);

        $this->assertTrue($riskResponse->isSuccessful());
        $this->assertEquals(0, $riskResponse->errorCode());
        $this->assertEquals('', $riskResponse->errorMessage());
        $this->assertEquals('pruebasp2p2016@gmail.com', $riskResponse->query());
        $this->assertEquals(500, $riskResponse->score());
        $this->assertEquals(3, $riskResponse->riskBand());
        $this->assertEquals('Fraud Score 301 to 600', $riskResponse->riskBandMessage());
        $this->assertEquals(4, $riskResponse->riskStatus());
        $this->assertEquals(8, $riskResponse->riskReason());
        $this->assertEquals(4, $riskResponse->riskAdvice());
        $this->assertEquals('Limited History for Email', $riskResponse->riskReasonMessage());
        $this->assertEquals('Moderate Fraud Risk', $riskResponse->riskAdviceMessage());
        $this->assertNull($riskResponse->ipRiskLevel());
        $this->assertEmpty($riskResponse->ipInformation());
        $this->assertEquals(48, $riskResponse->disOverallScore());
        $this->assertEquals('Moderate Confidence', $riskResponse->disOverallScoreDescrition());
    }

    /**
     * @test
     */
    public function it_parses_an_email_and_ip_response()
    {
        $result = $this->unserialize('czoyMDIxOiLvu797InF1ZXJ5Ijp7ImVtYWlsIjoicHJ1ZWJhc3AycDIwMTYlNDBnbWFpbC5jb20lMmIxODEuMTM4LjQ3LjE5NCIsInF1ZXJ5VHlwZSI6IkVtYWlsSVBSaXNrIiwiY291bnQiOjEsImNyZWF0ZWQiOiIyMDE3LTA2LTA5VDIzOjU5OjE2WiIsImxhbmciOiJlbi1VUyIsInJlc3BvbnNlQ291bnQiOjEsInJlc3VsdHMiOlt7ImVtYWlsIjoicHJ1ZWJhc3AycDIwMTYlNDBnbWFpbC5jb20iLCJpcGFkZHJlc3MiOiIxODEuMTM4LjQ3LjE5NCIsImVOYW1lIjoiIiwiZW1haWxBZ2UiOiIiLCJkb21haW5BZ2UiOiIxOTk1LTA4LTEzVDA3OjAwOjAwWiIsImZpcnN0VmVyaWZpY2F0aW9uRGF0ZSI6IjIwMTctMDYtMDlUMjM6NTk6MTZaIiwibGFzdFZlcmlmaWNhdGlvbkRhdGUiOiIyMDE3LTA2LTA5VDIzOjU5OjE2WiIsInN0YXR1cyI6IlZhbGlkRG9tYWluIiwiY291bnRyeSI6IlVTIiwiZnJhdWRSaXNrIjoiNTAwIE1vZGVyYXRlIiwiRUFTY29yZSI6IjUwMCIsIkVBUmVhc29uIjoiTGltaXRlZCBIaXN0b3J5IGZvciBFbWFpbCIsIkVBU3RhdHVzSUQiOiI0IiwiRUFSZWFzb25JRCI6IjgiLCJFQUFkdmljZUlEIjoiNCIsIkVBQWR2aWNlIjoiTW9kZXJhdGUgRnJhdWQgUmlzayIsIkVBUmlza0JhbmRJRCI6IjMiLCJFQVJpc2tCYW5kIjoiRnJhdWQgU2NvcmUgMzAxIHRvIDYwMCIsInNvdXJjZV9pbmR1c3RyeSI6IiIsImZyYXVkX3R5cGUiOiIiLCJsYXN0ZmxhZ2dlZG9uIjoiIiwiZG9iIjoiIiwiZ2VuZGVyIjoiIiwibG9jYXRpb24iOiIiLCJzbWZyaWVuZHMiOiIiLCJ0b3RhbGhpdHMiOiIyIiwidW5pcXVlaGl0cyI6IjEiLCJpbWFnZXVybCI6IiIsImVtYWlsRXhpc3RzIjoiTm90IFN1cmUiLCJkb21haW5FeGlzdHMiOiJZZXMiLCJjb21wYW55IjoiIiwidGl0bGUiOiIiLCJkb21haW5uYW1lIjoiZ21haWwuY29tIiwiZG9tYWluY29tcGFueSI6Ikdvb2dsZSIsImRvbWFpbmNvdW50cnluYW1lIjoiVW5pdGVkK1N0YXRlcyIsImRvbWFpbmNhdGVnb3J5IjoiV2VibWFpbCIsImRvbWFpbmNvcnBvcmF0ZSI6Ik5vIiwiZG9tYWlucmlza2xldmVsIjoiTW9kZXJhdGUiLCJkb21haW5yZWxldmFudGluZm8iOiJWYWxpZCBXZWJtYWlsIERvbWFpbiBmcm9tIFVuaXRlZCBTdGF0ZXMiLCJkb21haW5yaXNrbGV2ZWxJRCI6IjMiLCJkb21haW5yZWxldmFudGluZm9JRCI6IjUwOCIsInNtbGlua3MiOltdLCJpcF9yaXNrbGV2ZWxpZCI6IjMiLCJpcF9yaXNrbGV2ZWwiOiJNb2RlcmF0ZSIsImlwX3Jpc2tyZWFzb25pZCI6IjMwMSIsImlwX3Jpc2tyZWFzb24iOiJNb2RlcmF0ZSBSaXNrIiwiaXBfcmVwdXRhdGlvbiI6Ikdvb2QiLCJpcF9hbm9ueW1vdXNkZXRlY3RlZCI6IiIsImlwX2lzcCI6IiIsImlwX29yZyI6IiIsImlwX3VzZXJUeXBlIjoiIiwiaXBfbmV0U3BlZWRDZWxsIjoiIiwiaXBfY29ycG9yYXRlUHJveHkiOiIiLCJpcF9jb250aW5lbnRDb2RlIjoiIiwiaXBfY291bnRyeSI6IiIsImlwX2NvdW50cnlDb2RlIjoiIiwiaXBfcmVnaW9uIjoiIiwiaXBfY2l0eSI6IiIsImlwX2NhbGxpbmdjb2RlIjoiIiwiaXBfbWV0cm9Db2RlIjoiIiwiaXBfbGF0aXR1ZGUiOiIiLCJpcF9sb25naXR1ZGUiOiIiLCJpcF9tYXAiOiIiLCJpcGNvdW50cnltYXRjaCI6IiIsImlwcmlza2NvdW50cnkiOiIiLCJpcGRpc3RhbmNla20iOiIiLCJpcGRpc3RhbmNlbWlsIjoiIiwiaXBhY2N1cmFjeXJhZGl1cyI6IiIsImlwdGltZXpvbmUiOiIiLCJpcGFzbnVtIjoiIiwiaXBkb21haW4iOiIiLCJpcF9jb3VudHJ5Y29uZiI6IiIsImlwX3JlZ2lvbmNvbmYiOiIiLCJpcF9jaXR5Y29uZiI6IiIsImlwX3Bvc3RhbGNvZGUiOiIiLCJpcF9wb3N0YWxjb25mIjoiIiwiaXBfcmlza3Njb3JlIjoiIiwiY3VzdHBob25lSW5iaWxsaW5nbG9jIjoiIiwiY2l0eXBvc3RhbG1hdGNoIjoiIiwic2hpcGNpdHlwb3N0YWxtYXRjaCI6IiIsInBob25lX3N0YXR1cyI6IiIsInNoaXBmb3J3YXJkIjoiIn1dfSwicmVzcG9uc2VTdGF0dXMiOnsic3RhdHVzIjoic3VjY2VzcyIsImVycm9yQ29kZSI6IjAiLCJkZXNjcmlwdGlvbiI6IiJ9fSI7');
        $riskResponse = new RiskResponse($result);

        $this->assertTrue($riskResponse->isSuccessful());
        $this->assertEquals(0, $riskResponse->errorCode());
        $this->assertEquals('', $riskResponse->errorMessage());
        $this->assertEquals('pruebasp2p2016@gmail.com+181.138.47.194', $riskResponse->query());
        $this->assertEquals(500, $riskResponse->score());
        $this->assertEquals(3, $riskResponse->riskBand());
        $this->assertEquals('Fraud Score 301 to 600', $riskResponse->riskBandMessage());
        $this->assertEquals(4, $riskResponse->riskStatus());
        $this->assertEquals(8, $riskResponse->riskReason());
        $this->assertEquals(4, $riskResponse->riskAdvice());
        $this->assertEquals('Limited History for Email', $riskResponse->riskReasonMessage());
        $this->assertEquals('Moderate Fraud Risk', $riskResponse->riskAdviceMessage());
        $this->assertEquals(3, $riskResponse->ipRiskLevel());
        $this->assertEquals([
            'ip' => '181.138.47.194',
            'isp' => '',
            'country' => '',
            'region' => '',
            'city' => '',
            'latitude' => '',
            'longitude' => '',
            'anonymousDetected' => '',
            'autonomousSystemNumber' => '',
            'corporateProxy' => '',
            'countryMatch' => '',
            'domain' => '',
            'netSpeed' => '',
            'organization' => '',
            'reputation' => 'Good',
            'userType' => '',
        ], $riskResponse->ipInformation());
        $this->assertEquals('', $riskResponse->sourceIndustry());
        $this->assertEquals('', $riskResponse->lastFlaggedOn());
        $this->assertEquals('', $riskResponse->fraudType());
        $this->assertEquals([
            'email' => 'pruebasp2p2016@gmail.com',
            'age' => '',
            'country' => 'US',
            'exists' => 'Not Sure',
            'status' => 'ValidDomain',
            'firstSeen' => '2017-06-09T23:59:16Z',
            'firstSeenDays' => null,
            'lastSeen' => '2017-06-09T23:59:16Z',
            'imageUrl' => '',
            'hits' => '2',
            'uniqueHits' => '1',
            'creationDays' => null,
            'domain' => [
                'name' => 'gmail.com',
                'age' => '1995-08-13T07:00:00Z',
                'category' => 'Webmail',
                'corporate' => 'No',
                'exists' => 'Yes',
                'company' => 'Google',
                'countryName' => 'United States',
                'riskLevel' => '3',
                'riskLevelMessage' => 'Moderate',
                'relevantInfo' => '508',
                'relevantInfoMessage' => 'Valid Webmail Domain from United States',
                'creationDays' => null,
                'fraudRisk' => '500 Moderate',
                'riskCountry' => null,
            ],
            'company' => '',
        ], $riskResponse->emailInformation());
    }

    /**
     * @test
     */
    public function it_parses_an_ip_response()
    {
        $result = $this->unserialize('czoxMDIzOiLvu797InF1ZXJ5Ijp7ImlwYWRkcmVzcyI6IjE4MS4xMzguNDcuMTk0IiwicXVlcnlUeXBlIjoiSVBSaXNrIiwiY291bnQiOjEsImNyZWF0ZWQiOiIyMDE3LTA2LTEwVDAwOjAwOjA4WiIsImxhbmciOiJlbi1VUyIsInJlc3BvbnNlQ291bnQiOjEsInJlc3VsdHMiOlt7ImlwYWRkcmVzcyI6IjE4MS4xMzguNDcuMTk0IiwiaXBfcmlza2xldmVsaWQiOiIzIiwiaXBfcmlza2xldmVsIjoiTW9kZXJhdGUiLCJpcF9yaXNrcmVhc29uaWQiOiIzMDEiLCJpcF9yaXNrcmVhc29uIjoiTW9kZXJhdGUgUmlzayIsImlwX3JlcHV0YXRpb24iOiJHb29kIiwiaXBfYW5vbnltb3VzZGV0ZWN0ZWQiOiIiLCJpcF9pc3AiOiIiLCJpcF9vcmciOiIiLCJpcF91c2VyVHlwZSI6IiIsImlwX25ldFNwZWVkQ2VsbCI6IiIsImlwX2NvcnBvcmF0ZVByb3h5IjoiIiwiaXBfY29udGluZW50Q29kZSI6IiIsImlwX2NvdW50cnkiOiIiLCJpcF9jb3VudHJ5Q29kZSI6IiIsImlwX3JlZ2lvbiI6IiIsImlwX2NpdHkiOiIiLCJpcF9jYWxsaW5nY29kZSI6IiIsImlwX21ldHJvQ29kZSI6IiIsImlwX2xhdGl0dWRlIjoiIiwiaXBfbG9uZ2l0dWRlIjoiIiwiaXBfbWFwIjoiIiwiaXBjb3VudHJ5bWF0Y2giOiIiLCJpcHJpc2tjb3VudHJ5IjoiIiwiaXBkaXN0YW5jZWttIjoiIiwiaXBkaXN0YW5jZW1pbCI6IiIsImlwYWNjdXJhY3lyYWRpdXMiOiIiLCJpcHRpbWV6b25lIjoiIiwiaXBhc251bSI6IiIsImlwZG9tYWluIjoiIiwiaXBfY291bnRyeWNvbmYiOiIiLCJpcF9yZWdpb25jb25mIjoiIiwiaXBfY2l0eWNvbmYiOiIiLCJpcF9wb3N0YWxjb2RlIjoiIiwiaXBfcG9zdGFsY29uZiI6IiIsImlwX3Jpc2tzY29yZSI6IiIsImN1c3RwaG9uZUluYmlsbGluZ2xvYyI6IiIsImNpdHlwb3N0YWxtYXRjaCI6IiIsInNoaXBjaXR5cG9zdGFsbWF0Y2giOiIiLCJwaG9uZV9zdGF0dXMiOiIiLCJzaGlwZm9yd2FyZCI6IiJ9XX0sInJlc3BvbnNlU3RhdHVzIjp7InN0YXR1cyI6InN1Y2Nlc3MiLCJlcnJvckNvZGUiOiIwIiwiZGVzY3JpcHRpb24iOiIifX0iOw==');
        $riskResponse = new RiskResponse($result);

        $this->assertTrue($riskResponse->isSuccessful());
        $this->assertEquals('IPRisk', $riskResponse->queryType());
        $this->assertEquals(0, $riskResponse->errorCode());
        $this->assertEquals('', $riskResponse->errorMessage());
        $this->assertEquals('181.138.47.194', $riskResponse->query());
        $this->assertNull($riskResponse->score());
        $this->assertNull($riskResponse->riskBand());
        $this->assertNull($riskResponse->riskBandMessage());
        $this->assertNull($riskResponse->riskStatus());
        $this->assertNull($riskResponse->riskReason());
        $this->assertNull($riskResponse->riskAdvice());
        $this->assertNull($riskResponse->riskReasonMessage());
        $this->assertNull($riskResponse->riskAdviceMessage());
        $this->assertEquals(3, $riskResponse->ipRiskLevel());
        $this->assertEquals('Moderate', $riskResponse->ipRiskLevelMessage());
        $this->assertEquals(
            [
                'ip' => '181.138.47.194',
                'isp' => '',
                'country' => '',
                'region' => '',
                'city' => '',
                'latitude' => '',
                'longitude' => '',
                'anonymousDetected' => '',
                'autonomousSystemNumber' => '',
                'corporateProxy' => '',
                'countryMatch' => '',
                'domain' => '',
                'netSpeed' => '',
                'organization' => '',
                'reputation' => 'Good',
                'userType' => '',
            ],
            $riskResponse->ipInformation()
        );
    }

    /**
     * @test
     */
    public function it_parses_an_address_response()
    {
        $result = $this->unserialize('czoxMTI5OiJ7InF1ZXJ5Ijp7ImlwYWRkcmVzcyI6IjE4MS4xMzguNDcuMTk0IiwicXVlcnlUeXBlIjoiSVBSaXNrIiwiY291bnQiOjEsImNyZWF0ZWQiOiIyMDE3LTA2LTEwVDAwOjAwOjA4WiIsImxhbmciOiJlbi1VUyIsInJlc3BvbnNlQ291bnQiOjEsInJlc3VsdHMiOlt7ImlwYWRkcmVzcyI6IjE4MS4xMzguNDcuMTk0IiwiaXBfcmlza2xldmVsaWQiOiIzIiwiaXBfcmlza2xldmVsIjoiTW9kZXJhdGUiLCJpcF9yaXNrcmVhc29uaWQiOiIzMDEiLCJpcF9yaXNrcmVhc29uIjoiTW9kZXJhdGUgUmlzayIsImlwX3JlcHV0YXRpb24iOiJHb29kIiwiaXBfYW5vbnltb3VzZGV0ZWN0ZWQiOiIiLCJpcF9pc3AiOiIiLCJpcF9vcmciOiIiLCJpcF91c2VyVHlwZSI6IiIsImlwX25ldFNwZWVkQ2VsbCI6IiIsImlwX2NvcnBvcmF0ZVByb3h5IjoiIiwiaXBfY29udGluZW50Q29kZSI6IiIsImlwX2NvdW50cnkiOiIiLCJpcF9jb3VudHJ5Q29kZSI6IiIsImlwX3JlZ2lvbiI6IiIsImlwX2NpdHkiOiIiLCJpcF9jYWxsaW5nY29kZSI6IiIsImlwX21ldHJvQ29kZSI6IiIsImlwX2xhdGl0dWRlIjoiIiwiaXBfbG9uZ2l0dWRlIjoiIiwiaXBfbWFwIjoiIiwiaXBjb3VudHJ5bWF0Y2giOiIiLCJpcHJpc2tjb3VudHJ5IjoiIiwiaXBkaXN0YW5jZWttIjoiIiwiaXBkaXN0YW5jZW1pbCI6IiIsImlwYWNjdXJhY3lyYWRpdXMiOiIiLCJpcHRpbWV6b25lIjoiIiwiaXBhc251bSI6IiIsImlwZG9tYWluIjoiIiwiaXBfY291bnRyeWNvbmYiOiIiLCJpcF9yZWdpb25jb25mIjoiIiwiaXBfY2l0eWNvbmYiOiIiLCJpcF9wb3N0YWxjb2RlIjoiIiwiaXBfcG9zdGFsY29uZiI6IiIsImlwX3Jpc2tzY29yZSI6IiIsImN1c3RwaG9uZUluYmlsbGluZ2xvYyI6IiIsImNpdHlwb3N0YWxtYXRjaCI6IiIsInNoaXBjaXR5cG9zdGFsbWF0Y2giOiIiLCJwaG9uZV9zdGF0dXMiOiIiLCJzaGlwZm9yd2FyZCI6IiIsICJiaWxscmlza2NvdW50cnkiOiAiTm8iLCAiY2l0eXBvc3RhbG1hdGNoIjogIlllcyIsICJkb21haW5jb3VudHJ5bWF0Y2giOiAiWWVzIiwgInNoaXBjaXR5cG9zdGFsbWF0Y2giOiAiTm8ifV19LCJyZXNwb25zZVN0YXR1cyI6eyJzdGF0dXMiOiJzdWNjZXNzIiwiZXJyb3JDb2RlIjoiMCIsImRlc2NyaXB0aW9uIjoiIn19Ijs=');
        $riskResponse = new RiskResponse($result);

        $this->assertEquals(
            [
                'billRiskCountry' => 'No',
                'cityPostalMatch' => 'Yes',
                'domainCountryMatch' => 'Yes',
                'shippingCityPostalMatch' => 'No',
                'shippingForward' => '',
            ],
            $riskResponse->addressInformation()
        );
    }

    /**
     * @test
     */
    public function it_parses_a_digital_identity_score_response()
    {
        $result = $this->unserialize('czo5Mzc6InsicXVlcnkiOnsiaXBhZGRyZXNzIjoiMTgxLjEzOC40Ny4xOTQiLCJxdWVyeVR5cGUiOiJJUFJpc2siLCJjb3VudCI6MSwiY3JlYXRlZCI6IjIwMTctMDYtMTBUMDA6MDA6MDhaIiwibGFuZyI6ImVuLVVTIiwicmVzcG9uc2VDb3VudCI6MSwicmVzdWx0cyI6W3siZGlzRGVzY3JpcHRpb24iOiJIaWdoIENvbmZpZGVuY2UiLCJvdmVyYWxsRGlnaXRhbElkZW50aXR5U2NvcmUiOjg3LCJiaWxsQWRkcmVzc1RvRnVsbE5hbWVDb25maWRlbmNlIjo2NiwiYmlsbEFkZHJlc3NUb0xhc3ROYW1lQ29uZmlkZW5jZSI6NTUsImVtYWlsVG9JcENvbmZpZGVuY2UiOjgwLCJlbWFpbFRvQmlsbEFkZHJlc3NDb25maWRlbmNlIjo3OCwgImVtYWlsVG9GdWxsTmFtZUNvbmZpZGVuY2UiOjg4LCJlbWFpbFRvTGFzdE5hbWVDb25maWRlbmNlIjo4OSwiZW1haWxUb1Bob25lQ29uZmlkZW5jZSI6NDQsImVtYWlsVG9TaGlwQWRkcmVzc0NvbmZpZGVuY2UiOjc4LCJpcFRvQmlsbEFkZHJlc3NDb25maWRlbmNlIjo5MCwiaXBUb0Z1bGxOYW1lQ29uZmlkZW5jZSI6NDUsImlwVG9MYXN0TmFtZUNvbmZpZGVuY2UiOjU2LCJpcFRvUGhvbmVDb25maWRlbmNlIjo3MSwiaXBUb1NoaXBBZGRyZXNzQ29uZmlkZW5jZSI6MTAwLCJwaG9uZVRvQmlsbEFkZHJlc3NDb25maWRlbmNlIjoxMDAsInBob25lVG9GdWxsTmFtZUNvbmZpZGVuY2UiOjk3LCJwaG9uZVRvTGFzdE5hbWVDb25maWRlbmNlIjozNCwicGhvbmVUb1NoaXBBZGRyZXNzQ29uZmlkZW5jZSI6ODcsInNoaXBBZGRyZXNzVG9CaWxsQWRkcmVzc0NvbmZpZGVuY2UiOjc3LCJzaGlwQWRkcmVzc1RvRnVsbE5hbWVDb25maWRlbmNlIjo3OCwic2hpcEFkZHJlc3NUb0xhc3ROYW1lQ29uZmlkZW5jZSI6MzR9XX0sInJlc3BvbnNlU3RhdHVzIjp7InN0YXR1cyI6InN1Y2Nlc3MiLCJlcnJvckNvZGUiOiIwIiwiZGVzY3JpcHRpb24iOiIifX0iOw==');
        $riskResponse = new RiskResponse($result);

        $this->assertEquals(
            [
                'description' => 'High Confidence',
                'overallScore' => 87,
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
            ],
            $riskResponse->digitalIdentityScoreInformation()
        );
    }

    /**
     * @test
     */
    public function it_parses_an_ip_risk_response()
    {
        $result = $this->unserialize('czo0MTY6InsicXVlcnkiOnsiaXBhZGRyZXNzIjoiMTgxLjEzOC40Ny4xOTQiLCJxdWVyeVR5cGUiOiJJUFJpc2siLCJjb3VudCI6MSwiY3JlYXRlZCI6IjIwMTctMDYtMTBUMDA6MDA6MDhaIiwibGFuZyI6ImVuLVVTIiwicmVzcG9uc2VDb3VudCI6MSwicmVzdWx0cyI6W3siaXBhZGRyZXNzIjogIjE4MS4xMzguNDcuMTk0IiwgImlwX3Jpc2tsZXZlbGlkIjogIjMiLCAiaXBfcmlza2xldmVsIjogIk1vZGVyYXRlIiwgImlwX3Jpc2tyZWFzb25pZCI6ICIzMTEiLCAiaXBfcmlza3JlYXNvbiI6ICJNb2RlcmF0ZSBCeSBQcm94eSBSZXB1dGF0aW9uIEFuZCBDb3VudHJ5IENvZGUiLCAiaXByaXNrY291bnRyeSI6ICIifV19LCJyZXNwb25zZVN0YXR1cyI6eyJzdGF0dXMiOiJzdWNjZXNzIiwiZXJyb3JDb2RlIjoiMCIsImRlc2NyaXB0aW9uIjoiIn19Ijs=');
        $riskResponse = new RiskResponse($result);

        $this->assertEquals(
            [
                'level' => '3',
                'levelMessage' => 'Moderate',
                'reasonId' => '311',
                'reason' => 'Moderate By Proxy Reputation And Country Code',
                'riskCountry' => '',
            ],
            $riskResponse->ipRiskInformation()
        );
    }

    /**
     * @test
     */
    public function it_parses_an_ip_location_response()
    {
        $result = $this->unserialize('czo3NDk6InsicXVlcnkiOnsiaXBhZGRyZXNzIjoiMTgxLjEzOC40Ny4xOTQiLCJxdWVyeVR5cGUiOiJJUFJpc2siLCJjb3VudCI6MSwiY3JlYXRlZCI6IjIwMTctMDYtMTBUMDA6MDA6MDhaIiwibGFuZyI6ImVuLVVTIiwicmVzcG9uc2VDb3VudCI6MSwicmVzdWx0cyI6W3siaXBhZGRyZXNzIjogIjE4MS4xMzguNDcuMTk0IiwiaXBfY2FsbGluZ2NvZGUiOiAiOTA3IiwgImlwX2NpdHkiOiAiZmFpcmJhbmtzIiwgImlwX2NpdHljb25mIjogIjk1IiwgImlwX2NvbnRpbmVudENvZGUiOiAibmEiLCAiaXBfY291bnRyeSI6ICJDb2xvbWJpYSIsICJpcF9jb3VudHJ5Q29kZSI6ICJDTyIsICJpcF9jb3VudHJ5Y29uZiI6ICI5OSIsICJpcGRpc3RhbmNlbWlsIjogIjUzNjYiLCAiaXBkaXN0YW5jZWttIjogIjg2MzciLCAiaXBfbGF0aXR1ZGUiOiAiNjQuODM2MyIsICJpcF9sb25naXR1ZGUiOiAiLTE0Ny43MTUiLCAiaXBfbWFwIjogImh0dHBzOi8vYXBwLmVtYWlsYWdlLmNvbS9xdWVyeS9Hb29nbGVNYXBzP2xhdExuZz02NC44MzYzLC0xNDcuNzE1IiwgImlwX21ldHJvQ29kZSI6ICI3NDUiLCAiaXBfcmVnaW9uIjogImxhdGluYW1lcmljYSIsICJpcF9yZWdpb25jb25mIjogIjk5IiwgImlwX3Bvc3RhbGNvZGUiOiAiOTk3MDEiLCAiaXBfcG9zdGFsY29uZiI6ICI1MCIsICJpcHRpbWV6b25lIjogIi01MDAifV19LCJyZXNwb25zZVN0YXR1cyI6eyJzdGF0dXMiOiJzdWNjZXNzIiwiZXJyb3JDb2RlIjoiMCIsImRlc2NyaXB0aW9uIjoiIn19Ijs=');
        $riskResponse = new RiskResponse($result);

        $this->assertEquals(
            [
                'callingCode' => '907',
                'city' => 'fairbanks',
                'cityConfidence' => '95',
                'continentCode' => 'na',
                'country' => 'Colombia',
                'countryCode' => 'CO',
                'countryConfidence' => '99',
                'distanceMil' => '5366',
                'distanceKm' => '8637',
                'latitude' => '64.8363',
                'longitude' => '-147.715',
                'map' => 'https://app.emailage.com/query/GoogleMaps?latLng=64.8363,-147.715',
                'metroCode' => '745',
                'region' => 'latinamerica',
                'regionConfidence' => '99',
                'postalCode' => '99701',
                'postalConfidence' => '50',
                'timeZone' => '-500',
            ],
            $riskResponse->ipLocationInformation()
        );
    }

    /**
     * @test
     */
    public function it_parses_an_email_owner_response()
    {
        $result = $this->unserialize('czoyNzA6InsicXVlcnkiOnsiaXBhZGRyZXNzIjoiMTgxLjEzOC40Ny4xOTQiLCJxdWVyeVR5cGUiOiJJUFJpc2siLCJjb3VudCI6MSwiY3JlYXRlZCI6IjIwMTctMDYtMTBUMDA6MDA6MDhaIiwibGFuZyI6ImVuLVVTIiwicmVzcG9uc2VDb3VudCI6MSwicmVzdWx0cyI6W3siZU5hbWUiOiAiVGVzdCBFQSIsICJsb2NhdGlvbiI6ICIiLCAidGl0bGUiOiAiIn1dfSwicmVzcG9uc2VTdGF0dXMiOnsic3RhdHVzIjoic3VjY2VzcyIsImVycm9yQ29kZSI6IjAiLCJkZXNjcmlwdGlvbiI6IiJ9fSI7');
        $riskResponse = new RiskResponse($result);

        $this->assertEquals(
            [
                'eName' => 'Test EA',
                'location' => '',
                'title' => '',
            ],
            $riskResponse->ownerInformation()
        );
    }

    /**
     * @test
     */
    public function it_parses_a_phone_response()
    {
        $result = $this->unserialize('czo0MzM6InsicXVlcnkiOnsiaXBhZGRyZXNzIjoiMTgxLjEzOC40Ny4xOTQiLCJxdWVyeVR5cGUiOiJJUFJpc2siLCJjb3VudCI6MSwiY3JlYXRlZCI6IjIwMTctMDYtMTBUMDA6MDA6MDhaIiwibGFuZyI6ImVuLVVTIiwicmVzcG9uc2VDb3VudCI6MSwicmVzdWx0cyI6W3sicGhvbmVjYXJyaWVybmFtZSI6ICJWZXJpem9uIFdpcmVsZXNzIiwgInBob25lY2FycmllcnR5cGUiOiAibW9iaWxlIiwgImN1c3RwaG9uZUluYmlsbGluZ2xvYyI6ICJOb3QgRm91bmQiLCAicGhvbmVvd25lciI6ICJUZXN0IEVBIiwgInBob25lb3duZXJtYXRjaCI6ICJZIiwgInBob25lb3duZXJ0eXBlIjogIkNPTlNVTUVSIiwgInBob25lX3N0YXR1cyI6ICJWQUxJRCJ9XX0sInJlc3BvbnNlU3RhdHVzIjp7InN0YXR1cyI6InN1Y2Nlc3MiLCJlcnJvckNvZGUiOiIwIiwiZGVzY3JpcHRpb24iOiIifX0iOw==');
        $riskResponse = new RiskResponse($result);

        $this->assertEquals(
            [
                'carrierName' => 'Verizon Wireless',
                'carrierType' => 'mobile',
                'inBillingLocation' => 'Not Found',
                'owner' => 'Test EA',
                'ownerMatch' => 'Y',
                'ownerType' => 'CONSUMER',
                'status' => 'VALID',
            ],
            $riskResponse->phoneInformation()
        );
    }

    /**
     * @test
     */
    public function it_parses_a_social_media_response()
    {
        $result = $this->unserialize('czozMjE6InsicXVlcnkiOnsiaXBhZGRyZXNzIjoiMTgxLjEzOC40Ny4xOTQiLCJxdWVyeVR5cGUiOiJJUFJpc2siLCJjb3VudCI6MSwiY3JlYXRlZCI6IjIwMTctMDYtMTBUMDA6MDA6MDhaIiwibGFuZyI6ImVuLVVTIiwicmVzcG9uc2VDb3VudCI6MSwicmVzdWx0cyI6W3sic21saW5rcyI6W3sic291cmNlIjogIkdvb2dsZVBsdXMiLCAibGluayI6ICJodHRwczovL3BsdXMuZ29vZ2xlLmNvbS8xMDg4NjAxOCJ9XSwgInNtZnJpZW5kcyI6ICIyIn1dfSwicmVzcG9uc2VTdGF0dXMiOnsic3RhdHVzIjoic3VjY2VzcyIsImVycm9yQ29kZSI6IjAiLCJkZXNjcmlwdGlvbiI6IiJ9fSI7');
        $riskResponse = new RiskResponse($result);

        $this->assertEquals(
            [
                'smFriends' => 2,
                'smLinks' => [
                    [
                        'source' => 'GooglePlus',
                        'link' => 'https://plus.google.com/10886018',
                    ],
                ],
            ],
            $riskResponse->socialMediaInformation()
        );
    }

    /**
     * @test
     */
    public function it_parses_a_bad_call()
    {
        $this->expectException(EmailageValidatorException::class);
        $this->expectExceptionCode(3001);

        $result = $this->unserialize('czozMTY6Iu+7v3sicXVlcnkiOnsiaXBhZGRyZXNzIjoiMTgxLjEzOC40Ny4xOTQiLCJxdWVyeVR5cGUiOiJJUFJpc2siLCJjb3VudCI6MSwiY3JlYXRlZCI6IjIwMTctMDYtMTBUMDA6MDk6MDFaIiwibGFuZyI6ImVuLVVTIiwicmVzcG9uc2VDb3VudCI6MCwicmVzdWx0cyI6W119LCJyZXNwb25zZVN0YXR1cyI6eyJzdGF0dXMiOiJmYWlsZWQiLCJlcnJvckNvZGUiOiIzMDAxIiwiZGVzY3JpcHRpb24iOiJBdXRoZW50aWNhdGlvbiBFcnJvcjogVGhlIHNpZ25hdHVyZSBkb2VzIG5vdCBtYXRjaCBvciB0aGUgdXNlci9jb25zdW1lciBrZXkgd2FzIG5vdCBmb3VuZC4ifX0iOw==');
        new RiskResponse($result);
    }

    /**
     * @test
     */
    public function it_handles_a_non_json_response()
    {
        $this->expectException(EmailageValidatorException::class);
        $this->expectExceptionCode(500);

        new RiskResponse('<i>Some generated HTML stuff on error</i>');
    }
}

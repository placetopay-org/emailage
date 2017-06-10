<?php


class ResponseTest extends BaseTestCase
{

    public function testItParsesABasicResponse()
    {
        $result = $this->unserialize('czoxMjUwOiLvu797InF1ZXJ5Ijp7ImVtYWlsIjoicHJ1ZWJhc3AycDIwMTYlNDBnbWFpbC5jb20iLCJxdWVyeVR5cGUiOiJFbWFpbEFnZVZlcmlmaWNhdGlvbiIsImNvdW50IjoxLCJjcmVhdGVkIjoiMjAxNy0wNi0wOVQyMzo1ODoyNloiLCJsYW5nIjoiZW4tVVMiLCJyZXNwb25zZUNvdW50IjoxLCJyZXN1bHRzIjpbeyJlbWFpbCI6InBydWViYXNwMnAyMDE2JTQwZ21haWwuY29tIiwiZU5hbWUiOiIiLCJlbWFpbEFnZSI6IiIsImRvbWFpbkFnZSI6IjE5OTUtMDgtMTNUMDc6MDA6MDBaIiwiZmlyc3RWZXJpZmljYXRpb25EYXRlIjoiMjAxNy0wNi0wOVQyMzo1ODoyNloiLCJsYXN0VmVyaWZpY2F0aW9uRGF0ZSI6IjIwMTctMDYtMDlUMjM6NTg6MjZaIiwic3RhdHVzIjoiVmFsaWREb21haW4iLCJjb3VudHJ5IjoiVVMiLCJmcmF1ZFJpc2siOiI1MDAgTW9kZXJhdGUiLCJFQVNjb3JlIjoiNTAwIiwiRUFSZWFzb24iOiJMaW1pdGVkIEhpc3RvcnkgZm9yIEVtYWlsIiwiRUFTdGF0dXNJRCI6IjQiLCJFQVJlYXNvbklEIjoiOCIsIkVBQWR2aWNlSUQiOiI0IiwiRUFBZHZpY2UiOiJNb2RlcmF0ZSBGcmF1ZCBSaXNrIiwiRUFSaXNrQmFuZElEIjoiMyIsIkVBUmlza0JhbmQiOiJGcmF1ZCBTY29yZSAzMDEgdG8gNjAwIiwic291cmNlX2luZHVzdHJ5IjoiIiwiZnJhdWRfdHlwZSI6IiIsImxhc3RmbGFnZ2Vkb24iOiIiLCJkb2IiOiIiLCJnZW5kZXIiOiIiLCJsb2NhdGlvbiI6IiIsInNtZnJpZW5kcyI6IiIsInRvdGFsaGl0cyI6IjEiLCJ1bmlxdWVoaXRzIjoiMSIsImltYWdldXJsIjoiIiwiZW1haWxFeGlzdHMiOiJOb3QgU3VyZSIsImRvbWFpbkV4aXN0cyI6IlllcyIsImNvbXBhbnkiOiIiLCJ0aXRsZSI6IiIsImRvbWFpbm5hbWUiOiJnbWFpbC5jb20iLCJkb21haW5jb21wYW55IjoiR29vZ2xlIiwiZG9tYWluY291bnRyeW5hbWUiOiJVbml0ZWQrU3RhdGVzIiwiZG9tYWluY2F0ZWdvcnkiOiJXZWJtYWlsIiwiZG9tYWluY29ycG9yYXRlIjoiTm8iLCJkb21haW5yaXNrbGV2ZWwiOiJNb2RlcmF0ZSIsImRvbWFpbnJlbGV2YW50aW5mbyI6IlZhbGlkIFdlYm1haWwgRG9tYWluIGZyb20gVW5pdGVkIFN0YXRlcyIsImRvbWFpbnJpc2tsZXZlbElEIjoiMyIsImRvbWFpbnJlbGV2YW50aW5mb0lEIjoiNTA4Iiwic21saW5rcyI6W10sInBob25lX3N0YXR1cyI6IiIsInNoaXBmb3J3YXJkIjoiIn1dfSwicmVzcG9uc2VTdGF0dXMiOnsic3RhdHVzIjoic3VjY2VzcyIsImVycm9yQ29kZSI6IjAiLCJkZXNjcmlwdGlvbiI6IiJ9fSI7');
        $riskResponse = new \PlacetoPay\Emailage\Messages\RiskResponse($result);

        $this->assertTrue($riskResponse->isSuccessful());
        $this->assertEquals(0, $riskResponse->errorCode());
        $this->assertEquals('', $riskResponse->errorMessage());
        $this->assertEquals('pruebasp2p2016@gmail.com', $riskResponse->query());
        $this->assertEquals(500, $riskResponse->score());
        $this->assertEquals(3, $riskResponse->riskBand());
        $this->assertEquals(4, $riskResponse->riskStatus());
        $this->assertEquals(8, $riskResponse->riskReason());
        $this->assertEquals(4, $riskResponse->riskAdvice());
        $this->assertEquals('Limited History for Email', $riskResponse->riskReasonMessage());
        $this->assertEquals('Moderate Fraud Risk', $riskResponse->riskAdviceMessage());
        $this->assertNull($riskResponse->ipRiskLevel());
    }

    public function testItParsesAEmailAndIpResponse()
    {
        $result = $this->unserialize('czoyMDIxOiLvu797InF1ZXJ5Ijp7ImVtYWlsIjoicHJ1ZWJhc3AycDIwMTYlNDBnbWFpbC5jb20lMmIxODEuMTM4LjQ3LjE5NCIsInF1ZXJ5VHlwZSI6IkVtYWlsSVBSaXNrIiwiY291bnQiOjEsImNyZWF0ZWQiOiIyMDE3LTA2LTA5VDIzOjU5OjE2WiIsImxhbmciOiJlbi1VUyIsInJlc3BvbnNlQ291bnQiOjEsInJlc3VsdHMiOlt7ImVtYWlsIjoicHJ1ZWJhc3AycDIwMTYlNDBnbWFpbC5jb20iLCJpcGFkZHJlc3MiOiIxODEuMTM4LjQ3LjE5NCIsImVOYW1lIjoiIiwiZW1haWxBZ2UiOiIiLCJkb21haW5BZ2UiOiIxOTk1LTA4LTEzVDA3OjAwOjAwWiIsImZpcnN0VmVyaWZpY2F0aW9uRGF0ZSI6IjIwMTctMDYtMDlUMjM6NTk6MTZaIiwibGFzdFZlcmlmaWNhdGlvbkRhdGUiOiIyMDE3LTA2LTA5VDIzOjU5OjE2WiIsInN0YXR1cyI6IlZhbGlkRG9tYWluIiwiY291bnRyeSI6IlVTIiwiZnJhdWRSaXNrIjoiNTAwIE1vZGVyYXRlIiwiRUFTY29yZSI6IjUwMCIsIkVBUmVhc29uIjoiTGltaXRlZCBIaXN0b3J5IGZvciBFbWFpbCIsIkVBU3RhdHVzSUQiOiI0IiwiRUFSZWFzb25JRCI6IjgiLCJFQUFkdmljZUlEIjoiNCIsIkVBQWR2aWNlIjoiTW9kZXJhdGUgRnJhdWQgUmlzayIsIkVBUmlza0JhbmRJRCI6IjMiLCJFQVJpc2tCYW5kIjoiRnJhdWQgU2NvcmUgMzAxIHRvIDYwMCIsInNvdXJjZV9pbmR1c3RyeSI6IiIsImZyYXVkX3R5cGUiOiIiLCJsYXN0ZmxhZ2dlZG9uIjoiIiwiZG9iIjoiIiwiZ2VuZGVyIjoiIiwibG9jYXRpb24iOiIiLCJzbWZyaWVuZHMiOiIiLCJ0b3RhbGhpdHMiOiIyIiwidW5pcXVlaGl0cyI6IjEiLCJpbWFnZXVybCI6IiIsImVtYWlsRXhpc3RzIjoiTm90IFN1cmUiLCJkb21haW5FeGlzdHMiOiJZZXMiLCJjb21wYW55IjoiIiwidGl0bGUiOiIiLCJkb21haW5uYW1lIjoiZ21haWwuY29tIiwiZG9tYWluY29tcGFueSI6Ikdvb2dsZSIsImRvbWFpbmNvdW50cnluYW1lIjoiVW5pdGVkK1N0YXRlcyIsImRvbWFpbmNhdGVnb3J5IjoiV2VibWFpbCIsImRvbWFpbmNvcnBvcmF0ZSI6Ik5vIiwiZG9tYWlucmlza2xldmVsIjoiTW9kZXJhdGUiLCJkb21haW5yZWxldmFudGluZm8iOiJWYWxpZCBXZWJtYWlsIERvbWFpbiBmcm9tIFVuaXRlZCBTdGF0ZXMiLCJkb21haW5yaXNrbGV2ZWxJRCI6IjMiLCJkb21haW5yZWxldmFudGluZm9JRCI6IjUwOCIsInNtbGlua3MiOltdLCJpcF9yaXNrbGV2ZWxpZCI6IjMiLCJpcF9yaXNrbGV2ZWwiOiJNb2RlcmF0ZSIsImlwX3Jpc2tyZWFzb25pZCI6IjMwMSIsImlwX3Jpc2tyZWFzb24iOiJNb2RlcmF0ZSBSaXNrIiwiaXBfcmVwdXRhdGlvbiI6Ikdvb2QiLCJpcF9hbm9ueW1vdXNkZXRlY3RlZCI6IiIsImlwX2lzcCI6IiIsImlwX29yZyI6IiIsImlwX3VzZXJUeXBlIjoiIiwiaXBfbmV0U3BlZWRDZWxsIjoiIiwiaXBfY29ycG9yYXRlUHJveHkiOiIiLCJpcF9jb250aW5lbnRDb2RlIjoiIiwiaXBfY291bnRyeSI6IiIsImlwX2NvdW50cnlDb2RlIjoiIiwiaXBfcmVnaW9uIjoiIiwiaXBfY2l0eSI6IiIsImlwX2NhbGxpbmdjb2RlIjoiIiwiaXBfbWV0cm9Db2RlIjoiIiwiaXBfbGF0aXR1ZGUiOiIiLCJpcF9sb25naXR1ZGUiOiIiLCJpcF9tYXAiOiIiLCJpcGNvdW50cnltYXRjaCI6IiIsImlwcmlza2NvdW50cnkiOiIiLCJpcGRpc3RhbmNla20iOiIiLCJpcGRpc3RhbmNlbWlsIjoiIiwiaXBhY2N1cmFjeXJhZGl1cyI6IiIsImlwdGltZXpvbmUiOiIiLCJpcGFzbnVtIjoiIiwiaXBkb21haW4iOiIiLCJpcF9jb3VudHJ5Y29uZiI6IiIsImlwX3JlZ2lvbmNvbmYiOiIiLCJpcF9jaXR5Y29uZiI6IiIsImlwX3Bvc3RhbGNvZGUiOiIiLCJpcF9wb3N0YWxjb25mIjoiIiwiaXBfcmlza3Njb3JlIjoiIiwiY3VzdHBob25lSW5iaWxsaW5nbG9jIjoiIiwiY2l0eXBvc3RhbG1hdGNoIjoiIiwic2hpcGNpdHlwb3N0YWxtYXRjaCI6IiIsInBob25lX3N0YXR1cyI6IiIsInNoaXBmb3J3YXJkIjoiIn1dfSwicmVzcG9uc2VTdGF0dXMiOnsic3RhdHVzIjoic3VjY2VzcyIsImVycm9yQ29kZSI6IjAiLCJkZXNjcmlwdGlvbiI6IiJ9fSI7');
        $riskResponse = new \PlacetoPay\Emailage\Messages\RiskResponse($result);

        $this->assertTrue($riskResponse->isSuccessful());
        $this->assertEquals(0, $riskResponse->errorCode());
        $this->assertEquals('', $riskResponse->errorMessage());
        $this->assertEquals('pruebasp2p2016@gmail.com+181.138.47.194', $riskResponse->query());
        $this->assertEquals(500, $riskResponse->score());
        $this->assertEquals(3, $riskResponse->riskBand());
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
        ], $riskResponse->ipInformation());
    }

    public function testItParsesAnIpResponse()
    {
        $result = $this->unserialize('czoxMDIzOiLvu797InF1ZXJ5Ijp7ImlwYWRkcmVzcyI6IjE4MS4xMzguNDcuMTk0IiwicXVlcnlUeXBlIjoiSVBSaXNrIiwiY291bnQiOjEsImNyZWF0ZWQiOiIyMDE3LTA2LTEwVDAwOjAwOjA4WiIsImxhbmciOiJlbi1VUyIsInJlc3BvbnNlQ291bnQiOjEsInJlc3VsdHMiOlt7ImlwYWRkcmVzcyI6IjE4MS4xMzguNDcuMTk0IiwiaXBfcmlza2xldmVsaWQiOiIzIiwiaXBfcmlza2xldmVsIjoiTW9kZXJhdGUiLCJpcF9yaXNrcmVhc29uaWQiOiIzMDEiLCJpcF9yaXNrcmVhc29uIjoiTW9kZXJhdGUgUmlzayIsImlwX3JlcHV0YXRpb24iOiJHb29kIiwiaXBfYW5vbnltb3VzZGV0ZWN0ZWQiOiIiLCJpcF9pc3AiOiIiLCJpcF9vcmciOiIiLCJpcF91c2VyVHlwZSI6IiIsImlwX25ldFNwZWVkQ2VsbCI6IiIsImlwX2NvcnBvcmF0ZVByb3h5IjoiIiwiaXBfY29udGluZW50Q29kZSI6IiIsImlwX2NvdW50cnkiOiIiLCJpcF9jb3VudHJ5Q29kZSI6IiIsImlwX3JlZ2lvbiI6IiIsImlwX2NpdHkiOiIiLCJpcF9jYWxsaW5nY29kZSI6IiIsImlwX21ldHJvQ29kZSI6IiIsImlwX2xhdGl0dWRlIjoiIiwiaXBfbG9uZ2l0dWRlIjoiIiwiaXBfbWFwIjoiIiwiaXBjb3VudHJ5bWF0Y2giOiIiLCJpcHJpc2tjb3VudHJ5IjoiIiwiaXBkaXN0YW5jZWttIjoiIiwiaXBkaXN0YW5jZW1pbCI6IiIsImlwYWNjdXJhY3lyYWRpdXMiOiIiLCJpcHRpbWV6b25lIjoiIiwiaXBhc251bSI6IiIsImlwZG9tYWluIjoiIiwiaXBfY291bnRyeWNvbmYiOiIiLCJpcF9yZWdpb25jb25mIjoiIiwiaXBfY2l0eWNvbmYiOiIiLCJpcF9wb3N0YWxjb2RlIjoiIiwiaXBfcG9zdGFsY29uZiI6IiIsImlwX3Jpc2tzY29yZSI6IiIsImN1c3RwaG9uZUluYmlsbGluZ2xvYyI6IiIsImNpdHlwb3N0YWxtYXRjaCI6IiIsInNoaXBjaXR5cG9zdGFsbWF0Y2giOiIiLCJwaG9uZV9zdGF0dXMiOiIiLCJzaGlwZm9yd2FyZCI6IiJ9XX0sInJlc3BvbnNlU3RhdHVzIjp7InN0YXR1cyI6InN1Y2Nlc3MiLCJlcnJvckNvZGUiOiIwIiwiZGVzY3JpcHRpb24iOiIifX0iOw==');
        $riskResponse = new \PlacetoPay\Emailage\Messages\RiskResponse($result);

        $this->assertTrue($riskResponse->isSuccessful());
        $this->assertEquals(0, $riskResponse->errorCode());
        $this->assertEquals('', $riskResponse->errorMessage());
        $this->assertEquals('181.138.47.194', $riskResponse->query());
        $this->assertNull($riskResponse->score());
        $this->assertNull($riskResponse->riskBand());
        $this->assertNull($riskResponse->riskStatus());
        $this->assertNull($riskResponse->riskReason());
        $this->assertNull($riskResponse->riskAdvice());
        $this->assertNull($riskResponse->riskReasonMessage());
        $this->assertNull($riskResponse->riskAdviceMessage());
        $this->assertEquals(3, $riskResponse->ipRiskLevel());
        $this->assertEquals('Moderate', $riskResponse->ipRiskLevelMessage());
        $this->assertEquals([
            'ip' => '181.138.47.194',
            'isp' => '',
            'country' => '',
            'region' => '',
            'city' => '',
            'latitude' => '',
            'longitude' => '',
        ], $riskResponse->ipInformation());
    }

    public function testItParsesAEmailFlagResponse()
    {
        $result = $this->unserialize('czoyMjM6Iu+7v3sicXVlcnkiOnsiZW1haWwiOiJwcnVlYmFzcDJwJTQwZ21haWwuY29tIiwiZmxhZyI6ImZyYXVkIiwiZnJhdWRjb2RlSUQiOiI3IiwicXVlcnlUeXBlIjoiRW1haWxGbGFnIiwiY3JlYXRlZCI6IjIwMTctMDYtMTBUMDA6MDA6NDVaIiwibGFuZyI6ImVuLVVTIn0sInJlc3BvbnNlU3RhdHVzIjp7InN0YXR1cyI6InN1Y2Nlc3MiLCJlcnJvckNvZGUiOiIwIiwiZGVzY3JpcHRpb24iOiIifX0iOw==');
        $response = new \PlacetoPay\Emailage\Messages\FlagResponse($result);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('7', $response->flagReason());
    }

    public function testItParsesABadCall()
    {
        $result = $this->unserialize('czozMTY6Iu+7v3sicXVlcnkiOnsiaXBhZGRyZXNzIjoiMTgxLjEzOC40Ny4xOTQiLCJxdWVyeVR5cGUiOiJJUFJpc2siLCJjb3VudCI6MSwiY3JlYXRlZCI6IjIwMTctMDYtMTBUMDA6MDk6MDFaIiwibGFuZyI6ImVuLVVTIiwicmVzcG9uc2VDb3VudCI6MCwicmVzdWx0cyI6W119LCJyZXNwb25zZVN0YXR1cyI6eyJzdGF0dXMiOiJmYWlsZWQiLCJlcnJvckNvZGUiOiIzMDAxIiwiZGVzY3JpcHRpb24iOiJBdXRoZW50aWNhdGlvbiBFcnJvcjogVGhlIHNpZ25hdHVyZSBkb2VzIG5vdCBtYXRjaCBvciB0aGUgdXNlci9jb25zdW1lciBrZXkgd2FzIG5vdCBmb3VuZC4ifX0iOw==');
        $response = new \PlacetoPay\Emailage\Messages\RiskResponse($result);

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('IPRisk', $response->queryType());
        $this->assertEquals(3001, $response->errorCode());
        $this->assertEquals('Authentication Error: The signature does not match or the user/consumer key was not found.', $response->errorMessage());
    }
}
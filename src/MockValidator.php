<?php


namespace PlacetoPay\Emailage;


use PlacetoPay\Emailage\Messages\RiskResponse;

class MockValidator extends Validator
{

    public function query($email, $parameters = [])
    {
        $data = explode('+', $email);
        $email = $data[0];

        preg_match('/dnetix(\\d+)\\@yopmail.com/', $email, $matches);

        if ($matches && isset($matches[1])) {
            return new RiskResponse(json_encode([
                'query' => [
                    'email' => $email,
                    'queryType' => 'EmailAgeVerification',
                    'count' => 1,
                    'created' => date('c'),
                    'lang' => 'en-US',
                    'responseCount' => 1,
                    'results' => [
                        [
                            'email' => $email,
                            'eName' => '',
                            'emailAge' => '',
                            'domainAge' => '1995-08-13T07:00:00Z',
                            'firstVerificationDate' => '2017-06-09T23:58:26Z',
                            'lastVerificationDate' => '2017-06-09T23:58:26Z',
                            'status' => 'ValidDomain',
                            'country' => 'US',
                            'fraudRisk' => $matches[1] . ' Moderate',
                            'EAScore' => $matches[1],
                            'EAReason' => 'Limited History for Email',
                            'EAStatusID' => '4',
                            'EAReasonID' => '8',
                            'EAAdviceID' => '4',
                            'EAAdvice' => 'Moderate Fraud Risk',
                            'EARiskBandID' => '3',
                            'EARiskBand' => 'Fraud Score 301 to 600',
                            'source_industry' => '',
                            'fraud_type' => '',
                            'lastflaggedon' => '',
                            'dob' => '',
                            'gender' => '',
                            'location' => '',
                            'smfriends' => '',
                            'totalhits' => '1',
                            'uniquehits' => '1',
                            'imageurl' => '',
                            'emailExists' => 'Not Sure',
                            'domainExists' => 'Yes',
                            'company' => '',
                            'title' => '',
                            'domainname' => 'yopmail.com',
                            'domaincompany' => 'Yopmail',
                            'domaincountryname' => 'United+States',
                            'domaincategory' => 'Webmail',
                            'domaincorporate' => 'No',
                            'domainrisklevel' => 'Moderate',
                            'domainrelevantinfo' => 'Valid Webmail Domain from United States',
                            'domainrisklevelID' => '3',
                            'domainrelevantinfoID' => '508',
                            'smlinks' => [],
                            'phone_status' => '',
                            'shipforward' => ''
                        ]
                    ]
                ],
                'responseStatus' => [
                    'status' => 'success',
                    'errorCode' => '0',
                    'description' => ''
                ]
            ]));
        } else {
            return new RiskResponse(json_encode([
                'query' => [
                    'email' => $email,
                    'queryType' => 'EmailAgeVerification',
                    'count' => 1,
                    'created' => date('c'),
                    'lang' => 'en-US',
                    'responseCount' => 1,
                    'results' => [
                        [
                            'email' => $email,
                            'eName' => '',
                            'emailAge' => '',
                            'domainAge' => '1995-08-13T07:00:00Z',
                            'firstVerificationDate' => '2017-06-09T23:58:26Z',
                            'lastVerificationDate' => '2017-06-09T23:58:26Z',
                            'status' => 'ValidDomain',
                            'country' => 'US',
                            'fraudRisk' => '500 Moderate',
                            'EAScore' => 500,
                            'EAReason' => 'Limited History for Email',
                            'EAStatusID' => 4,
                            'EAReasonID' => 8,
                            'EAAdviceID' => 4,
                            'EAAdvice' => 'Moderate Fraud Risk',
                            'EARiskBandID' => 3,
                            'EARiskBand' => 'Fraud Score 301 to 600',
                            'source_industry' => '',
                            'fraud_type' => '',
                            'lastflaggedon' => '',
                            'dob' => '',
                            'gender' => '',
                            'location' => '',
                            'smfriends' => '',
                            'totalhits' => '1',
                            'uniquehits' => '1',
                            'imageurl' => '',
                            'emailExists' => 'Not Sure',
                            'domainExists' => 'Yes',
                            'company' => '',
                            'title' => '',
                            'domainname' => 'yopmail.com',
                            'domaincompany' => 'Yopmail',
                            'domaincountryname' => 'United+States',
                            'domaincategory' => 'Webmail',
                            'domaincorporate' => 'No',
                            'domainrisklevel' => 'Moderate',
                            'domainrelevantinfo' => 'Valid Webmail Domain from United States',
                            'domainrisklevelID' => '3',
                            'domainrelevantinfoID' => '508',
                            'smlinks' => [],
                            'phone_status' => '',
                            'shipforward' => ''
                        ]
                    ]
                ],
                'responseStatus' => [
                    'status' => 'success',
                    'errorCode' => '0',
                    'description' => ''
                ]
            ]));
        }
    }

}
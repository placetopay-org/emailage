<?php

namespace PlacetoPay\Emailage\Messages;

class RiskResponse extends Message
{
    protected $result;

    public function __construct($result)
    {
        parent::__construct($result);

        if (isset($this->query['results']) && is_array($this->query['results']) && count($this->query['results']) > 0) {
            $this->result = $this->query['results'][0];
        }
    }

    protected function resultData($key)
    {
        if (isset($this->result[$key])) {
            return $this->result[$key];
        }
        return null;
    }

    /**
     * Calculated Score by the Emailage proprietary algorithm.
     * @return int
     */
    public function score()
    {
        return $this->resultData('EAScore');
    }

    /**
     * Returns a code that provides additional information to understand the score.
     * @return int
     */
    public function riskReason()
    {
        return $this->resultData('EAReasonID');
    }

    /**
     * Returns a translation for humans of the reason code.
     * @return string
     */
    public function riskReasonMessage()
    {
        return $this->resultData('EAReason');
    }

    /**
     * The possible values are
     *  0 - GeneralError
     *  1 - Certified
     *  2 - Verified
     *  3 - EmailNonxistent
     *  4 - ValidDomain
     *  5 - DomainInexistent.
     * @return int
     */
    public function riskStatus()
    {
        return $this->resultData('EAStatusID');
    }

    /**
     * Serves as a guideline based on the risk associated with the email address.
     * @return int
     */
    public function riskAdvice()
    {
        return $this->resultData('EAAdviceID');
    }

    /**
     * Parses for humans the advice code.
     * @return string
     */
    public function riskAdviceMessage()
    {
        return $this->resultData('EAAdvice');
    }

    /**
     * Customized risk level band according to the configuration provided on Emailage administration.
     * @return int
     */
    public function riskBand()
    {
        return $this->resultData('EARiskBandID');
    }

    /**
     * Provides the fraud risk for the IP Address, the values for this field are.
     * @deprecated
     *  1 - Very High
     *  2 - High
     *  3 - Moderate
     *  4 - Low
     *  5 - Very Low
     *  6 - Review
     * @see ipInformation[riskLevel]
     * @return int
     */
    public function ipRiskLevel()
    {
        return $this->resultData('ip_risklevelid');
    }

    /**
     * @deprecated
     * @see ipInformation[riskLevelMessage]
     * @return string
     */
    public function ipRiskLevelMessage()
    {
        return $this->resultData('ip_risklevel');
    }

    /**
     * Industry that reports the data.
     * @return string
     */
    public function sourceIndustry()
    {
        return $this->resultData('source_industry');
    }

    /**
     * Industry that reports the data.
     * @return string
     */
    public function lastFlaggedOn()
    {
        return $this->resultData('lastflaggedon');
    }

    /**
     * Define fraud type.
     * @return string
     */
    public function fraudType()
    {
        return $this->resultData('fraud_type');
    }

    /**
     * Groups information about the email.
     * @return array
     */
    public function emailInformation()
    {
        return [
            'email' => $this->resultData('email'),
            'age' => $this->resultData('emailAge'),
            'country' => $this->resultData('country'),
            'exists' => $this->resultData('emailExists'),
            'status' => $this->resultData('status'),
            'firstSeen' => $this->resultData('firstVerificationDate'),
            'lastSeen' => $this->resultData('lastVerificationDate'),
            'imageUrl' => $this->resultData('imageurl'),
            'hits' => $this->resultData('hits'),
            'uniqueHits' => $this->resultData('uniquehits'),
            'domain' => [
                'name' => $this->resultData('domainname'),
                'age' => $this->resultData('domainAge'),
                'category' => $this->resultData('domaincategory'),
                'corporate' => $this->resultData('domaincorporate'),
                'exists' => $this->resultData('domainExists'),
                'company' => $this->resultData('domaincompany'),
                'countryName' => $this->resultData('domaincountryname'),
                'riskLevel' => $this->resultData('domainrisklevelID'),
                'riskLevelMessage' => $this->resultData('domainrisklevel'),
                'relevantInfo' => $this->resultData('domainrelevantinfoID'),
                'relevantInfoMessage' => $this->resultData('domainrelevantinfo'),
            ],
        ];
    }

    /**
     * Groups information about the IP address.
     * @return array
     */
    public function ipInformation()
    {
        $ipaddress = $this->resultData('ipaddress');
        if (empty($ipaddress)) {
            return [];
        }

        return [
            'ip' => $this->resultData('ipaddress'),
            'riskLevel' => $this->resultData('ip_risklevelid'),
            'riskLevelMessage' => $this->resultData('ip_risklevel'),
            'isp' => $this->resultData('ip_isp'),
            'country' => $this->resultData('ip_countryCode'),
            'region' => $this->resultData('ip_region'),
            'city' => $this->resultData('ip_city'),
            'latitude' => $this->resultData('ip_latitude'),
            'longitude' => $this->resultData('ip_longitude'),
        ];
    }

    /**
     * Returns the whole first result of service response.
     * @return array
     */
    public function fullServiceResponse()
    {
        return $this->result;
    }
}

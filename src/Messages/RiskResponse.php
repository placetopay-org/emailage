<?php

namespace PlacetoPay\Emailage\Messages;

class RiskResponse extends Message
{
    protected $result;

    public function __construct($result)
    {
        parent::__construct($result);

        if ($this->responseHasMultipleResults()) {
            $this->result = $this->query['results'][0];
        }
    }

    /**
     * @return bool
     */
    private function responseHasMultipleResults()
    {
        return
            isset($this->query['results'])
            && is_array($this->query['results'])
            && count($this->query['results']) > 0;
    }

    protected function resultData($key)
    {
        if (! isset($this->result[$key])) {
            return null;
        }

        return $this->result[$key];
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
     * Returns the risk band description.
     * @return string|null
     */
    public function riskBandMessage()
    {
        return $this->resultData('EARiskBand');
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
     * The Digital Identity Score (DIS) overall score.
     * @return int
     */
    public function disOverallScore()
    {
        return $this->resultData('overallDigitalIdentityScore');
    }

    /**
     * The Digital Identity Score (DIS) overall score description.
     * @return int
     */
    public function disOverallScoreDescrition()
    {
        return $this->resultData('disDescription');
    }

    /**
     * Groups information about customer's shipping or billing address related fields.
     * @return array
     */
    public function addressInformation()
    {
        return [
            'billRiskCountry' => $this->resultData('billriskcountry'),
            'cityPostalMatch' => $this->resultData('citypostalmatch'),
            'domainCountryMatch' => $this->resultData('domaincountrymatch'),
            'shippingCityPostalMatch' => $this->resultData('shipcitypostalmatch'),
            'shippingForward' => $this->resultData('shipforward'),
        ];
    }

    /**
     * Groups information about Digital Identity Score related fields.
     * @return array
     */
    public function digitalIdentityScoreInformation()
    {
        return [
            'description' => $this->resultData('disDescription'),
            'overallScore' => $this->resultData('overallDigitalIdentityScore'),
            'billAddressToFullNameConfidence' => $this->resultData('billAddressToFullNameConfidence'),
            'billAddressToLastNameConfidence' => $this->resultData('billAddressToLastNameConfidence'),
            'emailToIpConfidence' => $this->resultData('emailToIpConfidence'),
            'emailToBillAddressConfidence' => $this->resultData('emailToBillAddressConfidence'),
            'emailToFullNameConfidence' => $this->resultData('emailToFullNameConfidence'),
            'emailToLastNameConfidence' => $this->resultData('emailToLastNameConfidence'),
            'emailToPhoneConfidence' => $this->resultData('emailToPhoneConfidence'),
            'emailToShipAddressConfidence' => $this->resultData('emailToShipAddressConfidence'),
            'ipToBillAddressConfidence' => $this->resultData('ipToBillAddressConfidence'),
            'ipToFullNameConfidence' => $this->resultData('ipToFullNameConfidence'),
            'ipToLastNameConfidence' => $this->resultData('ipToLastNameConfidence'),
            'ipToPhoneConfidence' => $this->resultData('ipToPhoneConfidence'),
            'ipToShipAddressConfidence' => $this->resultData('ipToShipAddressConfidence'),
            'phoneToBillAddressConfidence' => $this->resultData('phoneToBillAddressConfidence'),
            'phoneToFullNameConfidence' => $this->resultData('phoneToFullNameConfidence'),
            'phoneToLastNameConfidence' => $this->resultData('phoneToLastNameConfidence'),
            'phoneToShipAddressConfidence' => $this->resultData('phoneToShipAddressConfidence'),
            'shipAddressToBillAddressConfidence' => $this->resultData('shipAddressToBillAddressConfidence'),
            'shipAddressToFullNameConfidence' => $this->resultData('shipAddressToFullNameConfidence'),
            'shipAddressToLastNameConfidence' => $this->resultData('shipAddressToLastNameConfidence'),
        ];
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
            'company' => $this->resultData('company'),
            'exists' => $this->resultData('emailExists'),
            'status' => $this->resultData('status'),
            'firstSeen' => $this->resultData('firstVerificationDate'),
            'firstSeenDays' => $this->resultData('first_seen_days'),
            'lastSeen' => $this->resultData('lastVerificationDate'),
            'imageUrl' => $this->resultData('imageurl'),
            'hits' => $this->resultData('totalhits'),
            'uniqueHits' => $this->resultData('uniquehits'),
            'creationDays' => $this->resultData('email_creation_days'),
            'domain' => [
                'name' => $this->resultData('domainname'),
                'age' => $this->resultData('domainAge'),
                'category' => $this->resultData('domaincategory'),
                'corporate' => $this->resultData('domaincorporate'),
                'exists' => $this->resultData('domainExists'),
                'company' => $this->resultData('domaincompany'),
                'countryName' => $this->resultData('domaincountryname'),
                'riskLevel' => $this->resultData('domainrisklevelID'),
                'riskCountry' => $this->resultData('domainriskcountry'),
                'riskLevelMessage' => $this->resultData('domainrisklevel'),
                'relevantInfo' => $this->resultData('domainrelevantinfoID'),
                'relevantInfoMessage' => $this->resultData('domainrelevantinfo'),
                'creationDays' => $this->resultData('domain_creation_days'),
                'fraudRisk' => $this->resultData('fraudRisk'),
            ],
        ];
    }

    /**
     * Groups information about the IP address.
     * @return array
     */
    public function ipInformation()
    {
        if (empty($this->resultData('ipaddress'))) {
            return [];
        }

        return [
            'ip' => $this->resultData('ipaddress'),
            'anonymousDetected' => $this->resultData('ip_anonymousdetected'),
            'autonomousSystemNumber' => $this->resultData('ipasnum'),
            'city' => $this->resultData('ip_city'),
            'corporateProxy' => $this->resultData('ip_corporateProxy'),
            'country' => $this->resultData('ip_countryCode'),
            'countryMatch' => $this->resultData('ipcountrymatch'),
            'domain' => $this->resultData('ipdomain'),
            'isp' => $this->resultData('ip_isp'),
            'region' => $this->resultData('ip_region'),
            'latitude' => $this->resultData('ip_latitude'),
            'longitude' => $this->resultData('ip_longitude'),
            'netSpeed' => $this->resultData('ip_netSpeedCell'),
            'organization' => $this->resultData('ip_org'),
            'reputation' => $this->resultData('ip_reputation'),
            'userType' => $this->resultData('ip_userType'),
        ];
    }

    /**
     * Groups information about the IP and device risk related fields.
     * @return array
     */
    public function ipRiskInformation()
    {
        if (empty($this->resultData('ipaddress'))) {
            return [];
        }

        return [
            'level' => $this->resultData('ip_risklevelid'),
            'levelMessage' => $this->resultData('ip_risklevel'),
            'reasonId' => $this->resultData('ip_riskreasonid'),
            'reason' => $this->resultData('ip_riskreason'),
            'riskCountry' => $this->resultData('ipriskcountry'),
        ];
    }

    /**
     * Groups information about the IP location related fields.
     * @return array
     */
    public function ipLocationInformation()
    {
        if (empty($this->resultData('ipaddress'))) {
            return [];
        }

        return [
            'callingCode' => $this->resultData('ip_callingcode'),
            'city' => $this->resultData('ip_city'),
            'cityConfidence' => $this->resultData('ip_cityconf'),
            'continentCode' => $this->resultData('ip_continentCode'),
            'country' => $this->resultData('ip_country'),
            'countryCode' => $this->resultData('ip_countryCode'),
            'countryConfidence' => $this->resultData('ip_countryconf'),
            'distanceMil' => $this->resultData('ipdistancemil'),
            'distanceKm' => $this->resultData('ipdistancekm'),
            'latitude' => $this->resultData('ip_latitude'),
            'longitude' => $this->resultData('ip_longitude'),
            'map' => $this->resultData('ip_map'),
            'metroCode' => $this->resultData('ip_metroCode'),
            'region' => $this->resultData('ip_region'),
            'regionConfidence' => $this->resultData('ip_regionconf'),
            'postalCode' => $this->resultData('ip_postalcode'),
            'postalConfidence' => $this->resultData('ip_postalconf'),
            'timeZone' => $this->resultData('iptimezone'),
        ];
    }

    /**
     * Groups information about the email owner related fields.
     * @return array
     */
        public function ownerInformation()
    {
        return [
            'eName' => $this->resultData('eName'),
            'location' => $this->resultData('location'),
            'title' => $this->resultData('title'),
        ];
    }

    /**
     * Groups information about the phone related fields.
     * @return array
     */
    public function phoneInformation()
    {
        return [
            'carrierName' => $this->resultData('phonecarriername'),
            'carrierType' => $this->resultData('phonecarriertype'),
            'inBillingLocation' => $this->resultData('custphoneInbillingloc'),
            'owner' => $this->resultData('phoneowner'),
            'ownerMatch' => $this->resultData('phoneownermatch'),
            'ownerType' => $this->resultData('phoneownertype'),
            'status' => $this->resultData('phone_status'),
        ];
    }

    /**
     * Groups information about the email social media related fields.
     * @return array
     */
    public function socialMediaInformation()
    {
        return [
            'smFriends' => $this->resultData('smfriends'),
            'smLinks' => $this->resultData('smlinks'),
        ];
    }
}

<?php


namespace PlacetoPay\Emailage\Messages;


class RiskResponse extends Message
{
    protected $result;

    public function __construct($result)
    {
        parent::__construct($result);

        if (isset($this->query['results']) && is_array($this->query['results']) && sizeof($this->query['results']) > 0) {
            $this->result = $this->query['results'][0];
        }
    }

    protected function resultData($key)
    {
        if (isset($this->result[$key]))
            return $this->result[$key];
        return null;
    }

    public function score()
    {
        return $this->resultData('EAScore');
    }

    public function riskReason()
    {
        return $this->resultData('EAReasonID');
    }

    public function riskReasonMessage()
    {
        return $this->resultData('EAReason');
    }

    public function riskStatus()
    {
        return $this->resultData('EAStatusID');
    }

    public function riskAdvice()
    {
        return $this->resultData('EAAdviceID');
    }

    public function riskAdviceMessage()
    {
        return $this->resultData('EAAdvice');
    }

    public function riskBand()
    {
        return $this->resultData('EARiskBandID');
    }

    public function ipRiskLevel()
    {
        return $this->resultData('ip_risklevelid');
    }

    public function ipInformation()
    {
        return [
            'ip' => $this->resultData('ipaddress'),
            'isp' => $this->resultData('ip_isp'),
            'country' => $this->resultData('ip_countryCode'),
            'region' => $this->resultData('ip_region'),
            'city' => $this->resultData('ip_city'),
            'latitude' => $this->resultData('ip_latitude'),
            'longitude' => $this->resultData('ip_longitude'),
        ];
    }

}
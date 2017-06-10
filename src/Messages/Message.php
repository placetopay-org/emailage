<?php


namespace PlacetoPay\Emailage\Messages;


use PlacetoPay\Emailage\Exceptions\EmailageValidatorException;

abstract class Message
{

    protected $errorCode;
    protected $errorMessage;
    protected $query;
    protected $type;

    public function __construct($result)
    {
        $result = json_decode(urldecode(str_replace("\xEF\xBB\xBF", '', $result)), true);
        if (!$result) {
            throw new EmailageValidatorException('Emailage response cannot be parsed from JSON');
        }

        $this->errorCode = $result['responseStatus']['errorCode'];
        $this->errorMessage = $result['responseStatus']['description'];

        $this->query = $result['query'];
        $this->type = $this->query['queryType'];
    }

    /**
     * Returns true or false if the request could be performed
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->errorCode() == 0;
    }

    /**
     * Returns the email, ip or email+ip combination that was originally queried
     * @return string
     */
    public function query()
    {
        return isset($this->query['email']) ? $this->query['email'] : $this->query['ipaddress'];
    }

    /**
     * Returns the kind of query made to the platform
     * @return string
     */
    public function queryType()
    {
        return $this->type;
    }

    /**
     * Returns error code for the request if was successful it's 0
     * @return int
     */
    public function errorCode()
    {
        return $this->errorCode;
    }

    /**
     * Returns error message for the request if was successful it's empty
     * @return string
     */
    public function errorMessage()
    {
        return $this->errorMessage;
    }

}
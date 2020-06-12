<?php

namespace PlacetoPay\Emailage\Exceptions;

class EmailageValidatorException extends \Exception
{
    public static function forInvalidResult(string $content)
    {
        return new self('Result could not be parsed to JSON: ' . base64_encode(serialize($content)), 500);
    }

    public static function forErrorCode($code, $message)
    {
        return new self($message, $code);
    }

    public static function forNotProvidedAuthentication()
    {
        return new self('The account or token properties are not provided', 400);
    }
}

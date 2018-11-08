<?php
namespace Oauth2\Validators\Exceptions;

class InvalidContentTypeException extends \Exception
{
    /**
     * @var string
     */
    public $message = 'Invalid content type';

    /**
     * @var int
     */
    public $code = 400;
}
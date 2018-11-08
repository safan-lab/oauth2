<?php
namespace Oauth2\Validators\Exceptions;

class InvalidRequestParametersException extends \Exception
{
    /**
     * @var string
     */
    public $message = 'Invalid request parameters';

    /**
     * @var int
     */
    public $code = 400;
}
<?php
namespace Oauth2\Validators\Exceptions;

class InvalidRequestMethodException extends \Exception
{
    /**
     * @var string
     */
    public $message = 'Invalid request method';

    /**
     * @var int
     */
    public $code = 400;
}
<?php
namespace Oauth2\Validators\Exceptions;

class InvalidGrantTypeException extends \Exception
{
    /**
     * @var string
     */
    public $message = 'Invalid grant type';

    /**
     * @var int
     */
    public $code = 400;
}
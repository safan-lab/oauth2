<?php
namespace Oauth2\GrantTypes\Exceptions;

class InvalidCredentialsException extends \Exception
{
    /**
     * @var string
     */
    public $message = 'Invalid credentials';

    /**
     * @var int
     */
    public $code = 401;
}
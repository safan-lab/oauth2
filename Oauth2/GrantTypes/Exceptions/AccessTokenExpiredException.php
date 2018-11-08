<?php
namespace Oauth2\GrantTypes\Exceptions;

class AccessTokenExpiredException extends \Exception
{
    /**
     * @var string
     */
    public $message = 'Access token expired';

    /**
     * @var int
     */
    public $code = 401;
}
<?php
namespace Oauth2\GrantTypes\Exceptions;

class SystemErrorException extends \Exception
{
    /**
     * @var string
     */
    public $message = 'System error';

    /**
     * @var int
     */
    public $code = 500;
}
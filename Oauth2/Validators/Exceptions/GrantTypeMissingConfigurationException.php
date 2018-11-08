<?php
namespace Oauth2\Validators\Exceptions;

class GrantTypeMissingConfigurationException extends \Exception
{
    /**
     * @var string
     */
    public $message = 'Missing grant type configuration';

    /**
     * @var int
     */
    public $code = 400;
}
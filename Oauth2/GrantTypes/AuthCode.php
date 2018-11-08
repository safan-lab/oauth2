<?php
namespace Oauth2\GrantTypes;

use Symfony\Component\HttpFoundation\Response;

class AuthCode extends BaseGrantType
{
    /**
     * @throws \Exception
     */
    public function authorize()
    {
        throw new \Exception('Not supported :( coming soon', Response::HTTP_BAD_REQUEST);
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return 'authorization_code';
    }
}
<?php
namespace Oauth2\GrantTypes;

use Oauth2\GrantTypes\Exceptions\AccessTokenExpiredException;

class Login extends BaseGrantType
{
    /**
     * @throws \Exception
     */
    public function authorize()
    {
        $now = new \DateTime();

        $accessToken = $this->getAccessTokenRepository()
            ->getModel()
            ->where([
                'token' => $this->params['token']
            ])
            ->runOnce();

        if (is_null($accessToken) || $accessToken->expired->getTimestamp() < $now->getTimestamp()) {
            throw new AccessTokenExpiredException();
        }

        return [
            'expired' => $accessToken->expired->getTimestamp()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return 'login';
    }
}
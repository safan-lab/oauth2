<?php
namespace Oauth2\GrantTypes;

use Oauth2\GrantTypes\Exceptions\InvalidCredentialsException;
use Oauth2\GrantTypes\Exceptions\SystemErrorException;
use Safan\Safan;

class Password extends BaseGrantType
{
    /**
     * @throws \Exception
     */
    public function authorize()
    {
        Safan::handler()->getObjectManager()->get('eventListener')->runEvent('preAccessTokenRequest');
        $authentication = Safan::handler()->getObjectManager()->get('authentication');

        $client = $this->getAuthClientRepository()
            ->getByParams([
                'clientID'     => $this->params['client_id'],
                'clientSecret' => $this->params['client_secret']
            ]);

        $res = $authentication->login($this->params['username'], $this->params['password']);

        if (!$res || is_null($client)) {
            Safan::handler()->getObjectManager()->get('eventListener')->runEvent('failAccessTokenRequest');

            throw new InvalidCredentialsException();
        }

        $client->userID = Safan::handler()->getObjectManager()->get('authentication')->getUserID();

        try {
            $this->getAuthClientRepository()->getModel()->beginTransaction();

            $accessToken = $this->createAccessToken($client);

            Safan::handler()->getObjectManager()->get('eventListener')->runEvent('successAccessTokenRequest');

            $this->getAuthClientRepository()->getModel()->commitTransaction();

            return [
                "access_token"  => $accessToken->token,
                "expires_in"    => $accessToken->expired->getTimestamp(),
                "token_type"    => "Bearer",
                "scope"         => $client->allowedScopes,
            ];
        } catch (\Exception $e) {
            $this->getAuthClientRepository()->getModel()->rollbackTransaction();

            throw new SystemErrorException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return 'password';
    }
}
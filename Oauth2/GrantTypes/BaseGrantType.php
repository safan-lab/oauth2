<?php
namespace Oauth2\GrantTypes;

use Oauth2\Models\BaseAccessToken;
use Oauth2\Models\BaseAuthClient;
use Oauth2\Models\BaseRefreshToken;
use Oauth2\Models\Repositories\BaseAccessTokenRepository;
use Oauth2\Models\Repositories\BaseAuthClientRepository;
use Oauth2\Models\Repositories\BaseRefreshTokenRepository;
use Safan\Safan;

abstract class BaseGrantType
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @return mixed
     */
    abstract public function authorize();

    /**
     * ClientCredential constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return BaseAuthClientRepository
     */
    protected function getAuthClientRepository()
    {
        return Safan::handler()
            ->getObjectManager()
            ->get('gapOrmCache')
            ->getRepository(
                BaseAuthClient::class,
                BaseAuthClientRepository::class
            );
    }

    /**
     * @return BaseAccessTokenRepository
     */
    protected function getAccessTokenRepository()
    {
        return Safan::handler()
            ->getObjectManager()
            ->get('gapOrmCache')
            ->getRepository(
                BaseAccessToken::class,
                BaseAccessTokenRepository::class
            );
    }

    /**
     * @return BaseRefreshTokenRepository
     */
    protected function getRefreshTokenRepository()
    {
        return Safan::handler()
            ->getObjectManager()
            ->get('gapOrmCache')
            ->getRepository(
                BaseRefreshToken::class,
                BaseRefreshTokenRepository::class
            );
    }

    /**
     * @param $client
     * @return mixed
     */
    protected function createAccessToken($client): \stdClass
    {
        $accessTokenExpired = new \DateTime();
        $accessTokenExpired->modify('+2 days');

        $accessToken = $this->getAccessTokenRepository()->getModel()->getEmptyObject();
        $accessToken->clientID = $this->params['client_id'];
        $accessToken->token    = $this->createRandomToken();
        $accessToken->expired  = $accessTokenExpired;
        $accessToken->scope    = $client->allowedScopes;
        $this->getAccessTokenRepository()->getModel()->save($accessToken);

        return $accessToken;
    }

    /**
     * @param $client
     * @return mixed
     */
    protected function createRefreshToken($client): \stdClass
    {
        $refreshTokenExpired = new \DateTime();
        $refreshTokenExpired->modify('+1 month');

        $refreshToken = $this->getRefreshTokenRepository()->getModel()->getEmptyObject();
        $refreshToken->clientID = $this->params['client_id'];
        $refreshToken->token    = $this->createRandomToken();
        $refreshToken->expired  = $refreshTokenExpired;
        $refreshToken->scope    = $client->allowedScopes;
        $this->getRefreshTokenRepository()->getModel()->save($refreshToken);

        return $refreshToken;
    }

    /**
     * @param $clientID
     * @param int $userID
     */
    protected function cleanAccessToken($clientID, $userID = 0): void
    {
        $accessToken = $this->getAccessTokenRepository()
            ->getModel()
            ->where([
                'clientID' => $clientID,
                'userID'   => $userID,
            ])
            ->runOnce();

        if (!is_null($accessToken)) {
            $this->getAccessTokenRepository()->delete($accessToken);
        }
    }

    /**
     * @param $clientID
     * @param int $userID
     */
    protected function cleanRefreshToken($clientID, $userID = 0): void
    {
        $refreshToken = $this->getRefreshTokenRepository()
            ->getModel()
            ->where([
                'clientID' => $clientID,
                'userID'   => $userID,
            ])
            ->runOnce();

        if (!is_null($refreshToken)) {
            $this->getRefreshTokenRepository()->delete($refreshToken);
        }
    }

    /**
     * @return string
     */
    protected function createRandomToken()
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(100, $strong);

            if (true === $strong && false !== $bytes) {
                $randomData = $bytes;
            }
        }

        if (empty($randomData)) { // Get 108 bytes of (pseudo-random, insecure) data
            $randomData = mt_rand() . mt_rand() . mt_rand() . uniqid(mt_rand(), true) . microtime(true) . uniqid(
                    mt_rand(),
                    true
                );
        }

        return rtrim(strtr(base64_encode(hash('sha256', $randomData)), '+/', '-_'), '=');
    }
}
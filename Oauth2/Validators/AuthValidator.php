<?php
namespace Oauth2\Validators;

use Oauth2\GrantTypes\BaseGrantType;
use Oauth2\Validators\Exceptions\CorsException;
use Oauth2\Validators\Exceptions\GrantTypeMissingConfigurationException;
use Oauth2\Validators\Exceptions\InvalidContentTypeException;
use Oauth2\Validators\Exceptions\InvalidGrantTypeException;
use Oauth2\Validators\Exceptions\InvalidRequestMethodException;
use Oauth2\Validators\Exceptions\InvalidRequestParametersException;
use Symfony\Component\HttpFoundation\Request;

class AuthValidator
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var BaseGrantType
     */
    protected $grantType;

    /**
     * AuthValidator constructor.
     * @param Request $request
     * @param array $config
     * @throws GrantTypeMissingConfigurationException
     * @throws InvalidContentTypeException
     * @throws InvalidGrantTypeException
     * @throws InvalidRequestMethodException
     * @throws InvalidRequestParametersException
     */
    public function __construct(Request $request, array $config)
    {
        $this->config  = $config;
        $this->request = $request;

        $this->checkMethod();
        $this->checkContentType();
        $this->checkParams();
    }

    /**
     * @return BaseGrantType
     */
    public function getGrantType()
    {
        return $this->grantType;
    }

    /**
     * @return bool
     * @throws CorsException
     * @throws InvalidRequestMethodException
     */
    private function checkMethod()
    {
        $method = $this->config['method'] ?? false;

        if ($method) {
            if (
                (is_array($method) && !in_array($this->request->getRealMethod(), $method)) ||
                (!is_array($method) && strtoupper($this->request->getRealMethod()) != strtoupper($method))
            ) {
                if ($this->request->getRealMethod() == 'OPTIONS') {
                    throw new CorsException();
                } else {
                    throw new InvalidRequestMethodException();
                }
            }
        }

        return true;
    }

    /**
     * @return bool
     * @throws InvalidContentTypeException
     */
    private function checkContentType()
    {
        $type = $this->config['contentType'] ?? false;

        if ($type &&
            (
                (is_array($type) && !in_array(strtolower($this->request->getContentType()), $type)) ||
                (!is_array($type) && strtolower($this->request->getContentType()) != strtolower($type))
            )
        ) {
            throw new InvalidContentTypeException();
        }

        return true;
    }

    /**
     * @return mixed
     * @throws GrantTypeMissingConfigurationException
     * @throws InvalidGrantTypeException
     * @throws InvalidRequestParametersException
     */
    private function checkParams()
    {
        if ($this->request->getRealMethod() == 'GET') {
            $queryString = $this->request->getQueryString();
            parse_str($queryString, $params);
        } elseif (
            ($this->request->getContentType() === 'application/json' || $this->request->getContentType() === 'json') &&
            !empty($this->request->getContent())
        ) {
            $params = json_decode($this->request->getContent(), true);
        } else {
            $queryString = $this->request->getContent();
            parse_str($queryString, $params);
        }

        if (!isset($params['grant_type']) && !isset($params['token'])) {
            throw new InvalidGrantTypeException();
        }

        $grantType      = $params['grant_type'] ?? 'login';
        $requiredParams = $this->config['required'][$grantType] ?? [];
        $grantTypeClass = $this->config['grant_types'][$grantType] ?? false;

        if (empty($requiredParams) || !$grantTypeClass) {
            throw new GrantTypeMissingConfigurationException();
        }

        foreach ($requiredParams as $configParam) {
            if (!isset($params[$configParam])) {
                throw new InvalidRequestParametersException();
            }
        }

        $this->grantType = new $grantTypeClass($params);
    }
}
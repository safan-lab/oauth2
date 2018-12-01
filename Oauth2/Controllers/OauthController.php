<?php
namespace Oauth2\Controllers;

use Oauth2\GrantTypes\BaseGrantType;
use Oauth2\Validators\AuthValidator;
use Oauth2\Validators\Exceptions\CorsException;
use Safan\GlobalExceptions\ParamsNotFoundException;
use Safan\Mvc\Controller;
use Safan\Safan;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OauthController
 * @package Oauth2\Controllers
 */
class OauthController extends Controller
{
    /**
     * @throws \Exception
     */
    public function authAction()
    {
        try {
            $request = Request::createFromGlobals();
            $config  = $this->getConfig('auth');

            $validator = new AuthValidator($request, $config);
            $service   = $validator->getGrantType();
            $data      = $service->authorize();
            $status    = Response::HTTP_CREATED;
        } catch (CorsException $e) {
            $status = Response::HTTP_OK;
            $data   = [];
        } catch (\Throwable $e) {
            $status = $e->getCode() ?: Response::HTTP_UNAUTHORIZED;
            $data   = [
                'status'  => $status,
                'message' => $e->getMessage() ?? Response::$statusTexts[$status]
            ];
        }

        return $this->respond($data, $status);
    }

    /**
     * @throws \Exception
     */
    public function loginAction()
    {
        try {
            /**
             * @var BaseGrantType $service
             */
            $request = Request::createFromGlobals();
            $config  = $this->getConfig('auth');

            $validator = new AuthValidator($request, $config);
            $service   = $validator->getGrantType();
            $data      = $service->authorize();
            $status    = Response::HTTP_OK;
        } catch (CorsException $e) {
            $status = Response::HTTP_OK;
            $data   = [];
        } catch (\Throwable $e) {
            $status = $e->getCode() ?: Response::HTTP_UNAUTHORIZED;
            $data   = [
                'status'  => $status,
                'message' => $e->getMessage() ?? Response::$statusTexts[$status]
            ];
        }

        return $this->respond($data, $status);
    }

    /**
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    private function getConfig($type)
    {
        $defaultConfigFile = dirname(__DIR__) . DS . 'Resources' . DS . 'config' . DS . 'validation.config.php';
        $configFile        = Safan::handler()->getConfig()['oauth2']['validation'] ?? $defaultConfigFile;

        if (!file_exists($configFile)) {
            throw new \Exception('Oauth2 validation file not found');
        }

        $config = include($configFile);

        if (!isset($config[$type])) {
            throw new ParamsNotFoundException(sprintf('Oauth2 %s configuration not defined', $type));
        }

        return $config[$type];
    }

    /**
     * @param array $data
     * @param int $httpStatus
     * @param array $headers
     * @return JsonResponse
     */
    protected function respond($data = [], $httpStatus = Response::HTTP_OK, $headers = [])
    {
        $response = new JsonResponse(json_encode($data, true), $httpStatus, $this->getHeaders($headers), true);

        return $response->send();
    }

    /**
     * @param string $message
     * @param int $httpStatus
     * @param array $data
     * @param array $headers
     * @return JsonResponse
     */
    protected function respondSuccess($message = '', $httpStatus = Response::HTTP_OK, $data = [], $headers = [])
    {
        $responseData = [];

        if (!empty($message)) {
            $responseData['message'] = $message;
        }

        if (!empty($data)) {
            $responseData['data'] = $data;
            $responseData         = json_encode($responseData, true);

            return new JsonResponse($responseData, $httpStatus, $headers, true);
        }

        $responseData = json_encode($responseData, true);

        return new JsonResponse($responseData, $httpStatus, $this->getHeaders($headers), false);
    }

    /**
     * @param $message
     * @param int $httpStatus
     * @param array $data
     * @param array $headers
     * @return JsonResponse
     */
    protected function respondError($message, $httpStatus = Response::HTTP_BAD_REQUEST, $data = [], $headers = [])
    {
        $responseData = [
            'error' => $message
        ];

        if (!empty($data)) {
            $responseData['details'] = $data;
        }

        return new JsonResponse($responseData, $httpStatus ?: Response::HTTP_UNAUTHORIZED, $this->getHeaders($headers), false);
    }

    /**
     * @param array $headers
     * @return array
     */
    private function getHeaders($headers = [])
    {
        return array_merge($headers, [
            "Access-Control-Allow-Origin" => "*",
            "Access-Control-Allow-Methods" => "POST, GET, DELETE, PUT, PATCH, OPTIONS",
            "Access-Control-Allow-Headers" => "Authorization, Origin, X-Requested-With, Content-Type, Accept"
        ]);
    }
}
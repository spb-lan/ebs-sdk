<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 25.07.17
 * Time: 17:18
 */

namespace Lan\Ebs\Sdk;

use Codeception\Util\Debug;
use Exception;
use Lan\Ebs\Sdk\Helper\Curl;

final class Client
{
    private $host = 'http://openapi.landev.ru';

    private $token;

    /**
     * Ebs constructor.
     * @param $token
     * @throws Exception
     */
    public function __construct($token)
    {
        if (empty($token)) {
            throw new Exception('Token is empty');
        }

        $this->token = $token;

        if ($_SERVER['USER'] == 'dp') {
            $this->host = 'http://eop.local';
        }

        if (\session_status() === PHP_SESSION_NONE && !\headers_sent()) {
            session_start();
        }
    }

    public function getResponse(array $request, array $params = [])
    {
        if (empty($request['url']) || empty($request['method']) || empty($request['code'])) {
            throw new Exception('Request url, method or success_code is missing');
        }

        $response = Curl::getResponse($this->host, $request['url'], $request['method'], $this->token, $params);

        if (isset($response['debug'])) {
            Debug::debug(['request' => $request, 'params' => $params, 'response' => $response]);
        }

        if ($response['status'] != $request['code']) {
            throw new Exception($response['message'], $response['status']);
        }

        return $response;
    }

//    /**
//     * @return mixed
//     */
//    public function getToken()
//    {
//        return $this->token;
//    }
}
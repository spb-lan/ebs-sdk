<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 25.07.17
 * Time: 17:18
 */

namespace Lan\Ebs\Sdk;

use Exception;
use Lan\Ebs\Sdk\Helper\Curl;
use Lan\Ebs\Sdk\Helper\Debuger;

final class Client
{
    private $token = '';

    /**
     * Ebs constructor.
     *
     * @param  $token
     * @throws Exception
     */
    public function __construct($token)
    {
        if (empty($token)) {
            throw new Exception('Token is empty');
        }

        $this->token = $token;
    }

    /**
     * @param array $request
     * @param array $params
     * @return array|mixed
     * @throws Exception
     */
    public function getResponse(array $request, array $params = [])
    {
        if (empty($request['url']) || empty($request['method']) || empty($request['code'])) {
            throw new Exception('Request url, method or success_code is missing');
        }

        $host = Security::getApiHost();

        $response = Curl::getResponse($host, $request['url'], $request['method'], $this->token, $params);

        if (isset($response['debug'])) {
            Debuger::dump(['host' => $host, 'request' => $request, 'params' => $params, 'response' => $response]);
        }

        if ($response['status'] != $request['code']) {
            throw new Exception($response['message'], $response['status']);
        }

        return $response;
    }
}
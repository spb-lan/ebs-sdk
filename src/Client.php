<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 25.07.17
 * Time: 17:18
 */

namespace Lan\Ebs\Sdk;

use Codeception\Util\Debug;
use Error;
use Lan\Ebs\Sdk\Helper\Curl;

final class Client
{
    private $host = 'http://eop.local';

    private $token;

    /**
     * Ebs constructor.
     * @param $token
     * @throws Error
     */
    public function __construct($token)
    {
        if (empty($token)) {
            throw new Error('Token is empty');
        }

        $this->token = $token;
    }

    public function getResponse(array $request, array $params = [])
    {
        if (empty($request['url']) || empty($request['method']) || empty($request['code'])) {
            throw new Error('Request url, method or success_code is missing');
        }

        $response = Curl::getResponse($this->host, $request['url'], $request['method'], $this->token, $params);

        if (isset($response['debug'])) {
            Debug::debug(['request' => $request, 'params' => $params, 'response' => $response]);
        }

        if ($response['status'] != $request['code']) {
            throw new Error($response['message'], $response['status']);
        }

        return $response;
    }
}
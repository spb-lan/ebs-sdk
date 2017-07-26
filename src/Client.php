<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 25.07.17
 * Time: 17:18
 */

namespace Lan\Ebs\Sdk;

use Error;
use Lan\Ebs\Sdk\Helper\Curl;
use Monolog\Logger;

final class Client
{
    private $host = 'http:/eop.local';

    private $token;

    private $logger = null;

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
            $this->getLogger()->debug('debug', (array)$response['debug']);
        }

        if ($response['status'] != $request['code']) {
            $this->getLogger()->error($response['message'], $response);

            throw new Error($response['message']);
        }

        return $response;
    }

    private function getLogger()
    {
        if ($this->logger === null) {
            $this->logger = new Logger(get_class($this));
        }

        return $this->logger;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 16.08.17
 * Time: 13:09
 */

namespace Lan\Ebs\Sdk;

use Exception;

final class Security implements Common
{
    private $client;

    /**
     * Security constructor.
     *
     * @param  Client $client
     * @throws Exception
     */
    public function __construct(Client $client)
    {
        if (!$client) {
            throw new Exception('Client not defined');
        }

        $this->client = $client;
    }

    public function getUrl($method, array $params = [])
    {
        switch ($method) {
            case 'getSecretKey':
                return [
                    'url' => '/1.0/security/secretKey',
                    'method' => 'GET',
                    'code' => 200
                ];
            case 'getDemoUrl':
                return [
                    'url' => '/1.0/security/demoUrl',
                    'method' => 'GET',
                    'code' => 200
                ];
            case 'getAutologinUrl':
                return [
                    'url' => '/1.0/security/autologinUrl',
                    'method' => 'GET',
                    'code' => 200
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }

    public function getDemoUrl($type, $id)
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__), ['type' => $type, 'id' => $id])['data'];
    }

    public function getAutologinUrl($uid, $fio = null, $email = null, $redirect = null) {
        return $this->client->getResponse(
            $this->getUrl(__FUNCTION__),
            [
                'uid' => $uid,
                'time' => date('YmdHi'),
                'fio' => $fio,
                'email' => $email,
                'redirect' => $redirect
            ]
        )['data'];
    }
}
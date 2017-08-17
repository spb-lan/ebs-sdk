<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 16.08.17
 * Time: 13:09
 */

namespace Lan\Ebs\Sdk;

use Error;

final class Security implements Common
{
    private $client;

    /**
     * Security constructor.
     *
     * @param Client $client
     * @throws Error
     */
    public function __construct(Client $client)
    {
        if (!$client) {
            throw new Error('Client not defined');
        }

        $this->client = $client;
    }

    public function getSecretKey($date)
    {
        if (is_string($date) && strlen($date) >= 8) {
            $date = substr($date, 0, 8);

            if (!empty($_SESSION[$date])) {
                return $_SESSION[$date];
            }
        }

        return $_SESSION[$date] = $this->client->getResponse($this->getUrl(__FUNCTION__), ['date' => $date])['data'];
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
            default :
                throw new Error('Route for ' . $method . ' not found');
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 16.08.17
 * Time: 13:09
 */

namespace Lan\Ebs\Sdk;

use Exception;

final class ReportForm implements Common
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

    /**
     * @param $method
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getUrl($method, array $params = [])
    {
        switch ($method) {
            case 'getBibFond':
                return [
                    'url' => '/1.0/report/form/bibFond',
                    'method' => 'GET',
                    'code' => 200
                ];
            case 'getEBooks':
                return [
                    'url' => '/1.0/report/form/eBooks',
                    'method' => 'GET',
                    'code' => 200
                ];
            case 'getSpecPo':
                return [
                    'url' => '/1.0/report/form/specPo',
                    'method' => 'GET',
                    'code' => 200
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getBibFond()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getEBooks()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getSpecPo()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }
}
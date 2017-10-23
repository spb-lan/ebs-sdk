<?php

namespace Lan\Ebs\Sdk;

use Exception;

final class Report implements Common
{
    const GROUP_BY_DAY = 'day';
    const GROUP_BY_MONTH = 'month';
    const GROUP_BY_YEAR = 'year';
    const GROUP_BY_ALL = 'all';

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
     * @param null $groupBy
     * @param null $periodFrom
     * @param null $periodTo
     * @return mixed
     * @throws Exception
     */
    public function getBooksViewsStatistics($groupBy, $periodFrom, $periodTo)
    {
        return $this->client->getResponse(
            $this->getUrl(__FUNCTION__),
            [
                'group_by' => $groupBy,
                'period_range_from' => $periodFrom,
                'period_range_to' => $periodTo,
            ]
        )['data'];
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
            case 'getBooksViewsStatistics':
                return [
                    'url' => '/1.0/report/stat/book',
                    'method' => 'GET',
                    'code' => 200
                ];
            case 'getJournalsViewsStatistics':
                return [
                    'url' => '/1.0/report/stat/journal',
                    'method' => 'GET',
                    'code' => 200
                ];
            case 'getUsersVisitsSatistics':
                return [
                    'url' => '/1.0/report/stat/visit',
                    'method' => 'GET',
                    'code' => 200
                ];
            case 'getAvailablePackets':
                return [
                    'url' => '/1.0/report/available/packet',
                    'method' => 'GET',
                    'code' => 200
                ];
            case 'getAvailableBooks':
                return [
                    'url' => vsprintf('/1.0/report/available/book/%d', $params),
                    'method' => 'GET',
                    'code' => 200
                ];
            case 'getAvailableJournals':
                return [
                    'url' => '/1.0/report/available/journal',
                    'method' => 'GET',
                    'code' => 200
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }

    /**
     * @param null $groupBy
     * @param null $periodFrom
     * @param null $periodTo
     * @return mixed
     * @throws Exception
     */
    public function getJournalsViewsStatistics($groupBy, $periodFrom, $periodTo)
    {
        return $this->client->getResponse(
            $this->getUrl(__FUNCTION__),
            [
                'group_by' => $groupBy,
                'period_range_from' => $periodFrom,
                'period_range_to' => $periodTo,
            ]
        )['data'];
    }

    /**
     * @param null $groupBy
     * @param null $periodFrom
     * @param null $periodTo
     * @return mixed
     * @throws Exception
     */
    public function getUsersVisitsSatistics($groupBy, $periodFrom, $periodTo)
    {
        return $this->client->getResponse(
            $this->getUrl(__FUNCTION__),
            [
                'group_by' => $groupBy,
                'period_range_from' => $periodFrom,
                'period_range_to' => $periodTo,
            ]
        )['data'];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getAvailablePackets()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }

    /**
     * @param $pdKey
     * @return mixed
     * @throws Exception
     */
    public function getAvailableBooks($pdKey)
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__, ['pdKey' => $pdKey]))['data'];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getAvailableJournals()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getFormReportEBooks()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }
}
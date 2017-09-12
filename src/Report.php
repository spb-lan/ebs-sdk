<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 16.08.17
 * Time: 13:09
 */

namespace Lan\Ebs\Sdk;

use Exception;

final class Report implements Common
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
                    'params' => [],
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
                    'params' => [],
                    'method' => 'GET',
                    'code' => 200
                ];
            case 'getFormReportEBooks':
                return [
                    'url' => '/1.0/report/form/eBooks',
                    'params' => [],
                    'method' => 'GET',
                    'code' => 200
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }

    public function getBooksViewsStatistics($groupBy = null, $periodFrom = null, $periodTo = null)
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

    public function getJournalsViewsStatistics($groupBy = null, $periodFrom = null, $periodTo = null)
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

    public function getUsersVisitsSatistics($groupBy = null, $periodFrom = null, $periodTo = null)
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

    public function getAvailablePackets()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }

    public function getAvailableBooks($pdKey)
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__, ['pdKey' => $pdKey]))['data'];
    }

    public function getAvailableJournals()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }

    public function getFormReportEBooks()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }
}
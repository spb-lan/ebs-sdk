<?php
/**
 * Class Report
 *
 * @author       Emil Limarenko <eal@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk;

use Exception;

/**
 * SDK для общих отчетов
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 */
class Report implements Common
{
    /**
     * Группировка по дням
     */
    const GROUP_BY_DAY = 'day';

    /**
     * Группировка по месяцам
     */
    const GROUP_BY_MONTH = 'month';

    /**
     * Группировка по годам
     */
    const GROUP_BY_YEAR = 'year';

    /**
     * Инстанс клиента API
     *
     * @var Client
     */
    private $client;

    /**
     * Конструктор общего отчета
     *
     * Экземпляр класса Report нужен для осуществления запросов к API для получения отчетных данных ЭБС Лань.
     *
     * @param Client $client Инстанс клиента
     *
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     *
     *      $report = new Report($client):
     * ```
     *
     * @throws Exception
     */
    public function __construct(Client $client)
    {
        if (!$client) {
            throw new Exception('Клиент не инициализирован');
        }

        $this->client = $client;
    }

    /**
     * Общая статистика чтения книг
     *
     * @param string $groupBy Группировка ('day|month|year')
     * @param string $periodFrom Период с (формат Y-m-d, например 2017-10-01)
     * @param string $periodTo Период с (формат Y-m-d, например 2017-11-01)
     *
     *
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getBooksViewsStatistics($groupBy, $periodFrom, $periodTo)
    {
        return $this->client->getResponse(
            $this->getUrl(__FUNCTION__),
            array(
                'group_by' => $groupBy,
                'period_range_from' => $periodFrom,
                'period_range_to' => $periodTo,
            )
        )['data'];
    }

    /**
     * Получение данных для запроса через API
     *
     * @param string $method Http-метод запроса
     * @param array $params Параметры для формирования урла
     *
     * @return array
     *
     * @throws Exception
     */
    public function getUrl($method, array $params = array())
    {
        switch ($method) {
            case 'getBooksViewsStatistics':
                return array(
                    'url' => '/1.0/report/stat/book',
                    'method' => 'GET',
                    'code' => 200
                );
            case 'getJournalsViewsStatistics':
                return array(
                    'url' => '/1.0/report/stat/journal',
                    'method' => 'GET',
                    'code' => 200
                );
            case 'getUsersVisitsStatistics':
                return array(
                    'url' => '/1.0/report/stat/visit',
                    'method' => 'GET',
                    'code' => 200
                );
            case 'getAvailablePackets':
                return array(
                    'url' => '/1.0/report/available/packet',
                    'method' => 'GET',
                    'code' => 200
                );
            case 'getAvailableBooks':
                return array(
                    'url' => vsprintf('/1.0/report/available/book/%d', $params),
                    'method' => 'GET',
                    'code' => 200
                );
            case 'getAvailableJournals':
                return array(
                    'url' => '/1.0/report/available/journal',
                    'method' => 'GET',
                    'code' => 200
                );
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }

    /**
     * Общая статистика чтения журналов
     *
     * @param string $groupBy Группировка ('day|month|year')
     * @param string $periodFrom Период с (формат Y-m-d, например 2017-10-01)
     * @param string $periodTo Период с (формат Y-m-d, например 2017-11-01)
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getJournalsViewsStatistics($groupBy, $periodFrom, $periodTo)
    {
        return $this->client->getResponse(
            $this->getUrl(__FUNCTION__),
            array(
                'group_by' => $groupBy,
                'period_range_from' => $periodFrom,
                'period_range_to' => $periodTo,
            )
        )['data'];
    }

    /**
     * Статистика посещаемости
     *
     * @param string $groupBy Группировка ('day|month|year')
     * @param string $periodFrom Период с (формат Y-m-d, например 2017-10-01)
     * @param string $periodTo Период с (формат Y-m-d, например 2017-11-01)
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getUsersVisitsStatistics($groupBy, $periodFrom, $periodTo)
    {
        return $this->client->getResponse(
            $this->getUrl(__FUNCTION__),
            array(
                'group_by' => $groupBy,
                'period_range_from' => $periodFrom,
                'period_range_to' => $periodTo,
            )
        )['data'];
    }

    /**
     * Доступные пакеты книг
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getAvailablePackets()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }

    /**
     * Доступные книги в пакете
     *
     * @param int $pdKey Идентификатор пакета
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getAvailableBooks($pdKey)
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__, array('pdKey' => $pdKey)))['data'];
    }

    /**
     * Доступные журналы
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getAvailableJournals()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }
}
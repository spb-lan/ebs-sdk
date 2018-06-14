<?php
/**
 * Class ReportForm
 *
 * @author       Emil Limarenko <eal@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk;

use Exception;

/**
 * SDK формализованных отчетов
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 */
class ReportForm implements Common
{
    /**
     * Инстанс клиента API
     *
     * @var Client
     */
    private $client;

    /**
     * Конструктор формализованного отчета
     *
     * Экземпляр класса ReportForm нужен для осуществления запросов к API для получения формализованных отчетных данных ЭБС Лань.
     *
     * @param Client $client Инстанс клиента
     *
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     *
     *      $report = new ReportForm($client):
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
     * Библиотечный фонд
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getBibFond()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
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
            case 'getBibFond':
                return array(
                    'url' => '/1.0/report/form/bibFond',
                    'method' => 'GET',
                    'code' => 200
                );
            case 'getEBooks':
                return array(
                    'url' => '/1.0/report/form/eBooks',
                    'method' => 'GET',
                    'code' => 200
                );
            case 'getSpecPo':
                return array(
                    'url' => '/1.0/report/form/specPo',
                    'method' => 'GET',
                    'code' => 200
                );
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }

    /**
     * Электронных книг по направлениям подготовки
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getEBooks()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }

    /**
     * Специальное ПО
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getSpecPo()
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__))['data'];
    }
}
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
     * @param Client $client Инстанс клиента
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
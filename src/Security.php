<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 16.08.17
 * Time: 13:09
 */

namespace Lan\Ebs\Sdk;

use Exception;

/**
 * Класс для получения публичных ресурсов по закрытому токену
 *
 * @package Lan\Ebs\Sdk
 */
final class Security implements Common
{
    /**
     * Токен для тестового доступа
     */
    const TEST_TOKEN = '7c0c2193d27108a509abd8ea84a8750c82b3a520';

    /**
     * Домен продакшен сервера API
     */
    const PROD_API_HOST = 'https://openapi.e.lanbook.com';

    /**
     * Домен тестового сервера API
     */
    const TEST_API_HOST = 'http://openapi.landev.ru';

    /**
     * Домен дев сервера API
     */
    const DEV_API_HOST = 'http://eop.local';

    /**
     * Домен продакшен сервера ЭБС
     */
    const PROD_EBS_HOST = 'https://e.lanbook.com';

    /**
     * Домен тестового сервера ЭБС
     */
    const TEST_EBS_HOST = 'http://ebs.landev.ru';

    /**
     * Домен дев сервера ЭБС
     */
    const DEV_EBS_HOST = 'http://ebs.local';

    /**
     * Инстанс клиента API
     *
     * @var Client
     */
    private $client;

    /**
     * Конструктор
     *
     * @param Client $client Истанс клиента
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

    /**
     * @param $type
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function getDemoUrl($type, $id)
    {
        return $this->client->getResponse($this->getUrl(__FUNCTION__), ['type' => $type, 'id' => $id])['data'];
    }

    /**
     * @param $uid
     * @param null $fio
     * @param null $email
     * @param null $redirect
     * @return mixed
     * @throws Exception
     */
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

    public static function getApiHost()
    {
        return isset($_SERVER['USER']) && $_SERVER['USER'] == 'dp' ? Security::DEV_API_HOST : Security::PROD_API_HOST;
    }

    public static function getEbsHost()
    {
        return isset($_SERVER['USER']) && $_SERVER['USER'] == 'dp' ? Security::DEV_EBS_HOST : Security::PROD_EBS_HOST;
    }
}
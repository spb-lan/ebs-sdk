<?php
/**
 * Class Security
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk;

use Exception;

/**
 * Класс для получения публичных ресурсов по закрытому токену
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
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
     * Домен продакшен сервера ЭБС
     */
    const PROD_EBS_HOST = 'https://e.lanbook.com';

    /**
     * Инстанс клиента API
     *
     * @var Client
     */
    private $client;

    /**
     * Конструктор экземпляра класса Security
     *
     * Экземпляр класса Security нужен для получения публичных ресурсов по закрытому токену (Например url для автологина)
     *
     * @param Client $client Истанс клиента
     *
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     *
     *      $security = new Security($client); // инициализация объекта SDK Security
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
     * Получение хоста API-сервера
     *
     * Пример:
     * ```php
     *      $apiHost = \Lan\Ebs\Sdk\Security::getApiHost();
     * ```
     *
     * @return string Хост сервера API
     *
     * Пример:
     * ```
     *      https://openapi.e.lanbook.com
     * ```
     */
    public static function getApiHost()
    {
        return isset($_SERVER['USER']) && $_SERVER['USER'] == 'dp' ? 'http://openapi.local' : Security::PROD_API_HOST;
    }

    /**
     * Получение хоста сервера ЭБС
     *
     * @return string
     */
    public static function getEbsHost()
    {
        return isset($_SERVER['USER']) && $_SERVER['USER'] == 'dp' ? 'http://openapi.local' : Security::PROD_EBS_HOST;
    }

    /**
     * Получение url для автологина
     *
     * @param int|string $uid Уникальные идентификатора прользователя на стороне клиента
     * @param string $fio ФИО (необязательно)
     * @param string $email Электронный адрес (необязательно)
     * @param string $redirect Url для редиректа на сайте ЭБС
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getAutologinUrl($uid, $fio = null, $email = null, $redirect = null)
    {
        return $this->client->getResponse(
            $this->getUrl(__FUNCTION__),
            array(
                'uid' => $uid,
                'time' => date('YmdHi'),
                'fio' => $fio,
                'email' => $email,
                'redirect' => $redirect
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
            case 'getAutologinUrl':
                return array(
                    'url' => '/1.0/security/autologinUrl',
                    'method' => 'GET',
                    'code' => 200
                );
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}
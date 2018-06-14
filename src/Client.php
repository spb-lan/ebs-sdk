<?php
/**
 * Class Client
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk;

use Exception;
use Lan\Ebs\Sdk\Helper\Curl;
use Lan\Ebs\Sdk\Helper\Debuger;

/**
 * Клиент API
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 */
final class Client
{
    /**
     * Токен клиента
     *
     * @var string
     */
    private $token = '';

    /**
     * Конструктор экземпляра класса Client
     *
     * Экземпляр класса Client нужен для осуществления запросов к API.
     *
     * @param string $token Токен клиента
     *
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     * ```
     *
     * @throws Exception
     */
    public function __construct($token)
    {
        if (empty($token)) {
            throw new Exception('Токен пустой');
        }

        $this->token = $token;
    }

    /**
     * Получение ответа сервера API
     *
     * Выполняется запрос к серверу API и получается ответ в формвте JSON
     *
     * @param array $request Данные для запроса (url, method)
     * @param array $params Параметры запроса
     *
     * Пример:
     * ```php
     *      $request = [
     *          'url' => '/1.0/resource/book/get/29),
     *          'method' => 'GET',
     *          'code' => 200
     *      ];
     *
     *      $params = ['fields' => 'name,authors.isbn'];
     *
     *      $response = $client->getResponse($request, $params);
     * ```
     *
     * @return array Ответ от сервера API приходит в формате JSON
     *
     * Пример:
     * ```json
     *      {
     *          "type":"object",
     *          "data":{
     *              "id":29,
     *              "name":"Курс теоретической механики",
     *              "authors":"Бутенин Н.В., Лунц Я.Л., Меркин Д.Р.",
     *              "isbn":"978-5-8114-0052-2"
     *          },
     *          "count":1,
     *          "status":200,
     *          "message":"Ok"
     *      }
     * ```
     *
     * @throws Exception
     */
    public function getResponse(array $request, array $params = array())
    {
        if (empty($request['url']) || empty($request['method']) || empty($request['code'])) {
            throw new Exception('Request url, method or success_code is missing');
        }

        $host = Security::getApiHost();

        $response = Curl::getResponse($host, $request['url'], $request['method'], $this->token, $params);

        if (isset($response['debug'])) {
            Debuger::dump(array('host' => $host, 'request' => $request, 'params' => $params, 'response' => $response));
        }

        if ($response['status'] != $request['code']) {
            throw new Exception($response['message'], $response['status']);
        }

        return $response;
    }
}
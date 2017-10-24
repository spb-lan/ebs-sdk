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
     * Конструктор клиента
     *
     * @param string $token Токен клиента
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
     * @param array $request Данные для запроса (url, method)
     * @param array $params Параметры запроса
     *
     * @return array
     *
     * @throws Exception
     */
    public function getResponse(array $request, array $params = [])
    {
        if (empty($request['url']) || empty($request['method']) || empty($request['code'])) {
            throw new Exception('Request url, method or success_code is missing');
        }

        $host = Security::getApiHost();

        $response = Curl::getResponse($host, $request['url'], $request['method'], $this->token, $params);

        if (isset($response['debug'])) {
            Debuger::dump(['host' => $host, 'request' => $request, 'params' => $params, 'response' => $response]);
        }

        if ($response['status'] != $request['code']) {
            throw new Exception($response['message'], $response['status']);
        }

        return $response;
    }
}
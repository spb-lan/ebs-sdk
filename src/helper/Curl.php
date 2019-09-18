<?php
/**
 * Class Curl
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk\Helper;

use Exception;

/**
 * Хелпер для работы с curl
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 * @category     Helper
 */
class Curl
{
    /**
     * Получение ответа от сервера API
     *
     * @param string $host Домен сервера API
     * @param string $url Урл ресурса API
     * @param string $method Метод http-запроса (GET|POST|PUT|DELETE)
     * @param string $token Токен клиента
     * @param array $params Параметры, переданные серверу API
     *
     * @return array|mixed
     *
     * @throws Exception
     */
    public static function getResponse($host, $url, $method, $token, array $params)
    {
        $curl = curl_init();

        switch ($method) {
            case 'GET':
                if (!empty($params)) {
                    $url = sprintf("%s?%s", $url, http_build_query($params, '', '&'));
                };

                break;
            case 'POST':
            case 'PUT':
            case 'DELETE':
                if (!empty($params)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));
                }
                break;
            default:
                throw new Exception('Method ' . $method . ' unknown');
        }

//        $url = html_entity_decode($url);

        $headers = array(
            'X-Auth-Token: ' . $token,
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            'Accept: application/json'
        );

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $host . $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

//        $fp = fopen(__DIR__ . '/errorlog.txt', 'w');
//        curl_setopt($curl, CURLOPT_VERBOSE, 1);
//        curl_setopt($curl, CURLOPT_STDERR, $fp);

        Debuger::dump($method . ' ' . $host . $url . ' ' . ($method == 'POST' || $method == 'PUT' || $method == 'DELETE' ? Curl::arrayPrettyPrint($params) : ''));

        $curlResult = curl_exec($curl);

        Debuger::dump('Response code: ' . (curl_errno($curl) ? '!!! Error !!!' : curl_getinfo($curl)['http_code']) . "\n");

        if (curl_errno($curl)) {
            return Curl::getError('Curl error ' . curl_errno($curl) . ': ' . curl_error($curl), 500);
        }

        $response = json_decode($curlResult, true);

        if (json_last_error()) {
            return Curl::getError('JSON error ' . json_last_error() . ': ' . json_last_error_msg() . "\n" . $curlResult, curl_getinfo($curl)['http_code']);
        }

        if (empty($response)) {
            return Curl::getError('Response is empty', curl_getinfo($curl)['http_code']);
        }

        return $response;
    }

    /**
     * Получение массива как строку
     *
     * @param array $array Данные массива
     *
     * @return mixed
     */
    private static function arrayPrettyPrint(array $array)
    {
        return str_replace('Array (', '(', preg_replace('/\s{2,}/', ' ', preg_replace('/[\x00-\x1F\x7F ]/', ' ', print_r($array, true))));
    }

    /**
     *  Дефолтный ответ при ошибке
     *
     * @param string $message Сообщение об ошибке
     * @param int $code Статус код ошибки
     *
     * @return array
     */
    private static function getError($message, $code)
    {
        return array(
            'type' => 'none',
            'data' => null,
            'count' => 0,
            'status' => $code,
            'message' => $message
        );
    }
}
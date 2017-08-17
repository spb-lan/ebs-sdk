<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 12:06
 */

namespace Lan\Ebs\Sdk\Helper;

use Codeception\Util\Debug;
use Error;

class Curl
{
    public static function getResponse($host, $url, $method, $token, array $params)
    {
        $curl = curl_init();

        switch ($method) {
            case 'GET':
                if ($params) {
                    $url = sprintf("%s?%s", $url, http_build_query($params));
                };

                break;
            case 'POST':
            case 'PUT':
            case 'DELETE':
                if ($params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
                }
                break;
            default:
                throw new Error('Method ' . $method . ' unknown');
        }

        $headers = [
            'X-Auth-Token: ' . $token,
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            'Accept: application/json'
        ];

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $host . $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

//        if ($method == 'PUT') {
//            $fp = fopen(dirname(__FILE__) . '/errorlog.txt', 'w');
//            curl_setopt($curl, CURLOPT_VERBOSE, 1);
//            curl_setopt($curl, CURLOPT_STDERR, $fp);
//
//            Debug::debug('"' . curl_exec($curl) . '"');
//            die();
//        }

        $response = curl_exec($curl);

        Debug::debug($method . ' ' . $host . $url . ' [' . (curl_errno($curl) ? 500 : curl_getinfo($curl)['http_code']) . ']');

        if (curl_errno($curl)) {
            return Curl::getError('Curl error: ' . curl_errno($curl), 500);
        }

        $response = json_decode($response, true);

        if (json_last_error()) {
            return Curl::getError('JSON error: ' . json_last_error_msg(), curl_getinfo($curl)['http_code']);
        }

        if (empty($response)) {
            return Curl::getError('Response is empty', curl_getinfo($curl)['http_code']);
        }

        return $response;
    }

    private static function getError($message, $code)
    {
        return [
            'type' => 'none',
            'data' => null,
            'count' => 0,
            'status' => $code,
            'message' => $message
        ];
    }
}
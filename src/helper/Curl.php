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
//            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
//            'Accept: application/json'
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

        try {
            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                throw new Error('Curl error: ' . curl_errno($curl));
            }

            if (empty($response)) {
                throw new Error('Response is empty (after curl_exec): ' . curl_errno($curl));
            }

            $response = json_decode(curl_exec($curl), true);

            if (json_last_error()) {
                throw new Error('JSON error: ' . json_last_error_msg());
            }

            if (empty($response)) {
                throw new Error('Response is empty (after json_decode): ' . curl_errno($curl));
            }
        } catch (Error $e) {
            $errorData = ' (' . $method . ' ' . $host . $url . ' ' . preg_replace('/[\x00-\x1F\x7F ]/', ' ', print_r($params, true)) . ')';

            throw new Error(preg_replace('/\s{2,}/', ' ', $e->getMessage() . $errorData));
        }

        return $response;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 12:06
 */

namespace Lan\Ebs\Sdk\Helper;

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
                if ($params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                }

                break;
            case 'PUT':
                if ($params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                }

                break;
            case 'DELETE':
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

        if ($response =  json_decode(curl_exec($curl), true)) {
            return $response;
        }

        throw new Error('Returned empty content (' . $method . ' ' . $host . $url . ' ' . preg_replace('/\s{2,}/', ' ', preg_replace('/[\x00-\x1F\x7F ]/', '', print_r($params, true))));
    }
}
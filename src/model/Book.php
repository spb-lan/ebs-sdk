<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 11:57
 */

namespace Lan\Ebs\Sdk\Model;

use Exception;
use Lan\Ebs\Sdk\Classes\Model;
use Lan\Ebs\Sdk\Client;

/**
 * @property mixed login
 * @property mixed email
 * @property mixed fio
 * @property mixed registered_at
 */
class Book extends Model
{
    const FIELD_NAME = 'name';

    public static $defaultFields = [
        Book::FIELD_NAME,
    ];

    /**
     * Book constructor.
     * @param Client $client
     * @param array $fields
     * @throws Exception
     */
    public function __construct(Client $client, array $fields = [])
    {
        parent::__construct($client, $fields);
    }

    /**
     * @param $method
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getUrl($method, array $params = [])
    {
        switch ($method) {
            case 'get':
                return [
                    'url' => vsprintf('/1.0/resource/book/get/%d', $params),
                    'method' => 'GET',
                    'code' => 200
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}
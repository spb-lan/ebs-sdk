<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 12:25
 */

namespace Lan\Ebs\Sdk\Collection;

use Exception;
use Lan\Ebs\Sdk\Classes\Collection;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Model\Book;

class BookCollection extends Collection
{
    /**
     * BookCollection constructor.
     * @param Client $client
     * @param array $fields
     * @param int $limit
     * @param int $offset
     * @throws Exception
     */
    public function __construct(Client $client, array $fields = [], $limit = 10, $offset = 0)
    {
        parent::__construct($client, $fields, Book::class, $limit, $offset);
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
            case 'load':
                return [
                    'url' => '/1.0/resource/book',
                    'method' => 'GET',
                    'code' => '200'
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}
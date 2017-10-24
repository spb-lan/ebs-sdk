<?php
/**
 * Class UserCollection
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk\Collection;

use Exception;
use Lan\Ebs\Sdk\Classes\Collection;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Model\User;

/**
 * Коллекция пользователей
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 * @category     Collection
 */
class UserCollection extends Collection
{
    /**
     * UserCollection constructor.
     *
     * @param Client $client
     * @param array $fields
     * @param int $limit
     * @param int $offset
     * @throws Exception
     */
    public function __construct(Client $client, array $fields = [], $limit = 10, $offset = 0)
    {
        parent::__construct($client, $fields, User::class, $limit, $offset);
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
            case 'load':
                return [
                    'url' => '/1.0/security/user',
                    'method' => 'GET',
                    'code' => '200'
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}
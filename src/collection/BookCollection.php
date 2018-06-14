<?php
/**
 * Class BookCollection
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk\Collection;

use Exception;
use Lan\Ebs\Sdk\Classes\Collection;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Model\Book;

/**
 * Коллекция книг
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 * @category     Collection
 */
class BookCollection extends Collection
{
    /**
     * Конструктор коллекции книг
     *
     * @param Client $client Инстанс клиента
     * @param array $fields Поля для выборки
     * @param int $limit Лимит выборки моделей книг
     * @param int $offset Смещение выборки моделей книг
     *
     * @throws Exception
     */
    public function __construct(Client $client, array $fields = array(), $limit = 10, $offset = 0)
    {
        parent::__construct($client, $fields, Book::class, $limit, $offset);
    }

    /**
     * /**
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
            case 'load':
                return array(
                    'url' => '/1.0/resource/book',
                    'method' => 'GET',
                    'code' => '200'
                );
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}
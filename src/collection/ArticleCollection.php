<?php
/**
 * Class ArticleCollection
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk\Collection;

use Exception;
use Lan\Ebs\Sdk\Classes\Collection;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Model\Article;

/**
 * Коллекция статей
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 * @category     Collection
 */
class ArticleCollection extends Collection
{
    /**
     * Идентификатор выпуска
     *
     * @var int
     */
    private $issueId = null;

    /**
     * BookCollection constructor.
     * @param $issueId
     * @param Client $client
     * @param array $fields
     * @param int $limit
     * @param int $offset
     * @throws Exception
     */
    public function __construct($issueId, Client $client, array $fields = array(), $limit = 10, $offset = 0)
    {
        parent::__construct($client, $fields, Article::class, $limit, $offset);

        $this->issueId = $issueId;
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
    public function getUrl($method, array $params = array())
    {
        switch ($method) {
            case 'load':
                return array(
                    'url' => '/1.0/resource/journal/issue/' . ((int)$this->issueId),
                    'method' => 'GET',
                    'code' => '200'
                );
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}
<?php
/**
 * Class IssueCollection
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk\Collection;

use Exception;
use Lan\Ebs\Sdk\Classes\Collection;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Model\Issue;

/**
 * Коллекция выпусков
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 * @category     Collection
 */
class IssueCollection extends Collection
{
    /**
     * Идентификатор журнала
     *
     * @var int
     */
    private $journalId = null;

    /**
     * BookCollection constructor.
     * @param $journalId
     * @param Client $client
     * @param array $fields
     * @param int $limit
     * @param int $offset
     * @throws Exception
     */
    public function __construct($journalId, Client $client, array $fields = array(), $limit = 10, $offset = 0)
    {
        parent::__construct($client, $fields, Issue::class, $limit, $offset);

        $this->journalId = $journalId;
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
                    'url' => '/1.0/resource/journal/' . ((int)$this->journalId),
                    'method' => 'GET',
                    'code' => '200'
                );
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}
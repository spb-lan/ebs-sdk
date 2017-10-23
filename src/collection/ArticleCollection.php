<?php

namespace Lan\Ebs\Sdk\Collection;

use Exception;
use Lan\Ebs\Sdk\Classes\Collection;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Model\Article;

class ArticleCollection extends Collection
{
    private $issueId = null;

    /**
     * BookCollection constructor.
     * @param Client $client
     * @param array $fields
     * @param int $limit
     * @param int $offset
     * @throws Exception
     */
    public function __construct($issueId, Client $client, array $fields = [], $limit = 10, $offset = 0)
    {
        parent::__construct($client, $fields, Article::class, $limit, $offset);

        $this->issueId = $issueId;
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
                    'url' => '/1.0/resource/journal/issue/' . ((int)$this->issueId),
                    'method' => 'GET',
                    'code' => '200'
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}
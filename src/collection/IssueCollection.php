<?php

namespace Lan\Ebs\Sdk\Collection;

use Exception;
use Lan\Ebs\Sdk\Classes\Collection;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Model\Issue;

class IssueCollection extends Collection
{
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
    public function __construct($journalId, Client $client, array $fields = [], $limit = 10, $offset = 0)
    {
        parent::__construct($client, $fields, Issue::class, $limit, $offset);

        $this->journalId = $journalId;
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
                    'url' => '/1.0/resource/journal/' . ((int)$this->journalId),
                    'method' => 'GET',
                    'code' => '200'
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}
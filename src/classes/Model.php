<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 12:30
 */

namespace Lan\Ebs\Sdk\Classes;

use Error;
use Lan\Ebs\Sdk\Client;
use Monolog\Logger;

abstract class Model
{
    private $client;

    private $fields = [];

    private $data = null;

    private $id = null;

    private $logger = null;

    /**
     * Model constructor.
     * @param Client $client
     * @param $fields
     * @throws Error
     */
    public function __construct(Client $client, array $fields)
    {
        if (!$client) {
            throw new Error('Client not defined');
        }

        if (!is_array($fields)) {
            throw new Error('Fields for model of collection mast be array');
        }

        $this->client = $client;
        $this->fields = $fields;
    }

    public function set(array $data)
    {
        $this->data = array_merge(
            (array)$this->data,
            array_intersect_key($data, array_flip($this->fields ? $this->fields : $this->getDefaultFields()))
        );

        if (!empty($this->data['id'])) {
            $this->id = $this->data['id'];
        }
    }

    abstract protected function getDefaultFields();

    abstract protected function getUrl($method);

    public function get($id = null)
    {
        if (empty($id)) {
            if ($this->data === null) {
                throw new Error('Id is required');
            }
        } else {
            if ($this->data === null || $id != $this->id) {
                $this->data = $this->client->getResponse($this->getUrl(__FUNCTION__), $this->fields)['data'];

                if (!empty($this->data['id'])) {
                    $this->id = $this->data['id'];
                }
            }
        }

        return $this->data;
    }

    protected function getLogger()
    {
        if ($this->logger === null) {
            $this->logger = new Logger(get_class($this));
        }

        return $this->logger;
    }
}
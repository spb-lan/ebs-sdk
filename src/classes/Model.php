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
    const MESSAGE_ID_REQUIRED = 'Id is required';
    const MESSAGE_ID_CAN_NOT_CHANGED = 'Id can not changed';

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

    /**
     * Set data to model
     *
     * @param array $data
     * @return $this
     * @throws Error
     */
    public function set(array $data)
    {
        if ($this->id === null) {
            if (empty($data['id'])) {
                throw new Error(Model::MESSAGE_ID_REQUIRED);
            }
        } else {
            if (!empty($data['id']) && $data['id'] != $this->id) {
                throw new Error(Model::MESSAGE_ID_CAN_NOT_CHANGED);
            }
        }

        $class = get_class($this);

        $this->data = array_merge(
            (array)$this->data,
            array_intersect_key($data, array_flip(array_merge($this->getFields(), ['id'])))
        );

        $this->id = $this->data['id'];

        return $this;
    }

    abstract protected function getUrl($method, array $params = []);

    public function get($id = null)
    {
        if (empty($id)) {
            if ($this->data === null) {
                throw new Error(Model::MESSAGE_ID_REQUIRED);
            }
        } else {
            if ($this->data === null || $id != $this->id) {
                $this->data = $this->client->getResponse($this->getUrl(__FUNCTION__, [$id]), $this->getFields())['data'];

                if (!empty($this->data['id'])) {
                    $this->id = $this->data['id'];
                }
            }
        }

        return $this->data;
    }

    private function post($data)
    {

    }

    protected function getLogger()
    {
        if ($this->logger === null) {
            $this->logger = new Logger(get_class($this));
        }

        return $this->logger;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFields()
    {
        $class = get_class($this);

        return array_merge(['id'], $this->fields ? $this->fields : $class::$defaultFields);
    }
}
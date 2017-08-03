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

abstract class Model
{
    const MESSAGE_ID_REQUIRED = 'Id is required';
    const MESSAGE_ID_CAN_NOT_CHANGED = 'Id can not be changed';

    private $client;

    private $fields = [];

    private $data = null;

    private $id = null;

    private $lastStatus = 0;

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
     * @param null $status
     * @return $this
     * @throws Error
     */
    public function set(array $data, $status = null)
    {
        if (empty($data['id']) && empty($this->id)) {
            throw new Error(Model::MESSAGE_ID_REQUIRED);
        }

        if (!empty($data['id']) && !empty($this->id) && $data['id'] != $this->id) {
            throw new Error(Model::MESSAGE_ID_CAN_NOT_CHANGED);
        }

        $this->data = array_merge(
            (array)$this->data,
            array_intersect_key($data, array_flip(array_merge($this->getFields(), ['id'])))
        );

        $this->id = $this->data['id'];

        if ($status) {
            $this->lastStatus = $status;
        }

        return $this;
    }

    abstract protected function getUrl($method, array $params = []);

    public function get($id = null)
    {
        if ($id === null && $this->id !== null) {
            return $this->data;
        }

        $this->set(['id' => $id]);

        $response = $this->client->getResponse($this->getUrl(__FUNCTION__, [$this->getId()]), $this->getFields());

        $this->set($response['data'], $response['status']);

        return $this->data;
    }

    public function post(array $data)
    {
        $response = $this->client->getResponse($this->getUrl(__FUNCTION__), $data);

        $this->set($response['data'], $response['status']);

        return $this;
    }

    public function put(array $data)
    {
        $this->set($data);

        $response = $this->client->getResponse($this->getUrl(__FUNCTION__, [$this->getId()]), $data);

        $this->set($response['data'], $response['status']);

        return $this;
    }

    public function delete($id = null)
    {
        if (empty($this->id)) {
            $this->set(['id' => $id]);
        }

        $response = $this->client->getResponse($this->getUrl(__FUNCTION__, [$this->getId()]));

        $this->set($response['data'], $response['status']);

        return $this;
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

    public function __get($name)
    {
        if ($this->data === null || !array_key_exists($name, $this->data)) {
            throw new Error('Param ' . $name . ' not defined for ' . get_class($this));
        }

        return $this->data[$name];
    }
}
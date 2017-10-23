<?php

namespace Lan\Ebs\Sdk\Classes;

use Exception;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Common;

abstract class Model implements Common
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
     *
     * @param  Client $client
     * @param  $fields
     * @throws Exception
     */
    public function __construct(Client $client, array $fields)
    {
        if (!$client) {
            throw new Exception('Client not defined');
        }

        if (!is_array($fields)) {
            throw new Exception('Fields for model of collection mast be array');
        }

        $this->client = $client;
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function post(array $data)
    {
        $response = $this->getClient()->getResponse($this->getUrl(__FUNCTION__), $data);

        $this->set($response['data'], $response['status']);

        return $this;
    }

    /**
     * Set data to model
     *
     * @param  array $data
     * @param  null $status
     * @return $this
     * @throws Exception
     */
    public function set(array $data, $status = null)
    {
        if (empty($data['id']) && empty($this->getId())) {
            throw new Exception(Model::MESSAGE_ID_REQUIRED);
        }

        if (!empty($data['id']) && !empty($this->getId()) && $data['id'] != $this->getId()) {
            throw new Exception(Model::MESSAGE_ID_CAN_NOT_CHANGED);
        }

        if (!empty($data['id'])) {
            $this->setId($data['id']);
        }

        $this->data = array_merge((array)$this->data, $data);

        if ($status) {
            $this->lastStatus = $status;
        }

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function put(array $data)
    {
        $this->set($data);

        $response = $this->getClient()->getResponse($this->getUrl(__FUNCTION__, [$this->getId()]), $data);

        $this->set($response['data'], $response['status']);

        return $this;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return Model
     * @throws Exception
     */
    public function setId($id)
    {
        return $this->id = $id;
    }

    /**
     * @param null $id
     * @return $this
     * @throws Exception
     */
    public function delete($id = null)
    {
        if (empty($this->getId())) {
            $this->set(['id' => $id]);
        }

        $response = $this->getClient()->getResponse($this->getUrl(__FUNCTION__, [$this->getId()]));

        $this->set($response['data'], $response['status']);

        return $this;
    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        $data = $this->get();

        if (!array_key_exists($name, $data)) {
            throw new Exception('Поле ' . $name . ' не указано при создвнии объекта модели ' . get_class($this) . ' (см. 2-й аргумент)');
        }

        return $data[$name];
    }

    /**
     * @param null $id
     * @return null
     * @throws Exception
     */
    public function get($id = null)
    {
        if ($id === null && $this->getId() !== null) {
            return $this->data;
        }

        if (!$id) {
            throw new Exception(Model::MESSAGE_ID_REQUIRED);
        }

        $this->setId($id);

        $params = $this->fields ? ['fields' => implode(',', $this->fields)] : [];

        $response = $this->getClient()->getResponse($this->getUrl(__FUNCTION__, [$this->getId()]), $params);

        $this->set($response['data'], $response['status']);

        return $this->data;
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        return $this->client;
    }
}
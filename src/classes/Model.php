<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 12:30
 */

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

    public function setId($id) {
        return $this->set(['id' => $id]);
    }

    public function get($id = null)
    {
        if ($id === null && $this->id !== null) {
            return $this->data;
        }

        $this->setId($id);

        $params = $this->fields ? ['fields' => implode(',', $this->fields)] : [];

        $response = $this->client->getResponse($this->getUrl(__FUNCTION__, [$this->getId()]), $params);

        $this->set($response['data'], $response['status']);

        return $this->data;
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
        if (empty($data['id']) && empty($this->id)) {
            throw new Exception(Model::MESSAGE_ID_REQUIRED);
        }

        if (!empty($data['id']) && !empty($this->id) && $data['id'] != $this->id) {
            throw new Exception(Model::MESSAGE_ID_CAN_NOT_CHANGED);
        }

        $this->data = array_merge((array)$this->data, $data);

        $this->id = $this->data['id'];

        if ($status) {
            $this->lastStatus = $status;
        }

        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getId()
    {
        return $this->id;
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

    public function __get($name)
    {
        $data = $this->get();

        if (!array_key_exists($name, $data)) {
            throw new Exception('Param ' . $name . ' not defined for ' . get_class($this));
        }

        return $data[$name];
    }
}
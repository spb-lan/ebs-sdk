<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 11:56
 */

namespace Lan\Ebs\Sdk\Classes;

use ArrayObject;
use Exception;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Common;

abstract class Collection extends ArrayObject implements Common
{
    private $client;

    private $loadStatus = 0;

    private $fields = [];

    private $class = null;

    private $limit = null;

    private $offset = null;

    /**
     * Collection constructor.
     * @param Client $client
     * @param array $fields
     * @param $class
     * @param int $limit
     * @param int $offset
     * @throws Exception
     */
    public function __construct(Client $client, array $fields, $class, $limit, $offset)
    {
        if (!$client) {
            throw new Exception('Client not defined');
        }

        if (!is_array($fields)) {
            throw new Exception('Fields for model of collection mast be array');
        }

        if (!is_subclass_of($class, Model::class)) {
            throw new Exception('Class of model collection not subclass for Model');
        }

        $this->client = $client;
        $this->fields = $fields;
        $this->class = $class;

        $this->setLimit($limit);
        $this->setOffset($offset);
    }

    public function getIterator()
    {
        return new CollectionIterator($this);
    }

    /**
     * @param bool $force
     * @return $this
     */
    public function load($force = false)
    {
        if ($this->loadStatus == 200 && !$force) {
            return $this;
        }

        $params = [
            'limit' => $this->limit,
            'offset' => $this->offset
        ];

        if ($this->fields) {
            $params['fields'] = implode(',', $this->fields);
        }

        $response = $this->client->getResponse($this->getUrl(__FUNCTION__), $params);

        $this->exchangeArray($response['data']);

        $this->loadStatus = $response['status'];

        unset($response);

        return $this;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        if ($this->loadStatus == 200) {
            $this->load(true);
        }
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        if ($this->loadStatus == 200) {
            $this->load(true);
        }
    }

    /**
     * @param array $data
     * @return Model
     */
    public function createModel(array $data = null)
    {
        $class = $this->class;

        /** @var Model $model */
        $model = new $class($this->client, $this->fields);

        $model->set($data === null ? current($this) : $data);

        return $model;
    }

//    public function getFields()
//    {
//        $class = $this->class;
//
//        return array_merge(['id'], $this->fields ? $this->fields : $class::$defaultFields);
//    }

    public function count() {
        $this->load();

        return parent::count();
    }

    public function getData()
    {
        $this->load();

        return $this->getArrayCopy();
    }

    public function reset()
    {
        $this->load();

        return $this->createModel(reset($this));
    }

    public function end()
    {
        $this->load();

        return $this->createModel(end($this));
    }
}
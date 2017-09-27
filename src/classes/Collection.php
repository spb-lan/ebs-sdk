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
use ReflectionClass;

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
     *
     * @param  Client $client
     * @param  array $fields
     * @param  string $class
     * @param  int $limit
     * @param  int $offset
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

        $reflectionClass = new ReflectionClass($class);

        if (!$reflectionClass->isSubclassOf(Model::class)) {
            throw new Exception('Class of model collection not subclass for Model');
        }

        $this->client = $client;
        $this->fields = $fields;
        $this->class = $class;

        $this->setLimit($limit);
        $this->setOffset($offset);
    }

    /**
     * @param int $limit
     * @throws Exception
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        if ($this->loadStatus == 200) {
            $this->load(true);
        }
    }

    /**
     * @param bool $force
     * @return $this
     * @throws Exception
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
            $params['fields'] = implode(',', (array)$this->fields);
        }

        $response = $this->client->getResponse($this->getUrl(__FUNCTION__), $params);

        $this->exchangeArray($response['data']);

        $this->loadStatus = $response['status'];

        unset($response);

        return $this;
    }

    /**
     * @param int $offset
     * @throws Exception
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        if ($this->loadStatus == 200) {
            $this->load(true);
        }
    }

    public function getIterator()
    {
        return new CollectionIterator($this);
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $this->load();

        return parent::count();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        $this->load();

        return $this->getArrayCopy();
    }

    /**
     * @return Model
     * @throws Exception
     */
    public function reset()
    {
        $this->load();

        return $this->createModel(reset($this));
    }

    /**
     * @param array $data
     * @return Model
     * @throws Exception
     */
    public function createModel(array $data = null)
    {
        $class = $this->class;

        /**
         * @var Model $model
         */
        $model = new $class($this->client, $this->fields);

        $model->set($data === null ? current($this) : $data);

        return $model;
    }

    /**
     * @return Model
     * @throws Exception
     */
    public function end()
    {
        $this->load();

        return $this->createModel(end($this));
    }
}
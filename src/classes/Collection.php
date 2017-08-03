<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 11:56
 */

namespace Lan\Ebs\Sdk\Classes;

use ArrayObject;
use Error;
use Lan\Ebs\Sdk\Client;

abstract class Collection extends ArrayObject
{
    private $client;

    private $fields = [];

    private $class = null;

    private $result = null;

    private $limit = null;

    private $offset = null;

    /**
     * Collection constructor.
     * @param Client $client
     * @param array $fields
     * @param $class
     * @param int $limit
     * @param int $offset
     * @throws Error
     */
    public function __construct(Client $client, array $fields, $class, $limit, $offset)
    {
        if (!$client) {
            throw new Error('Client not defined');
        }

        if (!is_array($fields)) {
            throw new Error('Fields for model of collection mast be array');
        }

        if (!is_subclass_of($class, Model::class)) {
            throw new Error('Class of model collection not subclass for Model');
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
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
//    public function offsetGet($offset) {
//        return $this->offsetExists($offset) ? $this->createModel($this->result['data'][$offset]) : null;
//    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @throws Error
     * @since 5.0.0
     */
//    public function offsetSet($offset, $value) {
//        if (!($value instanceof Model)) {
//            throw new Error('Value mast be instance of Model class');
//        }
//
//        if (is_null($offset)) {
//            $this->result['data'][] = $value;
//        } else {
//            $this->result['data'][$offset] = $value->get();
//        }
//    }

    /**
     * @return $this
     */
    public function load($force = false)
    {
        if ($this->result !== null && !$force) {
            return $this;
        }

        $params = [
            'limit' => $this->limit,
            'offset' => $this->offset
        ];

        if ($this->fields) {
            $params['fields'] = implode(',', $this->fields);
        }

        $this->result = $this->client->getResponse($this->getRequest(), $params);

        $this->exchangeArray($this->result['data']);

        return $this;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        if ($this->result !== null) {
            $this->load(true);
        }
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        if ($this->result !== null) {
            $this->load(true);
        }
    }

    public function rewind()
    {
        $this->load();
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

    abstract protected function getRequest();

    public function getFields()
    {
        $class = $this->class;

        return array_merge(['id'], $this->fields ? $this->fields : $class::$defaultFields);
    }

    public function getData() {
        return $this->result === null ? [] : $this->result['data'];
    }

    public function reset() {
        $this->load();

        return $this->createModel(reset($this));
    }

    public function end() {
        $this->load();

        return $this->createModel(end($this));
    }
}
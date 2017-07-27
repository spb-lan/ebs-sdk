<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 11:56
 */

namespace Lan\Ebs\Sdk\Classes;

use Error;
use Iterator;
use Lan\Ebs\Sdk\Client;
use Monolog\Logger;

abstract class Collection implements Iterator
{
    private $client;

    private $fields = [];

    private $class = null;

    private $result = null;

    private $limit = 10;

    private $offset = 0;

    private $logger = null;

    /**
     * Collection constructor.
     * @param Client $client
     * @param array $fields
     * @param $class
     * @throws Error
     */
    public function __construct(Client $client, array $fields, $class)
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
    }

    private function load()
    {
        $params = [
            'limit' => $this->limit,
            'offset' => $this->offset
        ];

        if ($this->fields) {
            $params['fields'] = implode(',', $this->fields);
        }

        $this->result = $this->client->getResponse($this->getRequest(), $params);
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;

        if ($this->result !== null) {
            $this->load();
        }
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset)
    {
        $this->offset = $offset;

        if ($this->result !== null) {
            $this->load();
        }
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return Model
     */
    public function current()
    {
        if ($this->result === null) {
            $this->load();
        }

        return $this->createModel(current($this->result['data']));
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return Model
     */
    public function next()
    {
        if ($this->result === null) {
            $this->load();
        }

        return next($this->result['data']);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return integer
     */
    public function key()
    {
        if ($this->result === null) {
            $this->load();
        }

        return key($this->result['data']);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid()
    {
        $key = $this->key();

        return ($key !== NULL && $key !== FALSE);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind()
    {
        if ($this->result === null) {
            $this->load();
        }

        reset($this->result['data']);
    }

    /**
     * @param array $data
     * @return Model
     */
    private function createModel(array $data) {
        $class = $this->class;

        /** @var Model $model */
        $model = new $class($this->client, $this->fields);

        $model->set($data);

        return $model;
    }

    abstract protected function getRequest();

    protected function getLogger() {
        if ($this->logger === null) {
            $this->logger = new Logger(get_class($this));
        }

        return $this->logger;
    }

    public function getFields()
    {
        $class = $this->class;

        return array_merge(['id'], $this->fields ? $this->fields : $class::$defaultFields);
    }
}
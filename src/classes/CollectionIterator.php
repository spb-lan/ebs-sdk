<?php
/**
 * Class CollectionIterator
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk\Classes;

use ArrayIterator;

/**
 * Итератор коллекции
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 * @category     Classes
 */
class CollectionIterator extends ArrayIterator
{
    /**
     * Инстанс коллекции
     *
     * @var Collection
     */
    private $collection;


    /**
     * Данные коллекции
     *
     * @var array
     */
    private $data = array();

    /**
     * Конструктор итератора
     *
     * @param Collection $collection Коллекция
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Return the current element
     *
     * @link   http://php.net/manual/en/iterator.current.php
     * @return Model
     * @throws \Exception
     */
    public function current()
    {
        return $this->collection->createModel(current($this->data));
    }

    /**
     * Move forward to next element
     *
     * @link   http://php.net/manual/en/iterator.next.php
     * @return Model
     */
    public function next()
    {
        return next($this->data);
    }

    /**
     * Checks if current position is valid
     *
     * @link   http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid()
    {
        $key = $this->key();

        return ($key !== null && $key !== false);
    }

    /**
     * Return the key of the current element
     *
     * @link   http://php.net/manual/en/iterator.key.php
     * @return integer
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link   http://php.net/manual/en/iterator.rewind.php
     * @return void
     * @throws \Exception
     */
    public function rewind()
    {
        $this->data = $this->collection->load()->getData();

        reset($this->data);
    }
}
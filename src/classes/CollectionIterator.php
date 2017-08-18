<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 31.07.17
 * Time: 16:37
 */

namespace Lan\Ebs\Sdk\Classes;

use ArrayIterator;

class CollectionIterator extends ArrayIterator
{
    /**
     * @var Collection
     */
    private $collection;

    private $data = [];

    /**
     * CollectionIterator constructor.
     *
     * @param Collection $collection
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
     */
    public function rewind()
    {
        $this->data = $this->collection->load()->getData();

        reset($this->data);
    }
}
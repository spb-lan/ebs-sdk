<?php
/**
 * Class BookCollection
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk\Classes;

use ArrayObject;
use Exception;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Common;
use ReflectionClass;

/**
 * Абстрактный класс для всех коллекций (+ итерируемый)
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 * @category     Classes
 */
abstract class Collection extends ArrayObject implements Common
{
    /**
     * Инстанс клиента API
     *
     * @var Client
     */
    private $client;

    /**
     * Флаг, сигнализирующий что коллекция загружена
     *
     * @var int
     */
    private $loadStatus = 0;

    /**
     * Имена полей, подлежаших получению через API
     *
     * @var array
     */
    private $fields = [];

    /**
     * Класс модели
     *
     * @var Model|string
     */
    private $class = null;

    /**
     * Лимит получаемых моделей коллекции через API
     *
     * @var int
     */
    private $limit = null;

    /**
     * Смещение выборки моделей через API
     *
     * @var int
     */
    private $offset = null;

    /**
     * Всего элементов без учета лимита
     *
     * @var int
     */
    private $fullCount = null;

    /**
     * Конструктор коллекции
     *
     * @param Client $client Инстанс клиента
     * @param array $fields Поля для выборки
     * @param string $class Класс модели
     * @param int $limit Лимит выборки
     * @param int $offset Смещение выборки
     *
     * @throws Exception
     */
    public function __construct(Client $client, array $fields, $class, $limit, $offset)
    {
        if (!$client) {
            throw new Exception('Клиент не инициализирован');
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
     * Установка лимита выборки
     *
     * @param int $limit Значение лимита выборки
     *
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
     * Загрузка коллекции
     *
     * @param bool $force Заново загружать коллекцию даже если она загружена ранее
     *
     * @return $this
     *
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

        if (!empty($this->fields)) {
            $params['fields'] = implode(',', (array)$this->fields);
        }

        $response = $this->client->getResponse($this->getUrl(__FUNCTION__), $params);

        $this->exchangeArray($response['data']);

        $this->loadStatus = $response['status'];

        $this->fullCount = $response['count'];

        unset($response);

        return $this;
    }

    /**
     * Установка смещения выборки
     *
     * @param int $offset Значение смещения выборки
     *
     * @throws Exception
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        if ($this->loadStatus == 200) {
            $this->load(true);
        }
    }

    /**
     * Получение количества всех элементов без учета лимита
     *
     * @return int
     *
     * @throws Exception
     */
    public function getFullCount()
    {
        $this->load();

        return $this->fullCount;
    }

    /**
     * Получение нового инстанса итератора коллекции
     *
     * @return CollectionIterator
     */
    public function getIterator()
    {
        return new CollectionIterator($this);
    }

    /**
     * Количество моделей в коллекции
     *
     * @return int
     *
     * @throws Exception
     */
    public function count()
    {
        $this->load();

        return parent::count();
    }

    /**
     * Получение коллекции в виде массива
     *
     * @return array
     *
     * @throws Exception
     */
    public function getData()
    {
        $this->load();

        return $this->getArrayCopy();
    }

    /**
     * Получение первой модели в коллекции
     *
     * @return Model
     *
     * @throws Exception
     */
    public function reset()
    {
        $this->load();

        return $this->createModel(reset($this));
    }

    /**
     * Создание модели по переданным данным
     *
     * @param array $data Данные для создания модели
     *
     * @return Model
     *
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
     * Получение последней модели в коллекции
     *
     * @return Model
     *
     * @throws Exception
     */
    public function end()
    {
        $this->load();

        return $this->createModel(end($this));
    }

    /**
     * Получение инстанса клиента
     *
     * @return Client
     */
    protected function getClient()
    {
        return $this->client;
    }
}
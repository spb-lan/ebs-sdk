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
use Lan\Ebs\Sdk\Collection\ArticleCollection;
use Lan\Ebs\Sdk\Collection\IssueCollection;
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
    private $fields = array();

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
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     *
     *      $bookCollection = new BookCollection($client, [], '\Lan\Ebs\Sdk\Model\Book', 5, 10); // инициализация коллекции книг
     * ```
     *
     * @throws Exception
     *
     * @see ArticleCollection::__construct
     * @see BookCollection::__construct
     * @see IssueCollection::__construct
     * @see JournalCollection::__construct
     * @see UserCollection::__construct
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
     * Установка/изменение лимита выборки коллекции. Вызывет перезагрузку коллекции если значение лимита изменилось
     *
     * @param int $limit Значение лимита выборки
     *
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     *
     *      $bookCollection = new BookCollection($client, [], '\Lan\Ebs\Sdk\Model\Book', 5, 100); // инициализация коллекции книг
     *
     *      $bookCollection->setLimit(100); // изменение лимита с 5 до 100
     * ```
     *
     * @return $this
     *
     * Пример:
     * ```php
     *      echo $bookCollection->setLimit(100)->count(); // Выведет количество элементов коллекции
     * ```
     *
     * @throws Exception
     */
    public function setLimit($limit)
    {
        $isChanged = $this->limit != $limit;

        $this->limit = $limit;

        if ($isChanged && $this->loadStatus == 200) {
            $this->load(true);
        }

        return $this;
    }

    /**
     * Загрузка коллекции
     *
     * Загрузка осуществляется только после вызова зависимого метода (ленивая загрузка)
     *
     * @param bool $force Заново загружать коллекцию даже если она загружена ранее
     *
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     *
     *      $bookCollection = new BookCollection($client, [], '\Lan\Ebs\Sdk\Model\Book', 5, 100); // инициализация коллекции книг
     *
     *      // Вызова метода загрузки еще небыло.. коллекция пустая
     *      $bookCollection->load(); // загрузка коллекции данными
     *      // Запрос к API выполнен.. коллекция загружена
     * ```
     *
     * @return $this Вернет текущую коллекцию
     *
     * Пример:
     * ```php
     *       echo $bookCollection->load()->count(); // Выведет количество элементов коллекции
     * ```
     *
     * @throws Exception
     *
     * @see Collection::setLimit()
     * @see Collection::setOffset()
     * @see Collection::getFullCount()
     * @see Collection::getCount()
     * @see Collection::getData()
     * @see Collection::reset()
     * @see Collection::end()
     * @see CollectionIterator::rewind()
     */
    public function load($force = false)
    {
        if ($this->loadStatus == 200 && !$force) {
            return $this;
        }

        $params = array(
            'limit' => $this->limit,
            'offset' => $this->offset
        );

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
     * Установка/изменение смещения выборки коллекции. Вызывет перезагрузку коллекции если значение смещения изменилось
     *
     * @param int $offset Значение смещения выборки
     *
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     *
     *      $bookCollection = new BookCollection($client, [], '\Lan\Ebs\Sdk\Model\Book', 5, 100); // инициализация коллекции книг
     *
     *      $bookCollection->setOffset(1000); // изменение смещения со 100 до 1000
     * ```
     *
     * @return $this Вернет текущую коллекцию
     *
     * Пример:
     * ```php
     *      echo $bookCollection->setOffset(1000)->count(); // Выведет количество элементов коллекции
     * ```
     *
     * @throws Exception
     */
    public function setOffset($offset)
    {
        $isChanged = $this->offset != $offset;

        $this->offset = $offset;

        if ($isChanged && $this->loadStatus == 200) {
            $this->load(true);
        }

        return $this;
    }

    /**
     * Получение количества всех элементов без учета лимита
     *
     * Вернет общее количество элементов необходимое, например, для вычисления постраничной выборки
     *
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     *
     *      $bookCollection = new BookCollection($client, [], '\Lan\Ebs\Sdk\Model\Book', 5, 100); // инициализация коллекции книг
     *
     *      echo $bookCollection->getFullCount(); // Вернет количество элементов без учета лимита
     * ```
     *
     * @return int
     *
     * Пример:
     * ```php
     *      echo $bookCollection>getFullCount(); // Вернет, например, 286
     *      echo $bookCollection->сount(); // Вернет, например, 5
     *      // Итого получаем ceil($bookCollection>getFullCount() /  $bookCollection>getLimit()) = 58 страниц
     * ```
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
     * Итератор для реализации итерируемой коллекции
     *
     * @return CollectionIterator Вернет экзепляря итератора текущей коллекции
     */
    public function getIterator()
    {
        return new CollectionIterator($this);
    }

    /**
     * Количество моделей в коллекции
     *
     * Фактическое число моделей в коллекции
     *
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     *
     *      $bookCollection = new BookCollection($client, [], '\Lan\Ebs\Sdk\Model\Book', 5, 100); // инициализация коллекции книг
     *
     *      echo $bookCollection>getFullCount(); // Вернет количество элементов без учета лимита
     * ```
     *
     * @return int
     *
     * Пример:
     * ```php
     *      echo $bookCollection->сount(); // Вернет, например, 5 (но не больше 5 - фактическое количество)
     * ```
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
     * Выгрузка коллекции в массив
     *
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     *
     *      $bookCollection = new BookCollection($client, [], '\Lan\Ebs\Sdk\Model\Book', 1, 10); // инициализация коллекции книг
     *
     *      print_r($bookCollection->getData()); // Вернет количество элементов без учета лимита
     * ```
     *
     * @return array
     *
     * Пример:
     * ```
     * [
     *      'id' => 22445,
     *      'name' => 'Свадьба. Сцена в одном действии',
     *      'authors' => 'Чехов А.П.'
     * ]
     * ```
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
     * Вернет первую модель коллекции.
     *
     * @return Model
     *
     * @throws Exception
     */
    public function reset()
    {
        $this->load();

        $data = reset($this);

        return $data ? $this->createModel($data) : null;
    }

    /**
     * Создание модели по переданным данным
     *
     * Создает модель по ее данным
     *
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     *
     *      $bookCollection = new BookCollection($client, [], '\Lan\Ebs\Sdk\Model\Book', 1, 10); // инициализация коллекции книг
     *
     *      $bookData = [
     *          'id' => 22445,
     *          'name' => 'Свадьба. Сцена в одном действии',
     *          'authors' => 'Чехов А.П.'
     *      ];
     *
     *      $book = $bookCollection->createModel($bookData);
     * ```
     *
     * @param array $data Данные для создания модели
     *
     * @return Model Вернет модель на основе данных
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
     * Вернет полделнюю модель коллекции.
     *
     * @return Model
     *
     * @throws Exception
     */
    public function end()
    {
        $this->load();

        $data = end($this);

        return $data ? $this->createModel($data) : null;
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

    /**
     * Получение текущего лимита коллекции
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     *
     * Получение текущего смещения коллекции
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Общее количество страниц при заданом лимиите на страницу
     *
     * @return float
     * @throws Exception
     */
    public function getPages() {
        return ceil($this->getFullCount() /  $this->getLimit());
    }
}
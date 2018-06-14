<?php
/**
 * Class Model
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk\Classes;

use Exception;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Common;

/**
 *  Абстрактный класс моделей
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 * @category     Classes
 */
abstract class Model implements Common
{
    const MESSAGE_ID_REQUIRED = 'Id is required';
    const MESSAGE_ID_CAN_NOT_CHANGED = 'Id can not be changed';

    /**
     * Инстанс клиента API
     *
     * @var Client
     */
    private $client;

    /**
     * Имена полей, подлежаших получению через API
     *
     * @var array
     */
    private $fields = array();

    /**
     * Данные модели
     *
     * @var array
     */
    private $data = array();

    /**
     * Идентификатор модели
     *
     * @var null
     */
    private $id = null;

    /**
     * Статус последнего обращения по API
     *
     * @var int
     */
    private $lastStatus = 0;

    /**
     * Конструктор модели
     *
     * @param Client $client Инстанс клиента
     * @param array $fields Поля для выборки
     *
     * Пример:
     * ```php
     *      $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     *      $client = new Client($token); // инициализация клиента
     *
     *      $book = new Book($client, []); // инициализация модели книг
     * ```
     *
     * @throws Exception
     *
     * @see Article::__construct
     * @see Book::__construct
     * @see Issue::__construct
     * @see Journal::__construct
     * @see User::__construct
     */
    public function __construct(Client $client, array $fields)
    {
        if (!$client) {
            throw new Exception('Клиент не инициализирован');
        }

        if (!is_array($fields)) {
            throw new Exception('Fields for model of collection mast be array');
        }

        $this->client = $client;
        $this->fields = $fields;
    }

    /**
     * Загружаемые поля модели
     *
     * Те поля модели, которые будут получены по API
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Добавление новой записи по API
     *
     * Создание новой сущности
     *
     * @param array $data Устанавливаемые данные модели
     *
     * ```php
     * $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     * $client = new Client($token); // инициализация клиента
     *
     * $user = new User($client);
     * $user->post([
     *      'login' => 'new_user_login',
     *      'password' => 'new_user_password',
     *      'fio' => 'new_user_fio'
     * ]);
     * ```
     *
     * @return $this Возвращает модель с данными и вновь созданным идентификатором
     *
     * @throws Exception
     */
    public function post(array $data = array())
    {
        $response = $this->getClient()->getResponse($this->getUrl(__FUNCTION__), $data);

        $this->set($response['data'], $response['status']);

        return $this;
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
     * Установка данных модели
     *
     * Изменение данных модели
     *
     * @param  array $data Данные модели
     * @param  int $status Статус полученных данных
     *
     * ```php
     * $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     * $client = new Client($token); // инициализация клиента
     *
     * $user = new User($client);
     * $user->set([
     *      'login' => 'new_user_login',
     *      'password' => 'new_user_password',
     *      'fio' => 'new_user_fio'
     * ]);
     * $user->post();
     * ```
     *
     * @return $this Возвращает модель с данными и вновь созданным идентификатором
     *
     * @throws Exception
     */
    public function set(array $data, $status = null)
    {
        if (empty($data)) {
            return $this;
        }

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
     * Получение идентификатора модели
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Установка идентификатора модели
     *
     * @param int $id Идентификатор модели
     *
     * @return int
     *
     * @throws Exception
     */
    public function setId($id)
    {
        return $this->id = $id;
    }

    /**
     * Обновление записи по API
     *
     * @param array $data Обновляемые данные
     *
     * ```php
     * $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     * $client = new Client($token); // инициализация клиента
     *
     * $user = new User($client);
     * $user->setId($testUserPk);
     * $user->put([
     *      'fio' => 'user_new_fio',
     *      'password' => 'user_new_password',
     * ]);
     * ```
     *
     * @return $this Возвращает модель с данными и вновь созданным идентификатором
     *
     * @throws Exception
     */
    public function put(array $data = array())
    {
        $this->set($data);

        $response = $this->getClient()->getResponse($this->getUrl(__FUNCTION__, array($this->getId())), $data);

        $this->set($response['data'], $response['status']);

        return $this;
    }

    /**
     * Удаление модели
     *
     * @param int $id Идентификатор модели
     *
     * ```php
     * $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     * $client = new Client($token); // инициализация клиента
     *
     * $user = new User($client);
     * $user->delete($testUserPk);
     * ```
     *
     * @return $this Возвращает модель с данными и вновь созданным идентификатором
     *
     * @throws Exception
     */
    public function delete($id = null)
    {
        if (empty($this->getId())) {
            $this->set(array('id' => $id));
        }

        $response = $this->getClient()->getResponse($this->getUrl(__FUNCTION__, array($this->getId())));

        $this->set($response['data'], $response['status']);

        return $this;
    }

    /**
     * Магический Get
     *
     * @param mixed $name Имя поля
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function __get($name)
    {
        $data = $this->get();

        if (!array_key_exists($name, $data)) {
            throw new Exception('Поле ' . $name . ' не указано при создвнии объекта модели ' . get_class($this) . ' (см. 2-й аргумент fields)');
        }

        return $data[$name];
    }

    /**
     * Получение метаданных по идентификатору модели
     *
     * @param int $id Идентификатор модели
     *
     * ```php
     * $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
     *
     * $client = new Client($token); // инициализация клиента
     *
     * $user = new User($client);
     * $userData = $user->get($testUserPk);
     * ```
     *
     * @return array Получение метаданных модели
     *
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

        $response = $this->getClient()->getResponse($this->getUrl(__FUNCTION__, array($this->getId())), $params);

        $this->set($response['data'], $response['status']);

        return $this->data;
    }
}
<?php
/**
 * Class User
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk\Model;

use Exception;
use Lan\Ebs\Sdk\Classes\Model;
use Lan\Ebs\Sdk\Client;

/**
 * Модель пользователя
 *
 * @property mixed login
 * @property mixed email
 * @property mixed fio
 * @property mixed registeredAt
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 * @category     Model
 */
class User extends Model
{
    /**
     * Логин пользователя
     */
    const FIELD_LOGIN = 'login';

    /**
     * ФИО пользователя
     */
    const FIELD_FIO = 'fio';

    /**
     * Email пользователя
     */
    const FIELD_EMAIL = 'email';

    /**
     * Дата и время регистрации
     */
    const FIELD_REGISTERED = 'registeredAt';

    /**
     * Пароль пользователя
     */
    const FIELD_PASSWORD = 'password';

    /**
     * Конструктор модели пользователя
     *
     * @param Client $client Инстанс клиента
     * @param array $fields Поля для выборки
     *
     * @throws Exception
     */
    public function __construct(Client $client, array $fields = array())
    {
        parent::__construct($client, $fields);
    }

    /**
     * Получение данных для запроса через API
     *
     * @param string $method Http-метод запроса
     * @param array $params Параметры для формирования урла
     *
     * @return array
     *
     * @throws Exception
     */
    public function getUrl($method, array $params = array())
    {
        switch ($method) {
            case 'get':
                return array(
                    'url' => vsprintf('/1.0/security/user/get/%d', $params),
                    'method' => 'GET',
                    'code' => 200
                );
            case 'post':
                return array(
                    'url' => '/1.0/security/user/post',
                    'method' => 'POST',
                    'code' => 201
                );
            case 'put':
                return array(
                    'url' => vsprintf('/1.0/security/user/put/%d', $params),
                    'method' => 'PUT',
                    'code' => 200
                );
            case 'delete':
                return array(
                    'url' => vsprintf('/1.0/security/user/delete/%d', $params),
                    'method' => 'DELETE',
                    'code' => 200
                );
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}
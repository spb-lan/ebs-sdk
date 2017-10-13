<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 11:57
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
 * @property mixed registered_at
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
    const FIELD_REGISTERED = 'registered_at';

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
    public function __construct(Client $client, array $fields = [])
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
    public function getUrl($method, array $params = [])
    {
        switch ($method) {
            case 'get':
                return [
                    'url' => vsprintf('/1.0/security/user/get/%d', $params),
                    'method' => 'GET',
                    'code' => 200
                ];
            case 'post':
                return [
                    'url' => '/1.0/security/user/post',
                    'params' => [],
                    'method' => 'POST',
                    'code' => 201
                ];
            case 'put':
                return [
                    'url' => vsprintf('/1.0/security/user/put/%d', $params),
                    'method' => 'PUT',
                    'code' => 200
                ];
            case 'delete':
                return [
                    'url' => vsprintf('/1.0/security/user/delete/%d', $params),
                    'method' => 'DELETE',
                    'code' => 200
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}
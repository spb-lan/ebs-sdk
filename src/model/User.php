<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 11:57
 */

namespace Lan\Ebs\Sdk\Model;

use Error;
use Lan\Ebs\Sdk\Classes\Model;
use Lan\Ebs\Sdk\Client;

class User extends Model
{
    const FIELD_LOGIN = 'login';
    const FIELD_EMAIL = 'email';
    const FIELD_FIO = 'fio';
    const FIELD_REGISTERED = 'registered_at';

    public static $defaultFields = [
        User::FIELD_LOGIN,
        User::FIELD_EMAIL,
        User::FIELD_FIO,
        User::FIELD_REGISTERED
    ];

    public function __construct(Client $client, array $fields = [])
    {
        parent::__construct($client, $fields);
    }

    protected function getUrl($method, array $params = [])
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
                    'code' => 204
                ];
            case 'delete':
                return [
                    'url' => vsprintf('/1.0/security/user/delete/%d', $params),
                    'method' => 'DELETE',
                    'code' => 204
                ];
            default :
                throw new Error('Route for ' . $method . ' not found');
        }
    }
}
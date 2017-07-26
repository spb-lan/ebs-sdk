<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 11:57
 */

namespace Lan\Ebs\Sdk\Model;

use Lan\Ebs\Sdk\Classes\Model;
use Lan\Ebs\Sdk\Client;

class User extends Model
{
    const FIELD_LOGIN = 'login';
    const FIELD_EMAIL = 'email';
    const FIELD_FIO = 'fio';
    const FIELD_REGISTERED = 'registered_at';

    public function __construct(Client $client, array $fields = [])
    {
        parent::__construct($client, $fields);
    }

    protected function getDefaultFields()
    {
        return [
            User::FIELD_LOGIN,
            User::FIELD_EMAIL,
            User::FIELD_FIO,
            User::FIELD_REGISTERED
        ];
    }

    protected function getUrl($method)
    {
        return [
            'get' => [
                'url' => '/1.0/security/user/get/%d',
                'method' => 'GET',
                'code' => 200
            ],
            'post' => [
                'url' => '/1.0/security/user/post',
                'method' => 'POST',
                'code' => 201
            ],
            'put' => [
                'url' => '/1.0/security/user/put/%d',
                'method' => 'PUT',
                'code' => 204
            ],
            'delete' => [
                'url' => '/1.0/security/user/delete/%d',
                'method' => 'DELETE',
                'code' => 204
            ][$method]
        ];
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 12:25
 */

namespace Lan\Ebs\Sdk\Collection;

use Error;
use Lan\Ebs\Sdk\Classes\Collection;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Model\User;

class UserCollection extends Collection
{
    public function __construct(Client $client, array $fields = [], $class = User::class)
    {
        if ($class != User::class) {
            throw new Error('Wrong class for model collection');
        }

        parent::__construct($client, $fields, $class);
    }

    protected function getRequest()
    {
        return [
            'url' => '/1.0/security/user',
            'method' => 'GET',
            'code' => '200'
        ];
    }
}
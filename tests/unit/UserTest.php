<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Lan\Ebs\Sdk\Model\User;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testApi()
    {
        $client = ClientTest::getClient();

        $user = new User($client);

    }
}
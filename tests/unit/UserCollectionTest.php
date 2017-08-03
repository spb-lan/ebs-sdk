<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Lan\Ebs\Sdk\Collection\UserCollection;
use Lan\Ebs\Sdk\Model\User;

class UserCollectionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function getServices()
    {
        return [
            'di'         => new \Codeception\Lib\Di(),
            'dispatcher' => new \Codeception\Util\Maybe(),
            'modules'    => \Codeception\Util\Stub::makeEmpty('Codeception\Lib\ModuleContainer')
        ];
    }

    protected function setUp()
    {
        $this->getMetadata()->setServices($this->getServices());

        parent::setUp();
    }

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testIndex()
    {
        $client = ClientTest::getClient();

        $limit = 5;

        $collection = new UserCollection($client, [], $limit);

        $count = 0;

        $previousUser = null;
        $previousUserId = 0;

        /** @var User $user */
        foreach ($collection as $user) {
            $this->assertInstanceOf(User::class, $user);

            $this->assertNotNull($user->getId());

            $this->assertNotEquals($previousUser, $user);
            $this->assertNotEquals($previousUserId, $user->getId());

            $previousUser = $user;
            $previousUserId = $user->getId();

            $count++;
        }

        $this->assertEquals($limit, $count);
    }
}
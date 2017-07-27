<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Lan\Ebs\Sdk\Collection\UserCollection;
use Lan\Ebs\Sdk\Model\User;
use Monolog\Logger;

class UserCollectionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Logger
     */
    private $logger;

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
        $this->logger = new Logger(__CLASS__);
    }

    protected function _after()
    {
    }

    public function testIndex()
    {
        $client = ClientTest::getClient();

        $collection = new UserCollection($client);

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

        $this->assertEquals(10, $count);
    }
}
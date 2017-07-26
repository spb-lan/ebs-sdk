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

        /** @var User $user */
        foreach ($collection as $user) {
            $this->assertInstanceOf(User::class, $user);

            $count++;
        }

        $this->assertEquals(10, $count);
    }
}
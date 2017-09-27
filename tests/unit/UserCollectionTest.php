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

    private $client;

    protected function getServices()
    {
        return [
            'di' => new \Codeception\Lib\Di(),
            'dispatcher' => new \Codeception\Util\Maybe(),
            'modules' => \Codeception\Util\Stub::makeEmpty('Codeception\Lib\ModuleContainer')
        ];
    }

    protected function setUp()
    {
        $this->getMetadata()->setServices($this->getServices());

        parent::setUp();
    }

    protected function _before()
    {
        $this->client = ClientTest::getClient();
    }

    protected function _after()
    {
    }

    public function testIndex()
    {
        $limit = 5;

        $collection = new UserCollection($this->client, [], $limit);

        $collectionCount = $collection->count();

        if ($collectionCount > 0) {
            $first = $collection->reset();
            $last = $collection->end();

            $this->assertNotNull($first);
            $this->assertNotNull($last);

            $this->assertNotEquals($first, $last);
        }

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

        $data = $collection->getData();

        $this->assertEquals($collectionCount, count($data));
    }
}
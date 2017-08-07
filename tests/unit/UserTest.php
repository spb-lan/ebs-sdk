<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Error;
use Lan\Ebs\Sdk\Classes\Collection;
use Lan\Ebs\Sdk\Classes\Model;
use Lan\Ebs\Sdk\Collection\UserCollection;
use Lan\Ebs\Sdk\Helper\Test;
use Lan\Ebs\Sdk\Model\User;

class UserTest extends \Codeception\Test\Unit
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

    public function testSetEmpty()
    {
        $user = new User($this->client);

        $this->expectException(Error::class);
        $user->set([]);

    }

//    public function testSetOnlyId()
//    {
//        $user = new User($this->client);
//
//        $this->expectException(Error::class);
//        $user->set(['id' => 2]);
//    }
//
//    public function testSetPartFields()
//    {
//        $user = new User($this->client);
//
//        $this->expectException(Error::class);
//        $user->set([
//            'id' => 2,
//            'login' => __FUNCTION__
//        ]);
//    }
//
//    public function testSetPartFields2()
//    {
//        $user = new User($this->client, [User::FIELD_LOGIN, User::FIELD_EMAIL, User::FIELD_FIO]);
//
//        $this->expectException(Error::class);
//        $user->set([
//            'id' => 2,
//            'login' => __FUNCTION__
//        ]);
//    }

    public function testSetDefinedFields()
    {
        $user = new User($this->client, [User::FIELD_LOGIN]);

        $user->set([
            'id' => 2,
            'login' => __FUNCTION__
        ]);

        $this->assertEquals(2, $user->getId());
    }

    public function testPost()
    {
        $user = new User($this->client);

        $time = time();

        $user->post([
            'login' => __FUNCTION__ . '_' . $time,
            'password' => __FUNCTION__ . '_' . $time,
            'fio' => __FUNCTION__
        ]);

        $data = $user->get();

        $this->assertNotNull($user->getId());
        $this->assertNotNull($user->id);
        $this->assertNotNull($data['id']);

        $this->assertNotNull($user->login);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->fio);
    }

    public function testGet()
    {
        $user = new User($this->client);

        try {
            $user->get();
        } catch (Error $e) {
            Test::assertExceptionMessage($this, $e, Model::MESSAGE_ID_REQUIRED);
        }

        /** @var  Collection $userCollection */
        $userCollection = new UserCollection($this->client, [], 1);

        /** @var User $user */
        $user = $userCollection->reset();

        $data = $user->get();

        $this->assertNotNull($user->getId());
        $this->assertNotNull($user->id);
        $this->assertNotNull($data['id']);

        $this->assertNotNull($user->login);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->fio);
    }

    public function testPut()
    {
        /** @var  Collection $userCollection */
        $userCollection = new UserCollection($this->client, [], 1);

        /** @var User $user */
        $user = $userCollection->reset();

        $oldFio = $user->fio;

        $this->assertNotNull($oldFio);

        $user->put([
            'fio' => __FUNCTION__,
            'password' => __FUNCTION__
        ]);

        $this->assertNotNull($user->fio);

        $this->assertNotEquals($user->fio, $oldFio);
    }

    public function testDelete()
    {
        /** @var  Collection $userCollection */
        $userCollection = new UserCollection($this->client, [], 1);

        /** @var User $user */
        $user = $userCollection->reset();

        $oldId = $user->id;

        $user->delete();

        $this->expectException(Error::class);
        $this->expectExceptionCode(404);

        $user->get($oldId);
    }
}
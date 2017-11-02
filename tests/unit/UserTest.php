<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Codeception\Util\Debug;
use Exception;
use Lan\Ebs\Sdk\Classes\Model;
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

//    public function testSetOnlyId()
//    {
//        $user = new User($this->client);
//
//        $this->expectException(Exception::class);
//        $user->set(['id' => 2]);
//    }
//
//    public function testSetPartFields()
//    {
//        $user = new User($this->client);
//
//        $this->expectException(Exception::class);
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
//        $this->expectException(Exception::class);
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

        file_put_contents(__DIR__ . '/testUserPk', $user->getId());
        file_put_contents(__DIR__ . '/testUserFio', $user->fio);
    }

    public function testGet()
    {
        $user = new User($this->client, [User::FIELD_LOGIN, User::FIELD_EMAIL, User::FIELD_FIO]);

        try {
            $user->get();
        } catch (Exception $e) {
            Test::assertExceptionMessage($this, $e, Model::MESSAGE_ID_REQUIRED);
        }

        $testUserPk = file_get_contents(__DIR__ . '/testUserPk');

        $user->setId($testUserPk);

        $data = $user->get($testUserPk);

        $this->assertEquals($testUserPk, $user->getId());

        $this->assertNotNull($user->getId());
        $this->assertNotNull($user->id);
        $this->assertNotNull($data['id']);

        $this->assertNotNull($user->login);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->fio);
    }

    public function testPut()
    {
        $testUserPk = file_get_contents(__DIR__ . '/testUserPk');
        $testUserFio = file_get_contents(__DIR__ . '/testUserFio');

        $user = new User($this->client);

        $user->setId($testUserPk);

        $this->assertEquals($testUserPk, $user->getId());

        $this->assertEquals('testPost', $testUserFio);

        $user->put([
            'fio' => __FUNCTION__,
            'password' => __FUNCTION__ . '_' . time(),
        ]);

        $this->assertEquals('testPut', $user->fio);
    }

    public function testDelete()
    {
        $testUserPk = file_get_contents(__DIR__ . '/testUserPk');

        $user = new User($this->client);

        $user->setId($testUserPk);

        $user->delete();

        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);

        unlink(__DIR__ . '/testUserPk');
        unlink(__DIR__ . '/testUserFio');

        $user->get($testUserPk);
    }
}
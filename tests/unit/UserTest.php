<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Error;
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

    public function testSetEmpty()
    {
        $user = new User($this->client);

        $this->expectException(Error::class);
        $user->set([]);

    }

    public function testSetOnlyId()
    {
        $user = new User($this->client);

        $this->expectException(Error::class);
        $user->set(['id' => 2]);
    }

    public function testSetPartFields()
    {
        $user = new User($this->client);

        $this->expectException(Error::class);
        $user->set([
            'id' => 2,
            'login' => __FUNCTION__
        ]);
    }

    public function testSetPartFields2()
    {
        $user = new User($this->client, [User::FIELD_LOGIN, User::FIELD_EMAIL, User::FIELD_FIO]);

        $this->expectException(Error::class);
        $user->set([
            'id' => 2,
            'login' => __FUNCTION__
        ]);
    }

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
    }

    public function testGet()
    {
        $user = new User($this->client);

        try {
            $user->get();
        } catch (Error $e) {
            Test::assertExceptionMessage($this, $e, Model::MESSAGE_ID_REQUIRED);
        }

        $data = $user->get(264);

        $this->assertEquals(264, $user->getId());
        $this->assertEquals(264, $data['id']);

        $this->assertEquals('dmitry@kokovtsev.ru', $data['email']);
    }

    public function testPut()
    {
        $user = new User($this->client);
    }

    public function testDelete()
    {
        $user = new User($this->client);
    }
}
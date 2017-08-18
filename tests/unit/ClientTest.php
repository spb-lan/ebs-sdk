<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Error;
use Lan\Ebs\Sdk\Client;

class ClientTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private static $token = 'fc92d67fb9597650d3f99d023a7f51db87d8';

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

    public function testFailCreateClient()
    {
        $this->expectException(Exception::class);
        $this->getClient('');
    }

    public function testSuccessCreateClient()
    {
        $this->assertInstanceOf(Client::class, $this->getClient());
    }

    /**
     * @param null $token
     * @return Client
     */
    public static function getClient($token = null)
    {
        return new Client($token === null ? ClientTest::$token : $token);
    }
}
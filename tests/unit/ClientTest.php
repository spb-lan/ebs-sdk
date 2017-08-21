<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Error;
use Exception;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Security;

class ClientTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private static $token = '7c0c2193d27108a509abd8ea84a8750c82b3a520';

    private $client;

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
        $this->client = ClientTest::getClient();
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

    public function testGetAutologinUrl() {
        $security = new Security($this->client);
    }
}
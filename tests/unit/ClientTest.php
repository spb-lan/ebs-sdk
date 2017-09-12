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
        $this->getClient();
    }

    public function testSuccessCreateClient()
    {
        $this->assertInstanceOf(Client::class, $this->getClient());
    }

    /**
     * @return Client
     */
    public static function getClient()
    {
        return new Client(Security::TEST_TOKEN);
    }

    public function testGetAutologinUrl() {
        $security = new Security($this->client);
        $this->assertNotEmpty($security->getAutologinUrl(rand(0, 9999)));
    }

    public function testGetDemoUrl() {
        $security = new Security($this->client);
        $this->assertNotEmpty($security->getDemoUrl('book', 27));
    }
}
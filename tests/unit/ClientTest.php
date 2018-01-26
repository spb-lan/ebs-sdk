<?php

namespace Lan\Ebs\Sdk\Test\Unit;

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

    /**
     * @throws \Exception
     */
    protected function _before()
    {
        $this->client = ClientTest::getClient();
    }

    protected function _after()
    {
    }

    public function testCreateClient()
    {
        $this->assertInstanceOf(Client::class, $this->client);
    }

    /**
     * @return Client
     * @throws \Exception
     */
    public static function getClient()
    {
        return new Client(Security::TEST_TOKEN);
    }

    /**
     * @throws \Exception
     */
    public function testGetAutologinUrl() {
        $security = new Security($this->client);

        $uid = '12345';
        $fio = 'Иванов Иван Иванович';
        $email = 'ivanov@example.com';
        $redirect = '/books';

        $url = $security->getAutologinUrl($uid, $fio, $email, $redirect);

        $this->assertNotNull($url);
        $this->assertNotEmpty($url);
    }
}
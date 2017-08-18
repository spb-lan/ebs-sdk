<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Exception;
use Lan\Ebs\Sdk\Security;

class SecurityTest extends \Codeception\Test\Unit
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

    public function testGetSecretKey()
    {
        $security = new Security($this->client);

        $secretKey = $security->getSecretKey(date('Ymd'));

        $this->assertNotEmpty($secretKey);

        $secretKey11 = $security->getSecretKey(date('Ymd'));

        $this->assertEquals($secretKey, $secretKey11);

        $secretKey2 = $security->getSecretKey(date('Ymd', strtotime('-1 day')));

        $this->assertNotEmpty($secretKey);

        $this->assertNotEquals($secretKey, $secretKey2);

        $secretKey3 = $security->getSecretKey(date('Ymd', strtotime('+1 day')));

        $this->assertNotEmpty($secretKey);

        $this->assertNotEquals($secretKey2, $secretKey3);

        $this->expectException(Exception::class);

        $security->getSecretKey(date('Ymd', strtotime('+2 days')));
    }
}
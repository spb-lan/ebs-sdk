<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Error;
use Exception;
use Lan\Ebs\Sdk\Client;
use Lan\Ebs\Sdk\Security;
use PHPUnit_Framework_TestResult;

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

    public function testCreateClient()
    {
        $this->assertInstanceOf(Client::class, $this->client);
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

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        // TODO: Implement count() method.
    }

    /**
     * Runs a test and collects its result in a TestResult instance.
     *
     * @param PHPUnit_Framework_TestResult $result
     *
     * @return PHPUnit_Framework_TestResult
     */
    public function run(PHPUnit_Framework_TestResult $result = null)
    {
        // TODO: Implement run() method.
    }
}
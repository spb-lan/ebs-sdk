<?php

namespace Lan\Ebs\Sdk\Test\Unit;

class ReportFormTest extends \Codeception\Test\Unit
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

    public function testBibFond()
    {
    }

    public function testEBooks()
    {
    }

    public function testSpecPo()
    {
    }

}
<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Exception;
use Lan\Ebs\Sdk\Classes\Collection;
use Lan\Ebs\Sdk\Classes\Model;
use Lan\Ebs\Sdk\Collection\BookCollection;
use Lan\Ebs\Sdk\Helper\Test;
use Lan\Ebs\Sdk\Model\Book;
use PHPUnit_Framework_TestResult;

class BookTest extends \Codeception\Test\Unit
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

    /**
     * @throws Exception
     */
    public function testSetEmpty()
    {
        $book = new Book($this->client);

        $this->expectException(Exception::class);

        $book->set([]);
    }

    public function testGet()
    {
        $book = new Book($this->client);

        try {
            $book->get();
        } catch (Exception $e) {
            Test::assertExceptionMessage($this, $e, Model::MESSAGE_ID_REQUIRED);
        }

        /** @var  Collection $bookCollection */
        $bookCollection = new BookCollection($this->client, [], 1);

        /** @var Book $book */
        $book = $bookCollection->reset();

        $data = $book->get();

        $this->assertNotNull($book->getId());
        $this->assertNotNull($book->id);
        $this->assertNotNull($data['id']);

        $this->assertNotNull($book->name);

        $book1 = new Book($this->client, ['name']);

        $bookData = $book1->get($book->getId());

        $this->assertEquals($book->name, $bookData['name']);

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
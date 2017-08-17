<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Lan\Ebs\Sdk\Collection\BookCollection;
use Lan\Ebs\Sdk\Model\Book;

class BookCollectionTest extends \Codeception\Test\Unit
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

    public function testIndex()
    {
        $limit = 5;

        $collection = new BookCollection($this->client, [], $limit);

        $collectionCount = $collection->count();

        if ($collectionCount > 0) {
            $this->assertNotNull($collection->reset());

            $this->assertNotNull($collection->end());
        }

        if ($collectionCount >= $limit) {
            $count = 0;

            $previousBook = null;
            $previousBookId = 0;

            /** @var Book $book */
            foreach ($collection as $book) {
                $this->assertInstanceOf(Book::class, $book);

                $this->assertNotNull($book->getId());

                $this->assertNotEquals($previousBook, $book);
                $this->assertNotEquals($previousBookId, $book->getId());

                $previousBook = $book;
                $previousBookId = $book->getId();

                $count++;
            }

            $this->assertEquals($limit, $count);
        }

        $data = $collection->getData();

        $this->assertEquals($collectionCount, count($data));
    }
}
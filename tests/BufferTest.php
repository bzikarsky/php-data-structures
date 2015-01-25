<?php

namespace Zikarsky\DataStructure\Test;

use Zikarsky\DataStructure\Buffer;

/**
 * @coversDefaultClass \Zikarsky\DataStructure\Buffer
 */
class BufferTest extends \PHPUnit_Framework_TestCase
{
    private $emptyBuffer;
    private $fullBuffer;
    private $buffer;

    public function setUp()
    {
        $this->emptyBuffer = new Buffer(4);

        $this->fullBuffer  = new Buffer(4);
        $this->fullBuffer->add('A');
        $this->fullBuffer->add('B');
        $this->fullBuffer->add('C');
        $this->fullBuffer->add('D');

        $this->buffer = new Buffer(4);
        $this->buffer->add('A');
    }

    /**
     * @covers ::isEmpty
     */
    public function testIsEmpty()
    {
        $this->assertTrue($this->emptyBuffer->isEmpty());
        $this->assertFalse($this->buffer->isEmpty());
        $this->assertFalse($this->fullBuffer->isEmpty());
    }

    /**
     * @covers ::isFull
     */
    public function testIsFull()
    {
        $this->assertFalse($this->emptyBuffer->isFull());
        $this->assertFalse($this->buffer->isFull());
        $this->assertTrue($this->fullBuffer->isFull());
    }

    /**
     * @covers ::count
     */
    public function testCount()
    {
        $this->assertEquals(0, $this->emptyBuffer->count());
        $this->assertEquals(1, $this->buffer->count());
        $this->assertEquals(4, $this->fullBuffer->count());
    }

    /**
     * @covers ::get
     * @covers ::getLeastRecentPosition
     */
    public function testGet()
    {
        $this->assertEquals('A', $this->fullBuffer->get());
        $this->assertEquals('A', $this->buffer->get());

        // Test that get is corrrect after one element is removed
        $this->fullBuffer->remove();
        $this->assertEquals('B', $this->fullBuffer->get());

        // Test that get is correct after an element is added again
        $this->fullBuffer->add('E');
        $this->assertEquals('B', $this->fullBuffer->get());

        // Test that get is correct after clear, and an additional add
        $this->fullBuffer->clear();
        $this->fullBuffer->add('F');
        $this->assertEquals('F', $this->fullBuffer->get());
    }

    /**
     * @covers ::get
     * @expectedException UnderflowException
     */
    public function testGetOnEmptyThrowsException()
    {
        $this->emptyBuffer->get();
    }

    /**
     * @covers ::remove
     * @covers ::getLeastRecentPosition
     */
    public function testRemove()
    {
        $this->assertEquals('A', $this->fullBuffer->remove());
        $this->assertEquals('A', $this->buffer->remove());

        $this->assertTrue($this->buffer->isEmpty());
        $this->assertFalse($this->fullBuffer->isEmpty());

        $this->assertEquals('B', $this->fullBuffer->get());
        $this->assertEquals('B', $this->fullBuffer->remove());

        $this->fullBuffer->add('E');
        $this->assertEquals('C', $this->fullBuffer->get());
    }

    /**
     * @covers ::remove
     * @expectedException UnderflowException
     */
    public function testRemoveOnEmptyThrowsException()
    {
        $this->emptyBuffer->remove();
    }

    /**
     * @covers ::add
     */
    public function testAdd()
    {
        $this->assertTrue($this->emptyBuffer->add('A'));
        $this->assertFalse($this->emptyBuffer->isEmpty());
        $this->assertEquals('A', $this->emptyBuffer->get());

        $this->assertTrue($this->buffer->add('B'));
        $this->assertEquals(2, $this->buffer->count());

        $this->fullBuffer->remove();
        $this->assertTrue($this->fullBuffer->add('E'));
        $this->assertTrue($this->fullBuffer->isFull());
    }

    /**
     * @covers ::add
     * @expectedException OverflowException
     */
    public function testAddOnEmptyThrowsException()
    {
        $this->fullBuffer->add('E');
    }

    /**
     * @covers ::clear
     */
    public function testClear()
    {
        $this->fullBuffer->clear();
        $this->assertTrue($this->fullBuffer->isEmpty());
        $this->assertEquals(0, $this->fullBuffer->count());

        // Test that we can readd elements after clearing
        $this->fullBuffer->add('A');
        $this->assertFalse($this->fullBuffer->isEmpty());

        // Test that clearing an empty buffer works
        $this->emptyBuffer->clear();
        $this->assertTrue($this->emptyBuffer->isEmpty());
    }

    /**
     * @covers ::__construct
     * @expectedException InvalidArgumentException
     */
    public function testThatConstructorFailsOnNegativeSize()
    {
        new Buffer(-1);
    }

    /**
     * @covers ::__construct
     * @expectedException InvalidArgumentException
     */
    public function testThatConstructorFailsOnZeroSize()
    {
        new Buffer(0);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructorCapacity()
    {
        $buffer = new Buffer(2);
        $this->assertFalse($this->buffer->isFull());
        
        $buffer->add('A');
        $this->assertFalse($this->buffer->isFull());
        
        $buffer->add('B');
        $this->assertTrue($buffer->isFull());
    }

    /**
     * @covers ::getIterator
     */
    public function testGetIterator()
    {
        $class = '\\Zikarsky\\DataStructure\\Iterator\\BufferIterator';
        
        $this->assertInstanceOf($class, $this->emptyBuffer->getIterator());
        $this->assertInstanceOf($class, $this->buffer->getIterator());
        $this->assertInstanceOf($class, $this->fullBuffer->getIterator());
    }
}

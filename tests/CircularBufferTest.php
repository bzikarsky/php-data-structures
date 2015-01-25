<?php

namespace Zikarsky\DataStructure\Test;

use Zikarsky\DataStructure\CircularBuffer;

/**
 * CircularBuffer inherits most of its behaviour from Buffer, so only
 * the overwritten behaviour is tested in this testcase.
 */
class CircularBufferTest extends \PHPUnit_Framework_TestCase
{

    public function testAdd()
    {
        $buffer = new CircularBuffer(4);
        
        // initial state is empty
        $this->assertTrue($buffer->isEmpty());
        $this->assertFalse($buffer->isFull());
        $this->assertEquals(0, $buffer->count());

        // lets make it a full buffer
        $buffer->add('A');
        $buffer->add('B');
        $buffer->add('C');
        $buffer->add('D');

        // is it really full?
        $this->assertFalse($buffer->isEmpty());
        $this->assertTrue($buffer->isFull());
        $this->assertEquals(4, $buffer->count());
        $this->assertEquals('A', $buffer->get());

        // Let's add another
        $buffer->add('E');

        // Is it circular?
        $this->assertFalse($buffer->isEmpty());
        $this->assertTrue($buffer->isFull());
        $this->assertEquals(4, $buffer->count());
        $this->assertEquals('B', $buffer->get());

        // Let's empty fotr a final check
        $this->assertEquals('B', $buffer->remove());
        $this->assertEquals('C', $buffer->remove());
        $this->assertEquals('D', $buffer->remove());
        $this->assertEquals('E', $buffer->remove());
    }
}

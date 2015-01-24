<?php

namespace Zikarsky\DataStructure;

use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use OverflowException;
use SplFixedArray;
use UnderflowException;
use Zikarsky\DataStructure\Iterator\BufferIterator;

/**
 * A simple FIFO Buffer implementation ontop of SplFixedArray
 *
 * The maximum number of elements the buffer holds is defined
 * in the constructor. Any overflow or underflow throws
 * the appropiate exception.
 *
 * @author Benjamin Zikarsky <benjamin@zikarsky.de>
 */
class Buffer implements Countable, IteratorAggregate
{
    
    /**
     * Internal representation of the buffer as a fixed-size array
     *
     * @var SplFixedArray
     */
    private $buffer;
    
    /**
     * Maximum number of elements the buffer can hold
     *
     * @var int
     */
    private $maxSize;

    /**
     * Current number of elements in the buffer
     *
     * @var int
     */
    private $size = 0;

    /**
     * Next free position in the buffer
     *
     * @var int
     */
    private $position = 0;

    /**
     * Constructs a buffer with given $maxSize
     *
     * @param  integer                  $maxSize Maximum capacity for this buffer
     * @throws InvalidArgumentException The exception is thrown in case $maxSize < 1
     */
    public function __construct($maxSize)
    {
        $maxSize = intval($maxSize);
        if ($maxSize < 1) {
            throw new InvalidArgumentException("maxSize has to be greather than 0");
        }

        $this->buffer = new SplFixedArray($maxSize);
        $this->maxSize = $maxSize;
    }

    /**
     * Adds an element to the buffer
     *
     * @param  mixed             $element to push into the buffer
     * @return boolean           Always true
     * @throws OverflowException The exception is thrown in case the buffer is full
     */
    public function add($element)
    {
        if ($this->isFull()) {
            throw new OverflowException("Buffer is full");
        }

        $this->buffer[$this->position] = $element;
        $this->position = ($this->position + 1) % $this->maxSize;
        $this->size++;

        return true;
    }

    /**
     * Gets the least recently added element
     *
     * @return mixed              The least recently added element
     * @throws UnderflowException The exception is thrown when the buffer is empty
     */
    public function get()
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Buffer is empty");
        }

        return $this->buffer[$this->getLeastRecentPosition()];
    }

    /**
     * Removes and returns the least recently added element
     *
     * @return mixed              The least recently added element
     * @throws UnderflowException The exception is thrown when the buffer is empty
     */
    public function remove()
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Buffer is empty");
        }

        $leastRecentPosition = $this->getLeastRecentPosition();
        $leastRecentElement  = $this->buffer[$leastRecentPosition];

        unset($this->buffer[$leastRecentPosition]);
        $this->size--;

        return $leastRecentElement;
    }

    /**
     * Clears the buffer of all elements
     */
    public function clear()
    {
        $this->size = 0;
        $this->position = 0;
        $this->buffer = new SplFixedArray($this->maxSize);
    }

    /**
     * Returns the current number of elements in the buffer
     *
     * @return integer Current buffer-size
     */
    public function count()
    {
        return $this->size;
    }

    /**
     * Returns whether the buffer is full
     *
     * @return boolean True when the buffer is full, false otherwise
     */
    public function isFull()
    {
        return $this->size == $this->maxSize;
    }

    /**
     * Returns whether the buffer is empty
     *
     * @return boolean True when the buffer is empty, false otherwise
     */
    public function isEmpty()
    {
        return $this->size == 0;
    }

    /**
     * Returns an iterator over the elements in the buffer.
     *
     * The order is the order of insertion (FIFO)
     *
     * @return BufferIterator Iterator over the buffer elements
     */
    public function getIterator()
    {
        return new BufferIterator(
            $this->buffer,
            $this->getLeastRecentPosition(),
            $this->size
        );
    }

    /**
     * Returns the position of the least recently added element in the buffer
     *
     * @return integer Position of the least recently added element
     */
    private function getLeastRecentPosition()
    {
        $position = $this->position - $this->size;

        // PHP's modulo is broken: Negative numbers aren't handled properly
        // => Adding maxSize solves this
        $position += $this->maxSize;

        return $position % $this->maxSize;
    }
}

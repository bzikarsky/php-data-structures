<?php

namespace Zikarsky\DataStructure;

/**
 * A circular FIFO buffer ontop of SplFixedArray
 *
 * The implementation takes alomost everything from Buffer and only
 * allows old values to be overwritten in `add($element)`.
 *
 * @author Benjamin Zikarsky <benjamin@zikarsky.de>
 */
class CircularBuffer extends Buffer
{
    /**
     * Adds an element to the buffer.
     *
     * If the buffer is full the oldest element is overwritten
     *
     * @param  mixed $element
     * @return bool  always true
     */
    public function add($element)
    {
        $this->buffer[$this->position] = $element;

        $this->position = ($this->position + 1) % $this->maxSize;
        $this->size = min($this->size + 1, $this->maxSize);

        return true;
    }
}

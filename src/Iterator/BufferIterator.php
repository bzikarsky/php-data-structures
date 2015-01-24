<?php

namespace Zikarsky\DataStructure\Iterator;

use Iterator;
use SplFixedArray;

class BufferIterator implements Iterator
{
    private $position = 0;
    private $offset;
    private $maxPosition;
    private $buffer;
    private $bufferSize;

    public function __construct(SplFixedArray $buffer, $startPosition, $size)
    {
        $this->offset = $startPosition;
        $this->buffer = $buffer;
        $this->maxPosition = $size;
        $this->bufferSize = $buffer->getSize();
    }

    public function next()
    {
        $this->position++;
    }

    public function current()
    {
        $realPosition = ($this->offset + $this->position) % $this->bufferSize;
        return $this->buffer[$realPosition];
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return $this->position < $this->maxPosition;
    }

    public function rewind()
    {
        $this->position = 0;
    }

}

<?php

namespace Zikarsky\DataStructure\Test\Iterator;

use SplFixedArray;
use Zikarsky\DataStructure\Iterator\BufferIterator;

class BufferIteratorTest extends \PHPUnit_Framework_TestCase
{
    
    const BUFFER_SIZE = 4;

    public function testCases()
    {
        $tests = [];

        $tests[] = [
            "Empty buffer",
            new BufferIterator(new SplFixedArray(self::BUFFER_SIZE), 0, 0),
            []
        ];

        $tests[] = [
            "Empty buffer - not at start",
            new BufferIterator(new SplFixedArray(self::BUFFER_SIZE), 2, 0),
            []
        ];

        $tests[] = [
            "Full buffer",
            new BufferIterator(SplFixedArray::fromArray(range(0, self::BUFFER_SIZE - 1)), 0, self::BUFFER_SIZE),
            range(0, self::BUFFER_SIZE - 1)
        ];

        $buffer = new SplFixedArray(self::BUFFER_SIZE);
        $buffer[2] = 0;
        $buffer[3] = 1;
        $buffer[0] = 2;
        $buffer[1] = 3;
        $tests[] = [
            "Full buffer - wrapped",
             new BufferIterator($buffer, 2, 4),
             [0, 1, 2, 3]
        ];


        $buffer = new SplFixedArray(self::BUFFER_SIZE);
        $buffer[0] = 0;
        $buffer[1] = 1;
        $tests[] = [
            "Partially filled buffer - values at start",
            new BufferIterator($buffer, 0, 2),
            [0, 1]
        ];

        $buffer = new SplFixedArray(self::BUFFER_SIZE);
        $buffer[2] = 0;
        $buffer[3] = 1;
        $tests[] = [
            "Partially filled buffer - values at end",
             new BufferIterator($buffer, 2, 2),
             [0, 1]
        ];

        $buffer = new SplFixedArray(self::BUFFER_SIZE);
        $buffer[3] = 0;
        $buffer[0] = 1;
        $tests[] = [
            "Partially filled buffer - wrapped",
             new BufferIterator($buffer, 3, 2),
             [0, 1]
        ];

        $buffer = new SplFixedArray(self::BUFFER_SIZE);
        $buffer[1] = 0;
        $buffer[2] = 1;
        $tests[] = [
            "Partially filled buffer - centered",
             new BufferIterator($buffer, 1, 2),
             [0, 1]
        ];
        
        return $tests;
    }


    /**
     * @dataProvider testCases
     */
    public function testIterator($case, $it, $result)
    {
        // run it once with iterator_to_array
        $emittedData = iterator_to_array($it);
        $this->assertEquals($result, $emittedData, "first oteration with iterator_to_array: $case");

        // try again with a loop
        $emittedData = [];
        foreach ($it as $k => $v) {
            $emittedData[$k] = $v;
        }

        $this->assertEquals($emittedData, $result, "Second iteration with loop: $case");
    }
}

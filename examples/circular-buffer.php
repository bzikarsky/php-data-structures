<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Zikarsky\DataStructure\CircularBuffer;


$buffer = new CircularBuffer(2);

echo "Adding A\n";
$buffer->add("A");
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";

echo "Adding B\n";
$buffer->add("B");
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";

echo "Adding C\n";
$buffer->add("C");
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";

echo "Getting an element: " . $buffer->get() . "\n";
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";

echo "Removing an element: " . $buffer->remove() . "\n";
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";

echo "Adding D\n";
$buffer->add("D");
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";

echo "Clearing buffer\n";
$buffer->clear();
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";


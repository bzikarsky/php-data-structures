<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Zikarsky\DataStructure\Buffer;


$buffer = new Buffer(4);

echo "Adding A\n";
$buffer->add("A");
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";

echo "Adding B\n";
$buffer->add("B");
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";

echo "Getting an element: " . $buffer->get() . "\n";
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";

echo "Removing an element: " . $buffer->remove() . "\n";
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";

echo "Adding C\n";
$buffer->add("C");
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";

echo "Clearing buffer\n";
$buffer->clear();
echo "Buffer: ", implode("", iterator_to_array($buffer)), "\n";


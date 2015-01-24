<?php

$autoloadFile = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($autoloadFile)) {
    die("Autoloader is missing. " .
        "Did you forget to install the dependencies? " .
        "('composer install --dev')\n");
}

require_once $autoloadFile;

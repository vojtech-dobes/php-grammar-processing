<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

Tester\Dumper::$dumpDir = __DIR__ . '/output';
Tester\Environment::setup();

<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

Tester\Environment::setup();

$configurator = new Nette\Configurator;
$configurator->setDebugMode(false);
$configurator->setTempDirectory(__DIR__ . '/../../tmp/temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../../src')
    ->addDirectory(__DIR__ . '/utils/')
	->register();

$configurator->addConfig(__DIR__ . '/../../src/config/config.neon');
$configurator->addConfig(__DIR__ . '/../../src/config/config.local.neon');
return $configurator->createContainer();

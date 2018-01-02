<?php

require __DIR__ . '/../../vendor/autoload.php';

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(__DIR__ . '/../../tmp/temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../../src')
    ->addDirectory(__DIR__ )
	->register();

$configurator->addConfig(__DIR__ . '/../../src/config/config.neon');
$configurator->addConfig(__DIR__ . '/../../src/config/config.local.neon');
return $configurator->createContainer();

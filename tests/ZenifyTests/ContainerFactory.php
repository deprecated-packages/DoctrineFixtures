<?php

namespace ZenifyTests;

use Nette;
use Nette\DI\Container;


class ContainerFactory
{

	/**
	 * @return Container
	 */
	public function create()
	{
		$configurator = new Nette\Configurator;
		$configurator->setTempDirectory($this->createAndReturnTempDir());
		$configurator->addConfig(__DIR__ . '/config/default.neon');
		return $configurator->createContainer();
	}


	/**
	 * @return string
	 */
	private function createAndReturnTempDir()
	{
		$tempDir = __DIR__ . '/../tmp/';
		Nette\Utils\FileSystem::delete($tempDir);
		@mkdir($tempDir); // @ - directory may exists
		return realpath($tempDir);
	}

}

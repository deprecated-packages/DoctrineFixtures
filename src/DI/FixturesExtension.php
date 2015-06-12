<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\DI;

use Faker\Provider\Base;
use Nelmio\Alice\Fixtures\Loader;
use Nelmio\Alice\Fixtures\Parser\Methods\MethodInterface;
use Nette\DI\CompilerExtension;
use Nette\DI\ServiceDefinition;
use Nette\Utils\Validators;
use Symfony\Component\Console\Application;
use Zenify\DoctrineFixtures\Command\LoadFixturesCommand;


class FixturesExtension extends CompilerExtension
{

	/**
	 * @var array[]
	 */
	private $defaults = [
		'alice' => [
			'locale' => 'cs_CZ',
			'seed' => 1
		]
	];


	public function loadConfiguration()
	{
		$containerBuilder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/services.neon');
		$this->compiler->parseServices($containerBuilder, $services);
	}


	public function beforeCompile()
	{
		$containerBuilder = $this->getContainerBuilder();
		$containerBuilder->prepareClassList();

		$this->loadFakerProvidersToAliceLoader();
		$this->loadParsersToAliceLoader();
	}


	private function loadFakerProvidersToAliceLoader()
	{
		$containerBuilder = $this->getContainerBuilder();
		$config = $this->getValidatedConfig();

		$this->getDefinitionByType(Loader::class)->setArguments([
			$config['alice']['locale'],
			$containerBuilder->findByType(Base::class),
			$config['alice']['seed']
		]);
	}


	/**
	 * @return array
	 */
	private function getValidatedConfig()
	{
		$config = $this->getConfig($this->defaults);
		$this->validateConfigTypes($config);
		return $config;
	}


	private function validateConfigTypes(array $config)
	{
		Validators::assertField($config, 'alice', 'array');
		Validators::assertField($config['alice'], 'seed', 'int');
		Validators::assertField($config['alice'], 'locale', 'string');
	}


	private function loadParsersToAliceLoader()
	{
		$containerBuilder = $this->getContainerBuilder();

		$aliceLoaderDefinition = $this->getDefinitionByType(Loader::class);
		foreach ($containerBuilder->findByType(MethodInterface::class) as $parserDefinition) {
			$aliceLoaderDefinition->addSetup('addParser', ['@' . $parserDefinition->getClass()]);
		}
	}


	/**
	 * @param string $type
	 * @return ServiceDefinition
	 */
	private function getDefinitionByType($type)
	{
		$containerBuilder = $this->getContainerBuilder();
		return $containerBuilder->getDefinition($containerBuilder->getByType($type));
	}

}

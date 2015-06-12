<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\DI;

use Faker\Provider\Base;
use Nelmio\Alice\LoaderInterface;
use Nelmio\Alice\ORM\Doctrine;
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

		$this->loadCommandToConsole();
		$this->loadFakerProvidersToAliceLoaders();
	}


	private function loadCommandToConsole()
	{
		$applicationDefinition = $this->getDefinitionByType(Application::class);
		$loadFixturesCommandDefinition = $this->getDefinitionByType(LoadFixturesCommand::class);
		$applicationDefinition->addSetup('add', [$loadFixturesCommandDefinition->getClass()]);
	}


	private function loadFakerProvidersToAliceLoaders()
	{
		$containerBuilder = $this->getContainerBuilder();

		$aliceDoctrineDefinition = $this->getDefinitionByType(Doctrine::class);
		$fakerProviderDefinitions = $containerBuilder->findByType(Base::class);
		$config = $this->getValidatedConfig();

		foreach ($containerBuilder->findByType(LoaderInterface::class) as $aliceLoaderDefinition) {
			$aliceLoaderDefinition->setArguments([$config['alice']['locale'], [], $config['alice']['seed']])
				->addSetup('setORM', ['@' . $aliceDoctrineDefinition->getClass()])
				->addSetup('setProviders', [$fakerProviderDefinitions]);
		}
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

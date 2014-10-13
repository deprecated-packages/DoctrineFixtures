<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\DI;

use Kdyby\Console\DI\ConsoleExtension;
use Nette\DI\CompilerExtension;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;


class FixturesExtension extends CompilerExtension
{

	/**
	 * @var array
	 */
	protected $defaults = array(
		'faker' => array(
			'providers' => array(
				'Zenify\DoctrineFixtures\Faker\Provider\Strings'
			),
		),
		'alice' => array(
			'seed' => 1,
			'locale' => 'cs_CZ',
			'loaders' => array(
				'neon' => 'Zenify\DoctrineFixtures\Alice\Loader\Neon'
			),
		),
		'enabled' => FALSE
	);

	/**
	 * @var array
	 */
	private $fakerProviders = array();


	public function __construct()
	{
		$this->defaults['enabled'] = (PHP_SAPI === 'cli');
	}


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		if ($config['enabled'] === FALSE) {
			return;
		}
		$this->validateConfigTypes($config);

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('dataFixtures.purger'))
			->setClass('Doctrine\Common\DataFixtures\Purger\ORMPurger');

		$builder->addDefinition($this->prefix('dataFixtures.executor'))
			->setClass('Doctrine\Common\DataFixtures\Executor\ORMExecutor');

		$builder->addDefinition($this->prefix('dataFixtures.loader'))
			->setClass('Zenify\DoctrineFixtures\DataFixtures\Loader');

		$builder->addDefinition($this->prefix('command.loadFixtures'))
			->setClass('Zenify\DoctrineFixtures\Commands\LoadFixturesCommand')
			->addTag(ConsoleExtension::COMMAND_TAG);

		$this->loadFaker($config['faker']);
		$this->loadAlice($config['alice']);
	}


	/**
	 * @param array $config
	 */
	protected function loadFaker($config)
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('faker.generator'))
			->setClass('Faker\Generator');

		foreach ($config['providers'] as $i => $class) {
			$provider = $builder->addDefinition($this->prefix('faker.provider.' . $i))
				->setClass($class);

			$this->fakerProviders[] = '@' . $provider->getClass();
		}
	}


	/**
	 * @param array $config
	 */
	protected function loadAlice($config)
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('alice.loader'))
			->setClass('Zenify\DoctrineFixtures\Alice\Loader');

		$builder->addDefinition($this->prefix('alice.orm.doctrine'))
			->setClass('Nelmio\Alice\ORM\Doctrine');

		foreach ($config['loaders'] as $i => $loader) {
			$builder->addDefinition($this->prefix('alice.loader.' . $i))
				->setClass($loader)
				->setArguments(array($config['locale'], $this->fakerProviders, $config['seed']))
				->addSetup('setORM', array($this->prefix('@alice.orm.doctrine')));
		}
	}


	/**
	 * @throws AssertionException
	 */
	protected function validateConfigTypes(array $config)
	{
		Validators::assertField($config, 'faker', 'array');
		Validators::assertField($config['faker'], 'providers', 'list');
		Validators::assertField($config, 'alice', 'array');
		Validators::assertField($config['alice'], 'seed', 'int');
		Validators::assertField($config['alice'], 'locale', 'string');
		Validators::assertField($config['alice'], 'loaders', 'array');
	}

}

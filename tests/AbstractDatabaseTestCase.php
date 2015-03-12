<?php

namespace Zenify\DoctrineFixtures\Tests;

use Kdyby\Doctrine\EntityManager;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;


abstract class AbstractDatabaseTestCase extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * @var EntityManager
	 */
	protected $entityManager;


	public function __construct()
	{
		$this->container = $container = (new ContainerFactory)->create();
	}


	protected function setUp()
	{
		$this->entityManager = $this->container->getByType(EntityManager::class);

		/** @var DatabaseLoader $databaseLoader */
		$databaseLoader = $this->container->getByType(DatabaseLoader::class);
		$databaseLoader->prepareProductAndUserTable();
	}

}

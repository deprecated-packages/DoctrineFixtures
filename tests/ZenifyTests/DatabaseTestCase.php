<?php

namespace ZenifyTests;

use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette;
use PHPUnit_Framework_TestCase;
use Zenify;
use ZenifyTests\DatabaseLoader;
use ZenifyTests\DoctrineFixtures\Entities\Product;
use ZenifyTests\DoctrineFixtures\Entities\User;


abstract class DatabaseTestCase extends PHPUnit_Framework_TestCase
{

	/**
	 * @var EntityManager
	 */
	protected $em;

	/**
	 * @var EntityDao
	 */
	protected $productDao;

	/**
	 * @var EntityDao
	 */
	protected $userDao;

	/**
	 * @var Nette\DI\Container
	 */
	protected $container;

	/**
	 * @var DatabaseLoader
	 */
	private $databaseLoader;


	public function __construct()
	{
		$this->container = $container = (new ContainerFactory)->create();
		$this->em = $container->getByType(EntityManager::class);
		$this->productDao = $this->em->getDao(Product::class);
		$this->userDao = $this->em->getDao(User::class);
		$this->databaseLoader = $container->getByType(DatabaseLoader::class);
	}


	protected function setUp()
	{
		$this->databaseLoader->prepareProductAndUserTable();
	}

}

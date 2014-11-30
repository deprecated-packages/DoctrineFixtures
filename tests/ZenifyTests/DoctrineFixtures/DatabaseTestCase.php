<?php

namespace ZenifyTests\DoctrineFixtures;

use Kdyby\Doctrine\Connection;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Tester\TestCase;
use Zenify;


abstract class DatabaseTestCase extends TestCase
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


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
		$this->em = $container->getByType('Kdyby\Doctrine\EntityManager');
		$this->productDao = $this->em->getDao('ZenifyTests\DoctrineFixtures\Entities\Product');
		$this->userDao = $this->em->getDao('ZenifyTests\DoctrineFixtures\Entities\User');
	}


	protected function setUp()
	{
		$this->prepareDbData();
	}


	private function prepareDbData()
	{
		/** @var Connection $connection */
		$connection = $this->container->getByType('Doctrine\DBAL\Connection');
		$connection->query('CREATE TABLE product (id INTEGER NOT NULL, name string, PRIMARY KEY(id))');
		$connection->query('CREATE TABLE user (id INTEGER NOT NULL, email string, PRIMARY KEY(id))');
		$this->em->flush();
	}

}

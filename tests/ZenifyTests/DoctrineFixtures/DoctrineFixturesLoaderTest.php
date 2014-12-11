<?php

namespace ZenifyTests\DoctrineFixtures;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Kdyby\Doctrine\EntityDao;
use Nette;
use Zenify;
use Zenify\DoctrineFixtures\DataFixtures\Loader;
use ZenifyTests\DatabaseTestCase;
use ZenifyTests\DoctrineFixtures\Entities\Product;


class DoctrineFixturesLoaderTest extends DatabaseTestCase
{

	/**
	 * @var Loader
	 */
	private $fixturesLoader;


	protected function setUp()
	{
		parent::setUp();
		$this->fixturesLoader = $this->container->getByType(Loader::class);
	}


	public function testLoadFixture()
	{
		$this->fixturesLoader->loadFromDirectory(__DIR__ . '/Fixtures');
		$fixtures = $this->fixturesLoader->getFixtures();
		$this->assertCount(1, $fixtures);

		/** @var ORMExecutor $executor */
		$executor = $this->container->getByType('Doctrine\Common\DataFixtures\Executor\ORMExecutor');
		$executor->execute($fixtures);

		/** @var EntityDao $productDao */
		$productDao = $this->em->getDao('ZenifyTests\DoctrineFixtures\Entities\Product');
		$product = $productDao->find(1);
		$this->assertInstanceOf(Product::class, $product);

		// purge by default
		$executor->execute($fixtures);
		$product = $productDao->find(2);
		$this->assertNull($product);

		// append
		$executor->execute($fixtures, TRUE);
		$product = $productDao->find(2);
		$this->assertInstanceOf(Product::class, $product);
	}

}

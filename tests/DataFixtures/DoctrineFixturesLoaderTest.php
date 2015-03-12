<?php

namespace Zenify\DoctrineFixtures\Tests\DoctrineFixtures;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Kdyby\Doctrine\EntityRepository;
use Zenify\DoctrineFixtures\DataFixtures\Loader;
use Zenify\DoctrineFixtures\Tests\AbstractDatabaseTestCase;
use Zenify\DoctrineFixtures\Tests\Entities\Product;


class DoctrineFixturesLoaderTest extends AbstractDatabaseTestCase
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
		$executor = $this->container->getByType(ORMExecutor::class);
		$executor->execute($fixtures);

		/** @var EntityRepository $productDao */
		$productDao = $this->entityManager->getRepository(Product::class);
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

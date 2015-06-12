<?php

namespace Zenify\DoctrineFixtures\Tests\DoctrineFixtures;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\EntityRepository;
use Zenify\DoctrineFixtures\DataFixtures\DataFixturesLoader;
use Zenify\DoctrineFixtures\Tests\AbstractDatabaseTestCase;
use Zenify\DoctrineFixtures\Tests\Entity\Product;


class DoctrineFixturesLoaderTest extends AbstractDatabaseTestCase
{

	/**
	 * @var DataFixturesLoader
	 */
	private $fixturesLoader;

	/**
	 * @var EntityRepository
	 */
	private $productRepository;

	/**
	 * @var ORMExecutor
	 */
	private $ormExecutor;


	protected function setUp()
	{
		parent::setUp();
		$this->fixturesLoader = $this->container->getByType(DataFixturesLoader::class);
		$this->productRepository = $this->entityManager->getRepository(Product::class);
		$this->ormExecutor = $this->container->getByType(ORMExecutor::class);
	}


	public function testLoadFixture()
	{
		$this->fixturesLoader->loadFromDirectory(__DIR__ . '/Fixtures');
		$fixtures = $this->fixturesLoader->getFixtures();
		$this->assertCount(1, $fixtures);

		$this->ormExecutor->execute($fixtures);

		$product = $this->productRepository->find(1);
		$this->assertInstanceOf(Product::class, $product);

		// purge by default
		$this->ormExecutor->execute($fixtures);
		$product = $this->productRepository->find(2);
		$this->assertNull($product);

		// append
		$this->ormExecutor->execute($fixtures, TRUE);
		$product = $this->productRepository->find(2);
		$this->assertInstanceOf(Product::class, $product);
	}

}

<?php

namespace Zenify\DoctrineFixtures\Tests\Alice;

use Doctrine\ORM\EntityRepository;
use Zenify\DoctrineFixtures\Contract\Alice\AliceLoaderInterface;
use Zenify\DoctrineFixtures\Tests\AbstractDatabaseTestCase;
use Zenify\DoctrineFixtures\Tests\Entity\Product;
use Zenify\DoctrineFixtures\Tests\Faker\Provider\ProductName;


class AliceLoaderYamlTest extends AbstractDatabaseTestCase
{

	/**
	 * @var AliceLoaderInterface
	 */
	private $fixturesLoader;

	/**
	 * @var EntityRepository
	 */
	private $productRepository;


	protected function setUp()
	{
		parent::setUp();
		$this->fixturesLoader = $this->container->getByType(AliceLoaderInterface::class);
		$this->productRepository = $this->entityManager->getRepository(Product::class);
	}


	public function testLoadFixture()
	{
		$file = __DIR__ . '/fixtures/products.yaml';
		$products = $this->fixturesLoader->load($file);

		/** @var Product[] $products */
		$products = $this->productRepository->findAll();

		$this->assertCount(20, $products);

		foreach ($products as $product) {
			$this->assertInstanceOf(Product::class, $product);
			$this->assertInternalType('string', $product->getName());
			$this->assertContains($product->getName(), ProductName::$randomNames);
		}
	}

}

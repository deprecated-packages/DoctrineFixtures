<?php

namespace Zenify\DoctrineFixtures\Tests\Alice;

use Kdyby\Doctrine\EntityRepository;
use Zenify\DoctrineFixtures\Alice\AliceLoader;
use Zenify\DoctrineFixtures\Tests\AbstractDatabaseTestCase;
use Zenify\DoctrineFixtures\Tests\Entities\Product;
use Zenify\DoctrineFixtures\Tests\Entities\User;
use Zenify\DoctrineFixtures\Tests\Faker\Providers\ProductName;


class AliceLoaderTest extends AbstractDatabaseTestCase
{

	/**
	 * @var AliceLoader
	 */
	private $fixturesLoader;

	/**
	 * @var EntityRepository
	 */
	private $productRepository;

	/**
	 * @var EntityRepository
	 */
	private $userRepository;


	protected function setUp()
	{
		parent::setUp();
		$this->fixturesLoader = $this->container->getByType(AliceLoader::class);
		$this->productRepository = $this->entityManager->getRepository(Product::class);
		$this->userRepository = $this->entityManager->getRepository(User::class);
	}


	public function testLoadFixture()
	{
		$file = __DIR__ . '/fixtures/products.neon';
		$this->fixturesLoader->load($file);

		$products = $this->productRepository->findAll();
		$this->assertCount(100, $products);

		/** @var Product $product */
		foreach ($products as $product) {
			$this->assertInstanceOf(Product::class, $product);
			$this->assertInternalType('string', $product->getName());
			$this->assertContains($product->getName(), ProductName::$randomNames);
		}
	}


	public function testLoadFolder()
	{
		$dir = __DIR__ . '/fixtures';
		$this->fixturesLoader->loadFromDirectory($dir);

		$products = $this->productRepository->findAll();
		$this->assertCount(100, $products);

		$users = $this->userRepository->findAll();
		$this->assertCount(10, $users);

		/** @var User $user */
		foreach ($users as $user) {
			$this->assertInstanceOf(User::class, $user);
			$this->assertContains('@', $user->getEmail());
		}
	}

	public function testLoadFixtureWithIncludes_fixturesAreLoadedInTopDownOrder()
	{
		$file = __DIR__ . '/fixturesWithIncludes/includes.neon';
		$this->fixturesLoader->load($file);

		$users = $this->userRepository->findAll();

		$this->assertCount(2, $users);

		$this->assertInstanceOf(User::class, $users[0]);
		$this->assertEquals("user1@email.com", $users[0]->getEmail());
		$this->assertInstanceOf(User::class, $users[1]);
		$this->assertEquals("user2@email.com", $users[1]->getEmail());
	}
}

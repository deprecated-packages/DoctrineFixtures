<?php

namespace ZenifyTests\DoctrineFixtures;

use Nette;
use Zenify;
use Zenify\DoctrineFixtures\Alice\AliceLoader;
use ZenifyTests\DatabaseTestCase;
use ZenifyTests\DoctrineFixtures\Entities\Product;
use ZenifyTests\DoctrineFixtures\Entities\User;
use ZenifyTests\DoctrineFixtures\Faker\Providers\ProductName;


class AliceLoaderTest extends DatabaseTestCase
{

	/**
	 * @var AliceLoader
	 */
	private $fixturesLoader;


	protected function setUp()
	{
		parent::setUp();
		$this->fixturesLoader = $this->container->getByType(AliceLoader::class);
	}


	public function testLoadFixture()
	{
		$file = __DIR__ . '/Alice/products.neon';
		$this->fixturesLoader->load($file);

		$products = $this->productDao->findAll();
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
		$dir = __DIR__ . '/Alice/';
		$this->fixturesLoader->loadFromDirectory($dir);

		$products = $this->productDao->findAll();
		$this->assertCount(100, $products);

		$users = $this->userDao->findAll();
		$this->assertCount(10, $users);

		/** @var User $user */
		foreach ($users as $user) {
			$this->assertInstanceOf(User::class, $user);
			$this->assertContains('@', $user->getEmail());
		}

	}

}

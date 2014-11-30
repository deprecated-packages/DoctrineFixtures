<?php

/**
 * @testCase
 */

namespace ZenifyTests\DoctrineFixtures;

use Nette;
use Tester\Assert;
use Zenify;
use Zenify\DoctrineFixtures\Alice\Loader;
use ZenifyTests\DoctrineFixtures\Entities\Product;
use ZenifyTests\DoctrineFixtures\Entities\User;
use ZenifyTests\DoctrineFixtures\Faker\Providers\ProductName;


$container = require_once __DIR__ . '/../bootstrap.php';


class AliceLoaderTest extends DatabaseTestCase
{

	/**
	 * @var Loader
	 */
	private $fixturesLoader;


	protected function setUp()
	{
		parent::setUp();
		$this->fixturesLoader = $this->container->getByType('Zenify\DoctrineFixtures\Alice\Loader');
	}


	public function testLoadFixture()
	{
		$file = __DIR__ . '/Alice/products.neon';
		$this->fixturesLoader->load($file);

		$products = $this->productDao->findAll();
		Assert::count(100, $products);

		/** @var Product $product */
		foreach ($products as $product) {
			Assert::type('ZenifyTests\DoctrineFixtures\Entities\Product', $product);
			Assert::isEqual('string', $product->getName());
			Assert::contains($product->getName(), ProductName::$randomNames);
		}
	}


	public function testLoadFolder()
	{
		$dir = __DIR__ . '/Alice/';
		$this->fixturesLoader->loadFromDirectory($dir);

		$products = $this->productDao->findAll();
		Assert::count(100, $products);

		$users = $this->userDao->findAll();
		Assert::count(10, $users);

		/** @var User $user */
		foreach ($users as $user) {
			Assert::type('ZenifyTests\DoctrineFixtures\Entities\User', $user);
			Assert::contains('@', $user->getEmail());
		}

	}

}


(new AliceLoaderTest($container))->run();

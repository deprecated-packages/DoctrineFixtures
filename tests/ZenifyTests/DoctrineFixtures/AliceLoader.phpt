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

}


(new AliceLoaderTest($container))->run();

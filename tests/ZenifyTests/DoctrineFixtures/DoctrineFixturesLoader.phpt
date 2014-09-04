<?php

/**
 * @testCase
 */

namespace ZenifyTests\DoctrineFixtures;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Kdyby\Doctrine\EntityDao;
use Nette;
use Tester\Assert;
use Zenify;
use Zenify\DoctrineFixtures\DataFixtures\Loader;


$container = require_once __DIR__ . '/../bootstrap.php';


class DoctrineFixturesLoaderTest extends DatabaseTestCase
{
	/** @var Loader */
	private $fixturesLoader;


	protected function setUp()
	{
		parent::setUp();
		$this->fixturesLoader = $this->container->getByType('Zenify\DoctrineFixtures\DataFixtures\Loader');
	}


	public function testLoadFixture()
	{
		$this->fixturesLoader->loadFromDirectory(__DIR__ . '/fixtures');
		$fixtures = $this->fixturesLoader->getFixtures();
		Assert::count(1, $fixtures);

		/** @var ORMExecutor $executor */
		$executor = $this->container->getByType('Doctrine\Common\DataFixtures\Executor\ORMExecutor');
		$executor->execute($fixtures);

		/** @var EntityDao $productDao */
		$productDao = $this->em->getDao('ZenifyTests\DoctrineFixtures\Entities\Product');
		$product = $productDao->find(1);
		Assert::type('ZenifyTests\DoctrineFixtures\Entities\Product', $product);

		// purge by default
		$executor->execute($fixtures);
		$product = $productDao->find(2);
		Assert::null($product, $product);

		// append
		$executor->execute($fixtures, TRUE);
		$product = $productDao->find(2);
		Assert::type('ZenifyTests\DoctrineFixtures\Entities\Product', $product);
	}

}


\run(new DoctrineFixturesLoaderTest($container));

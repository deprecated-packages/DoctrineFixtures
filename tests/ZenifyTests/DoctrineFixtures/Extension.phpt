<?php

/**
 * @testCase
 */

namespace ZenifyTests\DoctrineFixtures;

use Nette;
use Tester\Assert;
use Tester\TestCase;


$container = require_once __DIR__ . '/../bootstrap.php';


class ExtensionTest extends TestCase
{

	/**
	 * @var Nette\DI\Container
	 */
	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function testExtension()
	{
		$purger = $this->container->getByType('Doctrine\Common\DataFixtures\Purger\ORMPurger');
		Assert::type('Doctrine\Common\DataFixtures\Purger\ORMPurger', $purger);

		$executor = $this->container->getByType('Doctrine\Common\DataFixtures\Executor\ORMExecutor');
		Assert::type('Doctrine\Common\DataFixtures\Executor\ORMExecutor', $executor);

		$loader = $this->container->getByType('Zenify\DoctrineFixtures\DataFixtures\Loader');
		Assert::type('Zenify\DoctrineFixtures\DataFixtures\Loader', $loader);
	}


	public function testFaker()
	{
		$loadCommand = $this->container->getByType('Zenify\DoctrineFixtures\Commands\LoadFixturesCommand');
		Assert::type('Zenify\DoctrineFixtures\Commands\LoadFixturesCommand', $loadCommand);

		$faker = $this->container->getByType('Faker\Generator');
		Assert::type('Faker\Generator', $faker);

		$stringsProvider = $this->container->getByType('Zenify\DoctrineFixtures\Faker\Provider\Strings');
		Assert::type('Zenify\DoctrineFixtures\Faker\Provider\Strings', $stringsProvider);

	}


	public function testAlice()
	{
		$loader = $this->container->getByType('Zenify\DoctrineFixtures\Alice\Loader');
		Assert::type('Zenify\DoctrineFixtures\Alice\Loader', $loader);

		$objectManager = $this->container->getByType('Nelmio\Alice\ORM\Doctrine');
		Assert::type('Nelmio\Alice\ORM\Doctrine', $objectManager);

		$neonLoader = $this->container->getByType('Zenify\DoctrineFixtures\Alice\Loader\Neon');
		Assert::type('Zenify\DoctrineFixtures\Alice\Loader\Neon', $neonLoader);
	}

}


(new ExtensionTest($container))->run();

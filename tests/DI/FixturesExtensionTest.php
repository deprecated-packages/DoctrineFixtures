<?php

namespace Zenify\DoctrineFixtures\Tests\DI;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Faker\Generator;
use Nelmio\Alice\ORM\Doctrine;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Zenify\DoctrineFixtures\Tests\ContainerFactory;


class FixturesExtensionTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
	}


	public function testExtension()
	{
		$this->assertInstanceOf(
			ORMPurger::class,
			$this->container->getByType(ORMPurger::class)
		);

		$this->assertInstanceOf(
			Generator::class,
			$this->container->getByType(Generator::class)
		);

		$this->assertInstanceOf(
			Doctrine::class,
			$this->container->getByType(Doctrine::class)
		);
	}

}

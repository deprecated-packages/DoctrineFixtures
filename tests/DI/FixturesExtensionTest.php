<?php

namespace Zenify\DoctrineFixtures\Tests\DI;

use Faker\Generator;
use Faker\Provider\cs_CZ\Company;
use Nelmio\Alice\Fixtures\Loader;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerBuilder;
use PHPUnit_Framework_TestCase;
use Zenify\DoctrineFixtures\Alice\AliceLoader;
use Zenify\DoctrineFixtures\Contract\Alice\AliceLoaderInterface;
use Zenify\DoctrineFixtures\DI\FixturesExtension;
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


	public function testLoadConfiguration()
	{
		$extension = $this->getExtension();
		$extension->loadConfiguration();

		$containerBuilder = $extension->getContainerBuilder();
		$containerBuilder->prepareClassList();

		$aliceLoaderDefinition = $containerBuilder->getDefinition(
			$containerBuilder->getByType(AliceLoaderInterface::class)
		);

		$this->assertSame(AliceLoader::class, $aliceLoaderDefinition->getClass());
	}


	public function testLoadFakerProvidersToAliceLoader()
	{
		$extension = $this->getExtension();
		$extension->loadConfiguration();

		$containerBuilder = $extension->getContainerBuilder();
		$containerBuilder->addDefinition('company')
			->setClass(Company::class);

		$containerBuilder->prepareClassList();

		$extension->beforeCompile();

		$loaderDefinition = $containerBuilder->getDefinition($containerBuilder->getByType(Loader::class));

		$this->assertSame(Loader::class, $loaderDefinition->getClass());
		$arguments = $loaderDefinition->getFactory()->arguments;
		$this->assertCount(3, $arguments);
		$this->assertArrayHasKey('company', $arguments[1]);
	}


	public function testLoadParsersToAliceLoader()
	{
		$extension = $this->getExtension();
		$extension->loadConfiguration();
		$extension->beforeCompile();

		$containerBuilder = $extension->getContainerBuilder();
		$aliceLoaderDefinition = $containerBuilder->getDefinition($containerBuilder->getByType(Loader::class));

		$this->assertSame('addParser', $aliceLoaderDefinition->getSetup()[0]->getEntity());
	}


	/**
	 * @return FixturesExtension
	 */
	private function getExtension()
	{
		$extension = new FixturesExtension;
		$extension->setCompiler(new Compiler(new ContainerBuilder), 'fixtures');
		return $extension;
	}

}

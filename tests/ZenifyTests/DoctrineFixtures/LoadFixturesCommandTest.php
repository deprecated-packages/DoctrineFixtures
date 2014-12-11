<?php

namespace ZenifyTests\DoctrineFixtures;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Zenify\DoctrineFixtures\Commands\LoadFixturesCommand;
use ZenifyTests\DatabaseTestCase;


/**
 * @author Milan Blažek <blazekm1lan@seznam.cz>
 */
class LoadFixturesCommandTest extends DatabaseTestCase
{

	/**
	 * @var LoadFixturesCommand
	 */
	protected $command;


	protected function setUp()
	{
		parent::setUp();

		$this->command = $this->container->getByType(LoadFixturesCommand::class);
		$this->command->setHelperSet(new HelperSet([
			'question' => new QuestionHelper
		]));
	}


	public function testLoadAliceFixtures()
	{
		$input = new ArrayInput([
			'fixtures' => __DIR__ . '/Alice/' ,
			'--append' => FALSE ,
		]);
		$input->setInteractive(FALSE);

		$this->command->run($input, new BufferedOutput);

		$products = $this->productDao->findAll();
		$this->assertCount(100, $products);

		$users = $this->userDao->findAll();
		$this->assertCount(10, $users);
	}

}

<?php

namespace Zenify\DoctrineFixtures\Tests\Command;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Zenify\DoctrineFixtures\Command\LoadFixturesCommand;
use Zenify\DoctrineFixtures\Tests\AbstractDatabaseTestCase;
use Zenify\DoctrineFixtures\Tests\Entity\Product;
use Zenify\DoctrineFixtures\Tests\Entity\User;


class LoadFixturesCommandTest extends AbstractDatabaseTestCase
{

	/**
	 * @var LoadFixturesCommand
	 */
	private $command;

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

		$this->command = $this->container->getByType(LoadFixturesCommand::class);
		$this->command->setHelperSet(new HelperSet([
			'question' => new QuestionHelper
		]));

		$this->productRepository = $this->entityManager->getRepository(Product::class);
		$this->userRepository = $this->entityManager->getRepository(User::class);
	}


	public function testLoadAliceFixtures()
	{
		$input = new ArrayInput([
			'fixtures' => __DIR__ . '/../Alice/fixtures',
			'--append' => FALSE
		]);
		$input->setInteractive(FALSE);

		$this->command->run($input, new BufferedOutput);

		$this->assertCount(120, $this->productRepository->findAll());
		$this->assertCount(10, $this->userRepository->findAll());
	}


	public function testLoadAliceFixturesFromOneFileNoOtherFilesInDirectoryAreProcessed()
	{
		$input = new ArrayInput([
			'fixtures' => __DIR__ . '/../Alice/fixturesWithIncludes/includes.neon',
			'--append' => FALSE
		]);
		$input->setInteractive(FALSE);

		$this->command->run($input, new BufferedOutput);

		$this->assertCount(2, $this->userRepository->findAll());
	}

}

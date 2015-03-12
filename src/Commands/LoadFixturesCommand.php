<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Commands;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Zenify\DoctrineFixtures\Alice\AliceLoader;
use Zenify\DoctrineFixtures\DataFixtures\Loader;


class LoadFixturesCommand extends Command
{

	const APPEND = 'append';
	const FIXTURES = 'fixtures';
	const PURGE = 'purge-with-truncate';

	/**
	 * @inject
	 * @var ORMPurger
	 */
	public $purger;

	/**
	 * @inject
	 * @var ORMExecutor
	 */
	public $executor;

	/**
	 * @inject
	 * @var Loader
	 */
	public $loader;

	/**
	 * @inject
	 * @var AliceLoader
	 */
	public $aliceLoader;


	protected function configure()
	{
		$this->setName('doctrine:fixtures:load')
			->setAliases(['doctrine:fixture:load'])
			->setDescription('Load data fixtures to your database')
			->setDefinition([
				new InputArgument(self::FIXTURES, InputArgument::REQUIRED | InputArgument::IS_ARRAY,
					'The directory orf file to load data fixtures from.'),
				new InputOption(self::APPEND, NULL, InputOption::VALUE_OPTIONAL,
					'Append the data fixtures instead of deleting all data from the database first.', TRUE),
				new InputOption(self::PURGE, NULL, InputOption::VALUE_NONE,
					'Purge data by using a database-level TRUNCATE statement')
			])
			->setHelp(<<<EOT
The <info>doctrine:fixtures:load</info> command loads data fixtures from specified directory:

  <info>php www/index.php doctrine:fixtures:load /path/to/fixtures</info>

You can also optionally specify the multiple paths:

  <info>php www/index.php doctrine:fixtures:load /path/to/fixtures1,/path/to/fixtures2</info>

If you want to flush the fixtures instead of appending the database first you can use the <info>--append</info> option:

  <info>php www/index.php doctrine:fixtures:load /path/to/fixtures --append=0</info>

By default Doctrine Data Fixtures uses DELETE statements to drop the existing rows from
the database. If you want to use a TRUNCATE statement instead you can use the <info>--purge-with-truncate</info> flag:

  <info>php www/index.php doctrine:fixtures:load /path/to/fixtures --purge-with-truncate</info>
EOT
		);
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		if ($input->isInteractive() && ! $input->getOption(self::APPEND)) {
			if ( ! $this->askForPurge($input, $output)) {
				return;
			}
		}

		$dirOrFile = $input->getArgument(self::FIXTURES);
		$paths = is_array($dirOrFile) ? $dirOrFile : [$dirOrFile];

		$this->loadFixturesWithAlice($paths);
		$this->loadFixtures($paths, $input->getOption(self::PURGE), $input->getOption(self::APPEND));
	}


	/**
	 * @return bool
	 */
	private function askForPurge(InputInterface $input, OutputInterface $output)
	{
		/** @var QuestionHelper $questionHelper */
		$questionHelper = $this->getHelper('question');
		$question = new Question('Careful, database will be purged. Do you want to continue Y/N ?', FALSE);
		return $questionHelper->ask($input, $output, $question);
	}


	private function loadFixturesWithAlice(array $paths)
	{
		foreach ($paths as $path) {
			if (is_dir($path)) {
				$this->aliceLoader->loadFromDirectory($path);
			}
		}
	}


	/**
	 * @param array $paths
	 * @param bool $purge
	 * @param bool $append
	 */
	private function loadFixtures(array $paths, $purge, $append)
	{
		foreach ($paths as $path) {
			if (is_dir($path)) {
				$this->loader->loadFromDirectory($path);
			}
		}
		$fixtures = $this->loader->getFixtures();

		if (empty($fixtures)) {
			return;
		}

		$purgeMode = $purge ? ORMPurger::PURGE_MODE_TRUNCATE : ORMPurger::PURGE_MODE_DELETE;
		$this->purger->setPurgeMode($purgeMode);
		$this->executor->execute($fixtures, $append);
	}

}

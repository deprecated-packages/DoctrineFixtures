<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Commands;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Zenify\DoctrineFixtures\DataFixtures\Loader;


class LoadFixturesCommand extends Command
{

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
	 * @var \Zenify\DoctrineFixtures\Alice\Loader
	 * @inject
	 */
	public $aliceLoader;


	protected function configure()
	{
		$this->setName('doctrine:fixtures:load')
			->setAliases(['doctrine:fixture:load'])
			->setDescription('Load data fixtures to your database')
			->setDefinition([
				new InputArgument('fixtures', InputArgument::REQUIRED | InputArgument::IS_ARRAY,
					'The directory(/ies) to load data fixtures from.'),
				new InputOption('append', NULL, InputOption::VALUE_OPTIONAL,
						'Append the data fixtures instead of deleting all data from the database first.', TRUE),
				new InputOption('purge-with-truncate', NULL, InputOption::VALUE_NONE,
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
		/** @var QuestionHelper $questionHelper */
		$questionHelper = $this->getHelper('question');
		$question = new Question('Careful, database will be purged. Do you want to continue Y/N ?', FALSE);

		if ($input->isInteractive() && ! $input->getOption('append')) {
			if ( ! $questionHelper->ask($input, $output, $question)) {
				return;
			}
		}

		$dirOrFile = $input->getArgument('fixtures');
		$paths = is_array($dirOrFile) ? $dirOrFile : [$dirOrFile];

		foreach ($paths as $path) {
			if (is_dir($path)) {
				$this->loader->loadFromDirectory($path);
			}
		}
		$fixtures = $this->loader->getFixtures();

		$aliceFixtures = [];
		foreach ($paths as $path) {
			if (is_dir($path)) {
				$loaded = $this->aliceLoader->loadFromDirectory($path);
				$aliceFixtures = array_merge($aliceFixtures, $loaded);
			}
		}

		if (empty($aliceFixtures) && empty($fixtures)) {
			throw new InvalidArgumentException(
				sprintf('Could not find any fixtures to load in: %s', "\n\n- " . implode("\n- ", $paths))
			);
		}

		if (empty($fixtures)) {
			return;
		}

		$purgeMode = $input->getOption('purge-with-truncate') ? ORMPurger::PURGE_MODE_TRUNCATE : ORMPurger::PURGE_MODE_DELETE;
		$this->purger->setPurgeMode($purgeMode);
		$this->executor->setLogger(function ($message) use ($output) {
			$output->writeln(sprintf('  <comment>></comment> <info>%s</info>', $message));
		});
		$this->executor->execute($fixtures, $input->getOption('append'));
	}

}

<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Commands;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Zenify\DoctrineDataFixtures\Loader;


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

	/** @var string */
	protected $name = 'doctrine:fixtures:load';

	/** @var string */
	protected $description = 'Load data fixtures to your database';


	protected function configure()
	{
		$this->setHelp(<<<EOT
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


	/**
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('fixtures', InputArgument::REQUIRED | InputArgument::IS_ARRAY,
				'The directory(/ies) to load data fixtures from.'),
		);
	}


	/**
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('append', NULL, InputOption::VALUE_OPTIONAL,
				'Append the data fixtures instead of deleting all data from the database first.', TRUE),
			array('purge-with-truncate', NULL, InputOption::VALUE_NONE,
				'Purge data by using a database-level TRUNCATE statement')
		);
	}


	protected function fire()
	{
		if ($this->input->isInteractive() && ! $this->option('append')) {
			if ( ! $this->ask('Careful, database will be purged. Do you want to continue Y/N ?', FALSE)) {
				return;
			}
		}

		$dirOrFile = $this->argument('fixtures');
		$paths = is_array($dirOrFile) ? $dirOrFile : array($dirOrFile);

		foreach ($paths as $path) {
			if (is_dir($path)) {
				$this->loader->loadFromDirectory($path);
			}
		}
		$fixtures = $this->loader->getFixtures();
		if ( ! $fixtures) {
			throw new InvalidArgumentException(
				sprintf('Could not find any fixtures to load in: %s', "\n\n- ".implode("\n- ", $paths))
			);
		}

		$this->purger->setPurgeMode($this->option('purge-with-truncate') ? ORMPurger::PURGE_MODE_TRUNCATE : ORMPurger::PURGE_MODE_DELETE);
		$this->executor->setLogger(function($message) {
			$this->line(sprintf('  <comment>></comment> <info>%s</info>', $message));
		});
		$this->executor->execute($fixtures, $this->option('append'));
	}

}

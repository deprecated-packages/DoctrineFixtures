<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Contract\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;


/**
 * Derived from @see Doctrine\Common\DataFixtures\Loader
 */
interface DataFixturesLoaderInterface
{

	/**
	 * Find fixtures classes in a given directory and load them.
	 *
	 * @param string $dir Directory to find fixture classes in.
	 * @return array $fixtures Array of loaded fixture object instances.
	 */
	function loadFromDirectory($dir);


	/**
	 * Find fixtures classes in a given file and load them.
	 *
	 * @param string $fileName File to find fixture classes in.
	 * @return array $fixtures Array of loaded fixture object instances.
	 */
	function loadFromFile($fileName);


	/**
	 * Has fixture?
	 *
	 * @param FixtureInterface $fixture
	 * @return bool
	 */
	function hasFixture($fixture);


	/**
	 * Add a fixture object instance to the loader.
	 *
	 * @param FixtureInterface $fixture
	 */
	function addFixture(FixtureInterface $fixture);


	/**
	 * Returns the array of data fixtures to execute.
	 *
	 * @return array $fixtures
	 */
	function getFixtures();


	/**
	 * Check if a given fixture is transient and should not be considered a data fixtures
	 * class.
	 *
	 * @param string $className
	 * @return bool
	 */
	function isTransient($className);

}

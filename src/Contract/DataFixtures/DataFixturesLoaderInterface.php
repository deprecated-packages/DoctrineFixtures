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
	public function loadFromDirectory($dir);


	/**
	 * Find fixtures classes in a given file and load them.
	 *
	 * @param string $fileName File to find fixture classes in.
	 * @return array $fixtures Array of loaded fixture object instances.
	 */
	public function loadFromFile($fileName);


	/**
	 * Has fixture?
	 *
	 * @param FixtureInterface $fixture
	 * @return bool
	 */
	public function hasFixture($fixture);


	/**
	 * Add a fixture object instance to the loader.
	 *
	 * @param FixtureInterface $fixture
	 */
	public function addFixture(FixtureInterface $fixture);


	/**
	 * Returns the array of data fixtures to execute.
	 *
	 * @return array $fixtures
	 */
	public function getFixtures();


	/**
	 * Check if a given fixture is transient and should not be considered a data fixtures
	 * class.
	 *
	 * @param string $className
	 * @return bool
	 */
	public function isTransient($className);

}

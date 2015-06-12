<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Contract\Alice;


interface AliceLoaderInterface
{

	/**
	 * Loads fixtures from one or more files
	 * @param string|array $files
	 * @return object[]
	 */
	function load($files);


	/**
	 * Load all neon fixtures files from folder
	 *
	 * @param string $path
	 * @return object[]
	 * @throws \Exception
	 */
	function loadFromDirectory($path);

}

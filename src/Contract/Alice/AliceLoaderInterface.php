<?php

declare(strict_types=1);

/*
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Contract\Alice;


interface AliceLoaderInterface
{

	/**
	 * Loads fixtures from one or more files/folders.
	 *
	 * @param string|array $sources
	 * @return object[]
	 */
	public function load($sources) : array;

}

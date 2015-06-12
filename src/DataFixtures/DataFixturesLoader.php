<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\DataFixtures;

use Doctrine\Common\DataFixtures\Loader;
use Zenify\DoctrineFixtures\Contract\DataFixtures\DataFixturesLoaderInterface;
use Zenify\DoctrineFixtures\Exception\MissingSourceException;


class DataFixturesLoader implements DataFixturesLoaderInterface
{

	/**
	 * @var Loader
	 */
	private $doctrineLoader;


	public function __construct(Loader $doctrineLoader)
	{
		$this->doctrineLoader = $doctrineLoader;
	}


	/**
	 * {@inheritdoc}
	 */
	public function load($sources)
	{
		if ( ! is_array($sources)) {
			$sources = [$sources];
		}

		$entities = [];
		foreach ($sources as $source) {
			$newEntities = $this->loadEntitiesFromSource($source);
			$entities = array_merge($entities, $newEntities);
		}

		return $this->doctrineLoader->getFixtures();
	}


	/**
	 * @param string $source
	 * @return object[]
	 * @throws MissingSourceException
	 */
	private function loadEntitiesFromSource($source)
	{
		if (is_dir($source)) {
			return $this->doctrineLoader->loadFromDirectory($source);

		} elseif (is_file($source)) {
			return $this->doctrineLoader->loadFromFile($source);

		} else {
			throw new MissingSourceException(
				sprintf('Source "%s" was not found.', $source)
			);
		}
	}

}

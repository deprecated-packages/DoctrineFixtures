<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Alice;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\Finder;
use Zenify\DoctrineFixtures\Contract\Alice\AliceLoaderInterface;
use Zenify\DoctrineFixtures\Exception\MissingSourceException;


class AliceLoader implements AliceLoaderInterface
{

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @var Loader\NeonLoader
	 */
	private $neonLoader;


	public function __construct(EntityManagerInterface $entityManager, Loader\NeonLoader $neonLoader)
	{
		$this->entityManager = $entityManager;
		$this->neonLoader = $neonLoader;
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
			if (is_file($source)) {
				$newEntities = $this->loadFromFile($source);

			} elseif (is_dir($source)) {
				$newEntities = $this->loadFromDirectory($source);

			} else {
				throw new MissingSourceException(
					sprintf('Source "%s" was not found.', $source)
				);
			}

			$entities = array_merge($entities, $newEntities);
		}

		$this->entityManager->flush();

		return $entities;
	}


	/**
	 * @param string $path
	 * @return object[]
	 */
	private function loadFromFile($path)
	{
		$entities = $this->neonLoader->load($path);
		foreach ($entities as $entity) {
			$this->entityManager->persist($entity);
		}
		return $entities;
	}


	/**
	 * {@inheritdoc}
	 */
	private function loadFromDirectory($path)
	{
		$files = [];
		foreach (Finder::find('*.neon')->from($path) as $file) {
			$files[] = $file;
		}
		return $this->load($files);
	}

}

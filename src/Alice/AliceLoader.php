<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Alice;

use Kdyby\Doctrine\EntityManager;
use Nette\Utils\Finder;


class AliceLoader
{

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @var Loader\Neon
	 */
	private $neonLoader;


	public function __construct(EntityManager $entityManager, Loader\Neon $neonLoader)
	{
		$this->entityManager = $entityManager;
		$this->neonLoader = $neonLoader;
	}


	/**
	 * Loads fixtures from one or more files
	 * @param string|array $files
	 * @return object[]
	 */
	public function load($files)
	{
		if ( ! is_array($files)) {
			$files = [$files];
		}

		$objects = [];
		foreach ($files as $file) {
			if ( ! file_exists($file)) {
				throw new \Exception("File $file not found");
			}
			$set = $this->neonLoader->load($file);
			foreach ($set as $entity) {
				$this->entityManager->persist($entity);
			}
			$objects = array_merge($objects, $set);
		}
		$this->entityManager->flush();

		return $objects;
	}


	/**
	 * Load all neon fixtures files from folder
	 *
	 * @param string $path
	 * @return object[]
	 * @throws \Exception
	 */
	public function loadFromDirectory($path)
	{
		if ( ! is_dir($path)) {
			throw new \Exception("Folder $path not found.");
		}

		$files = [];
		foreach (Finder::find('*.neon')->from($path) as $file) {
			$files[] = $file;
		}

		return $this->load($files);
	}

}

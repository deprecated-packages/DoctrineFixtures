<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Alice;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\Finder;
use Zenify\DoctrineFixtures\Contract\Alice\AliceLoaderInterface;
use Zenify\DoctrineFixtures\Exception\MissingDirException;
use Zenify\DoctrineFixtures\Exception\MissingFileException;


class AliceLoader implements AliceLoaderInterface
{

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @var Loader\Neon
	 */
	private $neonLoader;


	public function __construct(EntityManagerInterface $entityManager, Loader\Neon $neonLoader)
	{
		$this->entityManager = $entityManager;
		$this->neonLoader = $neonLoader;
	}


	/**
	 * {@inheritdoc}
	 */
	public function load($files)
	{
		if ( ! is_array($files)) {
			$files = [$files];
		}

		$objects = [];
		foreach ($files as $file) {
			$this->ensureFileExists($file);

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
	 * {@inheritdoc}
	 */
	public function loadFromDirectory($path)
	{
		$this->ensureDirExists($path);

		$files = [];
		foreach (Finder::find('*.neon')->from($path) as $file) {
			$files[] = $file;
		}

		return $this->load($files);
	}


	/**
	 * @param string $path
	 * @throws MissingDirException
	 */
	private function ensureDirExists($path)
	{
		if ( ! is_dir($path)) {
			throw new MissingDirException(
				sprintf('Directory "%s" was not found.', $path)
			);
		}
	}


	/**
	 * @param string $file
	 * @throws MissingFileException
	 */
	private function ensureFileExists($file)
	{
		if ( ! file_exists($file)) {
			throw new MissingFileException(
				sprintf('File "%s" was not found', $file)
			);
		}
	}

}

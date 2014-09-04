<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Alice;

use Kdyby\Doctrine\EntityManager;
use Nelmio;
use Nelmio\Alice\ORM;
use Nette;


class Loader extends Nette\Object
{
	/** @var EntityManager */
	private $em;

	/** @var Loader\Neon */
	private $neonLoader;


	public function __construct(EntityManager $em, Loader\Neon $neonLoader)
	{
		$this->em = $em;
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
			$files = array($files);
		}

		$objects = array();
		foreach ($files as $file) {
			if ( ! file_exists($file)) {
				throw new \Exception("File $file not found");
			}
			$set = $this->neonLoader->load($file);
			foreach ($set as $entity) {
				$this->em->persist($entity);
			}
			$objects = array_merge($objects, $set);
		}
		$this->em->flush();

		return $objects;
	}

}

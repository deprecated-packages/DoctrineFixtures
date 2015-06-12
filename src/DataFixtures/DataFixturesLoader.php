<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Zenify\DoctrineFixtures\Contract\DataFixtures\DataFixturesLoaderInterface;


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
	public function addFixture(FixtureInterface $fixture)
	{
		$this->doctrineLoader->addFixture($fixture);
	}


	/**
	 * {@inheritdoc}
	 */
	public function loadFromDirectory($dir)
	{
		return $this->doctrineLoader->loadFromDirectory($dir);
	}


	/**
	 * {@inheritdoc}
	 */
	public function loadFromFile($fileName)
	{
		return $this->doctrineLoader->loadFromFile($fileName);
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasFixture($fixture)
	{
		return $this->doctrineLoader->hasFixture($fixture);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getFixtures()
	{
		return $this->doctrineLoader->getFixtures();
	}


	/**
	 * {@inheritdoc}
	 */
	public function isTransient($className)
	{
		return $this->doctrineLoader->isTransient($className);
	}

}

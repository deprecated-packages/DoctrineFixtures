<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\DataFixtures;

use Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Nette\DI\Container;


class Loader extends Doctrine\Common\DataFixtures\Loader
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	public function addFixture(FixtureInterface $fixture)
	{
		$this->container->callInjects($fixture);
		parent::addFixture($fixture);
	}

}

<?php

namespace Zenify\DoctrineFixtures\Tests\DataFixtures\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Zenify\DoctrineFixtures\Tests\Entities\Product;


class ProductFixtures implements FixtureInterface
{

	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$product = new Product('Bag carrier');
		$manager->persist($product);
		$manager->flush();
	}

}

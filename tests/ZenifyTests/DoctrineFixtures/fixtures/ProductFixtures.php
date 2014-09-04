<?php

namespace ZenifyTests\DoctrineFixtures\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ZenifyTests\DoctrineFixtures\Entities\Product;


class ProductFixtures implements FixtureInterface
{

	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$product = new Product;
		$product->setName('Bag carrier');

		$manager->persist($product);
		$manager->flush();
	}

}

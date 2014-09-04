<?php

namespace ZenifyTests\DoctrineFixtures\Faker\Providers;

use Faker\Provider\Base;


class ProductName extends Base
{
	/** @var array */
	public static $randomNames = array(
		'Hair of love',
		'Eye of xray',
		'Flying shoe'
	);


	/**
	 * @return string
	 */
	public function shortName()
	{
		return $this->randomElement(self::$randomNames);
	}

}

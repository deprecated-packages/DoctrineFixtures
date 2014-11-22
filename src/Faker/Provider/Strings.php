<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Faker\Provider;

use Faker\Provider\Base;
use Nette;


class Strings extends Base
{

	/**
	 * @param string $s
	 * @return string
	 */
	public function webalize($s)
	{
		return Nette\Utils\Strings::webalize($s);
	}

}

<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Alice\Loader;

use Nelmio\Alice\Loader\Base;
use Nette;
use Nette\DI\Config\Helpers;


class Neon extends Base
{

	/**
	 * @param string $file
	 * @return array
	 */
	public function load($file)
	{
		$data = $this->parse($file);
		return parent::load($data);
	}


	/**
	 * @param string $file
	 * @return array
	 * @throws \UnexpectedValueException
	 */
	private function parse($file)
	{
		ob_start();
		$loader = $this;

		// isolates the file from current context variables and gives
		// it access to the $loader object to inline php blocks if needed
		$includeWrapper = function () use ($file, $loader) {
			return include $file;
		};
		$data = $includeWrapper();

		if (1 === $data) {
			// include didn't return data but included correctly, parse it as yaml
			$neon = ob_get_clean();
			$data = Nette\Neon\Neon::decode($neon);

		} else {
			// make sure to clean up if there is a failure
			ob_end_clean();
		}

		$data = $this->processIncludes($data, $file);

		return $data;
	}


	/**
	 * @param array $data
	 * @param string $file
	 * @return array
	 */
	private function processIncludes($data, $file)
	{
		if (isset($data['includes'])) {
			foreach ($data['includes'] as $include) {
				$includeFile = dirname($file) . DIRECTORY_SEPARATOR . $include;
				$includeData = $this->parse($includeFile);
				$data = Helpers::merge($data, $includeData);
			}

			unset($data['includes']);
		}

		return $data;
	}

}

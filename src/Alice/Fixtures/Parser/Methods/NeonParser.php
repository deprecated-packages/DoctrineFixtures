<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFixtures\Alice\Fixtures\Parser\Methods;

use Nelmio\Alice\Fixtures\Parser\Methods\Base;
use Nette\DI\Config\Helpers;
use Nette\Neon\Neon;


class NeonParser extends Base
{

	/**
	 * {@inheritdoc}
	 */
	protected $extension = 'neon';


	/**
	 * {@inheritdoc}
	 */
	public function parse($file)
	{
		ob_start();
		$loader = $this;

		// isolates the file from current context variables and gives
		// it access to the $loader object to inline php blocks if needed
		$includeWrapper = function () use ($file, $loader) {
			return include $file;
		};
		$data = $includeWrapper();

		if ($data === 1) {
			// include didn't return data but included correctly, parse it as yaml
			$neon = ob_get_clean();
			$data = Neon::decode($neon) ?: [];

		} else {
			// make sure to clean up if there is a failure
			ob_end_clean();
		}

		return $this->processIncludes($data, $file);
	}


	/**
	 * {@inheritdoc}
	 */
	protected function processIncludes($data, $filename)
	{
		$includeKeywords = [
			'include',
			'includes' // BC
		];
		foreach ($includeKeywords as $includeKeyword) {
			$data = $this->mergeIncludedFiles($data, $filename, $includeKeyword);
		}

		return $data;
	}


	/**
	 * @param array $data
	 * @param string $includeKeyword
	 * @return array
	 */
	private function mergeIncludedFiles($data, $filename, $includeKeyword)
	{
		if (isset($data[$includeKeyword])) {
			foreach ($data[$includeKeyword] as $include) {
				$includeFile = dirname($filename) . DIRECTORY_SEPARATOR . $include;
				$includeData = $this->parse($includeFile);
				$data = Helpers::merge($includeData, $data);
			}
			unset($data[$includeKeyword]);
		}
		return $data;
	}

}

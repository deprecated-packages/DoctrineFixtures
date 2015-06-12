# Doctrine Fixtures

[![Build Status](https://img.shields.io/travis/Zenify/DoctrineFixtures.svg?style=flat-square)](https://travis-ci.org/Zenify/DoctrineFixtures)
[![Quality Score](https://img.shields.io/scrutinizer/g/Zenify/DoctrineFixtures.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFixtures)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Zenify/DoctrineFixtures.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFixtures)
[![Downloads this Month](https://img.shields.io/packagist/dm/zenify/doctrine-fixtures.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-fixtures)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-fixtures.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-fixtures)


This package implements all you need for effective dummy data generation:

- [nelmio/alice](https://github.com/nelmio/alice) manages all that in config. This package adds *.neon* support.
- [fzaninotto/Faker](https://github.com/fzaninotto/Faker) generates fake data.


## Install

Via Composer:

```sh
$ composer require zenify/doctrine-fixtures
```

Register extensions in `config.neon`:

```yaml
extensions:
	- Kdyby\Annotations\DI\AnnotationsExtension
	- Kdyby\Events\DI\EventsExtension
	doctrine: Kdyby\Doctrine\DI\OrmExtension
	fixtures: Zenify\DoctrineFixtures\DI\FixturesExtension
```


## Configuration

```yaml
# default values
fixtures:
	alice:
		locale: "cs_CZ" # e.g. change to en_US in case you want to use English
		seed: 1
```

For all supported locales, just check [Faker Providers](https://github.com/fzaninotto/Faker/tree/master/src/Faker/Provider).


## Usage


### Via [Alice](https://github.com/nelmio/alice)

First is based on loading `.neon`/`.yaml`. It turns them into entities and inserts them to database.
To understand fixture files, just check [nelmio/alice](https://github.com/nelmio/alice).


This fixture will create 100 products with generated name:

`fixtures/products.neon`

```yaml
Zenify\DoctrineFixtures\Tests\Entity\Product:
	"product{1..100}":
		__construct: ["<shortName()>"]
```

And then we can load them:

```php
use Zenify\DoctrineFixtures\Contract\Alice\AliceLoaderInterface;


class SomeClass
{

	/**
	 * @var AliceLoaderInterface
	 */
	private $aliceLoader;


	public function __construct(AliceLoaderInterface $aliceLoader)
	{
		$this->aliceLoader = $aliceLoader;
	}
	
	
	public function loadFixtures()
	{
		$entities = $this->aliceLoader->load(__DIR__ . '/fixtures'); // file(s) or dir(s) with fixtures
		// ...
	}

}
```

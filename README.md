# Doctrine Fixtures

[![Build Status](https://img.shields.io/travis/Zenify/DoctrineFixtures.svg?style=flat-square)](https://travis-ci.org/Zenify/DoctrineFixtures)
[![Quality Score](https://img.shields.io/scrutinizer/g/Zenify/DoctrineFixtures.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFixtures)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Zenify/DoctrineFixtures.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFixtures)
[![Downloads this Month](https://img.shields.io/packagist/dm/zenify/doctrine-fixtures.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-fixtures)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-fixtures.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-fixtures)


Integration of [nelmio/alice](https://github.com/nelmio/alice) to Nette DI.
This package adds `.neon` support to Alice.

Alice uses [fzaninotto/Faker](https://github.com/fzaninotto/Faker) to generate fake data, so be sure to check that too.


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
	locale: "cs_CZ" # e.g. change to en_US in case you want to use English
	seed: 1
```

For all supported locales, just check [Faker Providers](https://github.com/fzaninotto/Faker/tree/master/src/Faker/Provider).


## Usage

We can load `.neon`/`.yaml` with specific fixtures structure. Alice turns them into entities and inserts them to database. To understand fixture files, just check the [README of nelmio/alice](https://github.com/nelmio/alice).

For example, this fixture will create 100 products with generated name:

`fixtures/products.neon`

```yaml
Zenify\DoctrineFixtures\Tests\Entity\Product:
	"product{1..100}":
		__construct: ["<shortName()>"]
```

You can also include others fixures:

`products.neon`

```yaml
includes:
	- categories.neon

Zenify\DoctrineFixtures\Tests\Entity\Product:
	"product{1..100}":
		__construct: ["<shortName()>"]
		category: "@category@brand<numberBetween(1, 10)>"
```

`categories.neon`

```yaml
Zenify\DoctrineFixtures\Tests\Entity\Category:
	"category{1..10}":
		__construct: ["<shortName()>"]
```

Note: the keyword is `include` for `yaml` files.


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
		// as arg can be used file(s) or dir(s) with fixtures
		$entities = $this->aliceLoader->load(__DIR__ . '/fixtures');
		// ...
	}

}
```

That's it!


## Testing

```sh
$ phpunit
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

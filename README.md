# Doctrine Fixtures

[![Build Status](https://img.shields.io/travis/Zenify/DoctrineFixtures.svg?style=flat-square)](https://travis-ci.org/Zenify/DoctrineFixtures)
[![Quality Score](https://img.shields.io/scrutinizer/g/Zenify/DoctrineFixtures.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFixtures)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Zenify/DoctrineFixtures.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFixtures)
[![Downloads this Month](https://img.shields.io/packagist/dm/zenify/doctrine-fixtures.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-fixtures)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-fixtures.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-fixtures)


This package implements all you need for effective dummy data generation:

- [doctrine/data-fixtures](https://github.com/doctrine/data-fixtures) allows you to populate your dev database or provide data for tests.
- [fzaninotto/Faker](https://github.com/fzaninotto/Faker) generates fake data.
- [nelmio/alice](https://github.com/nelmio/alice) manages all that in config. This package adds *.neon* support.


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

doctrine:
	host: localhost
	user: root
	password: 
	dbname: database
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


### Fixture files 

This extension loads fixtures from `*.neon` files, turns them into entities and inserts them to database.

To understand fixture files, just check [nelmio/alice](https://github.com/nelmio/alice).

Short example: this will create 100 products with generated name:

```yaml
Zenify\DoctrineFixtures\Tests\Entity\Product:
	"product{1..100}":
		__construct: ["<shortName()>"]
```


## Usage

When you have your fixtures files ready, you have 2 options to load them:

### Via CLI

Run in console in your project's root:

```sh
# show all commands
$ php www/index.php
 
# run fixture command 
$ php www/index.php doctrine:fixtures:load 

# get info about fixture command 
$ php www/index.php doctrine:fixtures:load -h 
```

### In the code via FixturesLoader 

```php
$fixturesLoader = new Zenify\DoctrineFixtures\DataFixtures\Loader;
$fixturesLoader->loadFromDirectory(__DIR__ . '/fixtures');

$loadedEntities = $this->fixturesLoader->getFixtures(); // get loaded entities
```

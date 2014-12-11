# Zenify/DoctrineFixtures

[![Build Status](https://img.shields.io/travis/Zenify/DoctrineFixtures.svg?style=flat-square)](https://travis-ci.org/Zenify/DoctrineFixtures)
[![Quality Score](https://img.shields.io/scrutinizer/g/Zenify/DoctrineFixtures.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFixtures)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Zenify/DoctrineFixtures.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFixtures)
[![Downloads this Month](https://img.shields.io/packagist/dm/zenify/doctrine-fixtures.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-fixtures)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-fixtures.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-fixtures)


This package implements all you need for effective dummy data generation:

- [doctrine/data-fixtures](https://github.com/doctrine/data-fixtures) allows you to populate your dev database or provide data for tests.
- [fzaninotto/Faker](https://github.com/fzaninotto/Faker) generates fake data.
- [nelmio/alice](https://github.com/nelmio/alice) manages all that in config. This package adds *.neon* support.


## Installation

Install last version via Composer:

```sh
$ composer require zenify/doctrine-fixtures
```

Register extension in `config.neon`:

```yaml
extensions:
	- Zenify\DoctrineFixtures\DI\FixturesExtension
```


## Usage

See [tests](tests/ZenifyTests)

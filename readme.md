# Zenify/DoctrineFixtures

[![Build Status](https://travis-ci.org/Zenify/DoctrineFixtures.svg?branch=master)](https://travis-ci.org/Zenify/DoctrineFixtures)
[![Downloads this Month](https://img.shields.io/packagist/dm/zenify/doctrine-fixtures.svg)](https://packagist.org/packages/zenify/doctrine-fixtures)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-fixtures.svg)](https://packagist.org/packages/zenify/doctrine-fixtures)


This package implements all you need for effective dummy data generation:

- [doctrine/data-fixtures](https://github.com/doctrine/data-fixtures) allows you to populate your dev database or provide data for tests.
- [fzaninotto/Faker](https://github.com/fzaninotto/Faker) generates fake data.
- [nelmio/alice](https://github.com/nelmio/alice) manages all that in config. This package adds *.neon* support.



## Installation

To get the latest version, run [Composer](http://getcomposer.org/) command:

```sh
$ composer require zenify/doctrine-fixtures
```


Register extensions you need in `config.neon`:

```yaml
extensions:
	- Zenify\DoctrineFixtures\DI\FixturesExtension
```


## Usage

See [tests](tests/ZenifyTests)

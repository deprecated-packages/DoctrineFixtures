# Zenify/DoctrineFixtures

[![Build Status](https://travis-ci.org/Zenify/DoctrineFixtures.svg?branch=master)](https://travis-ci.org/Zenify/DoctrineFixtures)
[![Downloads this Month](https://img.shields.io/packagist/dm/zenify/doctrine-fixtures.svg)](https://packagist.org/packages/zenify/doctrine-fixtures)

This package implements all you need for effective dummy data generation:

- [doctrine/data-fixtures](https://github.com/doctrine/data-fixtures) allows you to populate your dev database or provide data for tests.
- [fzaninotto/Faker](https://github.com/fzaninotto/Faker) generates fake data.
- [nelmio/alice](https://github.com/nelmio/alice) manages all that in config. This package adds *.neon* support.



## Installation

The best way to install is via [Composer](http://getcomposer.org/).

```sh
$ composer require zenify/doctrine-fixtures:~1.0
```


Register extensions you need in `config.neon`:

```yaml
extensions:
	- Zenify\DoctrineFixitures\DI\FixturesExtension
```


## Usage

See [tests](tests/ZenifyTests)

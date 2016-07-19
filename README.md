# Phalcon Diff

[![Build Status](https://travis-ci.org/phalcongelist/php-diff.svg?branch=master)](http://travis-ci.org/phalcongelist/php-diff)

## About this repo

This is a fork of [Chris Boulton's Diff][fork] project.

## Introduction

Phalcon Diff is a comprehensive library for generating differences between
two hashable objects (strings or arrays). Generated differences can be
rendered in all of the standard formats including:

 * Unified
 * Context
 * Inline HTML
 * Side by Side HTML

The logic behind the core of the diff engine (ie, the sequence matcher)
is primarily based on the Python difflib package. The reason for doing
so is primarily because of its high degree of accuracy.

Please write us if you have any feedback.

## Get Started

### Requirements

To run this library on your project, you need at least:

* PHP >= 5.4

### Installation

Install [Composer][composer] in a common location or in your project:

```sh
$ curl -s http://getcomposer.org/installer | php
```

Create the `composer.json` file as follows:

```json
{
    "require": {
        "phalcongelist/php-diff": "~2.0"
    }
}
```

Run the composer installer:

```sh
$ php composer.phar install
```

## Example Use

More complete documentation will be available shortly.

## Todo

 * Ability to ignore blank line changes
 * 3 way diff support
 * Performance optimizations

## License

Phalcon Diff is open-sourced software licensed under the [New BSD License][license].

© 2016, Phalcon Framework Team and contributors <br>
© 2009-2016, Chris Boulton <chris.boulton@interspire.com> <br>
All rights reserved.

[fork]: https://github.com/chrisboulton/php-diff
[composer]: https://getcomposer.org
[license]: https://github.com/phalcongelist/php-diff/blob/master/docs/LICENSE.txt

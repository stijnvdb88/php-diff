# Phalcon Diff

## About this repo

This is a fork of [Chris Boulton's Diff][fork] project.

## Introduction

A comprehensive library for generating differences between
two hashable objects (strings or arrays). Generated differences can be
rendered in all of the standard formats including:

 * Unified
 * Context
 * Inline HTML
 * Side by Side HTML

The logic behind the core of the diff engine (ie, the sequence matcher)
is primarily based on the Python difflib package. The reason for doing
so is primarily because of its high degree of accuracy.

## Example Use

More complete documentation will be available shortly.

## Todo

 * Ability to ignore blank line changes
 * 3 way diff support
 * Performance optimizations

## License

Phalcon Diff is open-sourced software licensed under the [New BSD License][license].

© 2009-2016, Chris Boulton <chris.boulton@interspire.com> <br>
© 2016, Phalcon Framework Team and contributors <br>
All rights reserved.

[fork]: https://github.com/chrisboulton/php-diff
[license]: https://github.com/phalcongelist/php-diff/blob/master/docs/LICENSE.txt

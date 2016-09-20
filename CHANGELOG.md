# [2.0.5](https://github.com/phalcongelist/php-diff/releases/tag/v2.0.5) (2016-XX-XX)


# [2.0.4](https://github.com/phalcongelist/php-diff/releases/tag/v2.0.4) (2016-09-20)

* Added ability to set title for `SideBySide` renderer
* Added ability to set title for `Inline` renderer
* Added `mbstring` extension as package dependency [#6](https://github.com/phalcongelist/php-diff/issues/6)

# [2.0.3](https://github.com/phalcongelist/php-diff/releases/tag/v2.0.3) (2016-07-18)

* Fixed `BaseArray` class name
* Fixed `SequenceMatcher::getMatchingBlocks`

# [2.0.2](https://github.com/phalcongelist/php-diff/releases/tag/v2.0.2) (2016-07-18)

* Fixed `Renderer` namespace

# [2.0.1](https://github.com/phalcongelist/php-diff/releases/tag/v2.0.1) (2016-07-18)

* Fixed Composer autoload namespace
* Adding a `CONTRIBUTING.md` file

# [2.0.0](https://github.com/phalcongelist/php-diff/releases/tag/v2.0.0) (2016-07-17)

* Refactored code in order to follow PSR2 coding standard
* Introduced `Phalcon\Diff\Render\RenderInterface`
* Fixed `Phalcon\Diff::getGroupedOpcodes`. Missing `context` option pass to `SequenceMatcher`

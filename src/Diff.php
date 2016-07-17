<?php

/*
 +------------------------------------------------------------------------+
 | Phalcon Diff                                                           |
 +------------------------------------------------------------------------+
 | Copyright (c) 2009-2016, Chris Boulton <chris.boulton@interspire.com>  |
 | Copyright (c) 2016 Phalcon Team and contributors                       |
 +------------------------------------------------------------------------+
 | This source file is subject to the New BSD License that is bundled     |
 | with this package in the file docs/LICENSE.txt.                        |
 |                                                                        |
 | If you did not receive a copy of the license and are unable to         |
 | obtain it through the world-wide-web, please send an email             |
 | to license@phalconphp.com so we can send you a copy immediately.       |
 +------------------------------------------------------------------------+
*/

namespace Phalcon;

use Phalcon\Diff\SequenceMatcher;
use Phalcon\Diff\Render\RenderInterface;

/**
 * Diff
 *
 * A comprehensive library for generating differences between two strings
 * in multiple formats (unified, side by side HTML etc)
 *
 * @package Phalcon
 */
class Diff
{
    /**
     * The "old" sequence to use as the basis for the comparison.
     * @var array
     */
    private $a = null;

    /**
     * The "new" sequence to generate the changes for.
     * @var array
     */
    private $b = null;

    /**
     * Array containing the generated opcodes for the differences between the two items.
     * @var array
     */
    private $groupedCodes = null;

    /**
     * Associative array of the default options available for the diff class and their default value.
     * @var array
     */
    private $defaultOptions = [
        'context' => 3,
        'ignoreNewLines' => false,
        'ignoreWhitespace' => false,
        'ignoreCase' => false
    ];

    /**
     * Array of the options that have been applied for generating the diff.
     * @var array
     */
    private $options = [];

    /**
     * The constructor.
     *
     * @param array $a Array containing the lines of the first string to compare.
     * @param array $b Array containing the lines for the second string to compare. [Optional]
     * @param array $options
     */
    public function __construct(array $a, array $b, array $options = [])
    {
        $this->a = $a;
        $this->b = $b;

        $this->options = array_merge($this->defaultOptions, $options);
    }

    /**
     * Render a diff using the supplied rendering class and return it.
     *
     * @param RenderInterface $renderer An instance of the rendering object to use for generating the diff.
     * @return string The generated diff. Exact return value depends on the rendered.
     */
    public function render(RenderInterface $renderer)
    {
        $renderer->diff = $this;

        return $renderer->render();
    }

    /**
     * Get a range of lines from $start to $end from the first comparison string
     * and return them as an array. If no values are supplied, the entire string
     * is returned. It's also possible to specify just one line to return only
     * that line.
     *
     * @param int $start The starting number. [Optional]
     * @param int $end The ending number. If not supplied, only the item in $start will be returned. [Optional]
     * @return array Array of all of the lines between the specified range.
     */
    public function getA($start = 0, $end = null)
    {
        if (0 ===  $start && null === $end) {
            return $this->a;
        }

        if (null === $end) {
            $length = 1;
        } else {
            $length = $end - $start;
        }

        return array_slice($this->a, $start, $length);
    }

    /**
     * Get a range of lines from $start to $end from the second comparison string
     * and return them as an array. If no values are supplied, the entire string
     * is returned. It's also possible to specify just one line to return only
     * that line.
     *
     * @param int $start The starting number. [Optional]
     * @param int $end The ending number. If not supplied, only the item in $start will be returned. [Optional]
     * @return array Array of all of the lines between the specified range.
     */
    public function getB($start = 0, $end = null)
    {
        if (0 === $start && null === $end) {
            return $this->b;
        }

        if (null === $end) {
            $length = 1;
        } else {
            $length = $end - $start;
        }

        return array_slice($this->b, $start, $length);
    }

    /**
     * Generate a list of the compiled and grouped opcodes for the differences between the
     * two strings. Generally called by the renderer, this class instantiates the sequence
     * matcher and performs the actual diff generation and return an array of the opcodes
     * for it. Once generated, the results are cached in the diff class instance.
     *
     * @return array Array of the grouped opcodes for the generated diff.
     */
    public function getGroupedOpcodes()
    {
        if (null ==! $this->groupedCodes) {
            return $this->groupedCodes;
        }

        $sequenceMatcher = new SequenceMatcher($this->a, $this->b, $this->options);
        $this->groupedCodes = $sequenceMatcher->getGroupedOpcodes($this->options['context']);

        return $this->groupedCodes;
    }
}

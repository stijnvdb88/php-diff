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

namespace Phalcon\Diff\Render\Text;

use Phalcon\Diff\Render\AbstractRender;

/**
 * Context diff generator for PHP DiffLib.
 *
 * @package Phalcon\Diff\Render\Text
 */
class Context extends AbstractRender
{
    /**
     * Array of the different opcode tags and how they map to the context diff equivalent.
     * @var array
     */
    private $tagMap = [
        'insert' => '+',
        'delete' => '-',
        'replace' => '!',
        'equal' => ' '
    ];

    /**
     * Render and return a context formatted (old school!) diff file.
     *
     * @return string The generated context diff.
     */
    public function render()
    {
        $diff = '';
        $opCodes = $this->diff->getGroupedOpcodes();
        foreach ($opCodes as $group) {
            $diff .= "***************\n";
            $lastItem = count($group)-1;
            $i1 = $group[0][1];
            $i2 = $group[$lastItem][2];
            $j1 = $group[0][3];
            $j2 = $group[$lastItem][4];

            if ($i2 - $i1 >= 2) {
                $diff .= '*** ' . ($group[0][1] + 1) . ',' . $i2 . " ****" . PHP_EOL;
            } else {
                $diff .= '*** ' . $i2." ****\n";
            }

            if ($j2 - $j1 >= 2) {
                $separator = '--- ' . ($j1 + 1) . ',' . $j2 . " ----" . PHP_EOL;
            } else {
                $separator = '--- ' . $j2 . " ----" . PHP_EOL;
            }

            $hasVisible = false;
            foreach ($group as $code) {
                if ($code[0] == 'replace' || $code[0] == 'delete') {
                    $hasVisible = true;
                    break;
                }
            }

            if ($hasVisible) {
                foreach ($group as $code) {
                    list ($tag, $i1, $i2, $j1, $j2) = $code;
                    if ($tag == 'insert') {
                        continue;
                    }

                    $diff .= $this->tagMap[$tag] .
                             ' ' .
                             implode(PHP_EOL . $this->tagMap[$tag] . ' ', $this->diff->getA($i1, $i2)) . PHP_EOL;
                }
            }

            $hasVisible = false;
            foreach ($group as $code) {
                if ($code[0] == 'replace' || $code[0] == 'insert') {
                    $hasVisible = true;
                    break;
                }
            }

            $diff .= $separator;

            if ($hasVisible) {
                foreach ($group as $code) {
                    list ($tag, $i1, $i2, $j1, $j2) = $code;
                    if ($tag == 'delete') {
                        continue;
                    }

                    $diff .= $this->tagMap[$tag] .
                             ' ' .
                             implode(PHP_EOL . $this->tagMap[$tag] . ' ', $this->diff->getB($j1, $j2)) . PHP_EOL;
                }
            }
        }

        return $diff;
    }
}

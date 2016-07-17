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

namespace Phalcon\Diff\Render\Html;

use Phalcon\Diff\Render\AbstractRender;

/**
 * Base renderer for rendering HTML based diffs for PHP DiffLib.
 *
 * @package Phalcon\Diff\Render\Text
 */
class BaseArray extends AbstractRender
{
    /**
     * Array of the default options that apply to this renderer.
     * @var array
     */
    protected $defaultOptions = [
        'tabSize' => 4
    ];

    /**
     * Render and return an array structure suitable for generating HTML
     * based differences. Generally called by subclasses that generate a
     * HTML based diff and return an array of the changes to show in the diff.
     *
     * @return array An array of the generated chances, suitable for presentation in HTML.
     */
    public function render()
    {
        // As we'll be modifying a & b to include our change markers,
        // we need to get the contents and store them here. That way
        // we're not going to destroy the original data
        $a = $this->diff->getA();
        $b = $this->diff->getB();

        $changes = [];
        $opCodes = $this->diff->getGroupedOpcodes();
        foreach ($opCodes as $group) {
            $blocks = [];
            $lastTag = null;
            $lastBlock = 0;
            foreach ($group as $code) {
                list ($tag, $i1, $i2, $j1, $j2) = $code;

                if ($tag == 'replace' && $i2 - $i1 == $j2 - $j1) {
                    for ($i = 0; $i < ($i2 - $i1); ++$i) {
                        $fromLine = $a[$i1 + $i];
                        $toLine = $b[$j1 + $i];

                        list ($start, $end) = $this->getChangeExtent($fromLine, $toLine);
                        if ($start != 0 || $end != 0) {
                            $realEnd = mb_strlen($fromLine) + $end;
                            $fromLine = mb_substr($fromLine, 0, $start)
                                . "\0"
                                . mb_substr($fromLine, $start, $realEnd - $start)
                                . "\1"
                                . mb_substr($fromLine, $realEnd);
                            $realEnd = mb_strlen($toLine) + $end;
                            $toLine = mb_substr($toLine, 0, $start)
                                . "\0"
                                . mb_substr($toLine, $start, $realEnd - $start)
                                . "\1"
                                . mb_substr($toLine, $realEnd);
                            $a[$i1 + $i] = $fromLine;
                            $b[$j1 + $i] = $toLine;
                        }
                    }
                }

                if ($tag != $lastTag) {
                    $blocks[] = [
                        'tag' => $tag,
                        'base' => [
                            'offset' => $i1,
                            'lines' => []
                        ],
                        'changed' => [
                            'offset' => $j1,
                            'lines' => []
                        ]
                    ];
                    $lastBlock = count($blocks)-1;
                }

                $lastTag = $tag;

                if ($tag == 'equal') {
                    $lines = array_slice($a, $i1, ($i2 - $i1));
                    $blocks[$lastBlock]['base']['lines'] += $this->formatLines($lines);
                    $lines = array_slice($b, $j1, ($j2 - $j1));
                    $blocks[$lastBlock]['changed']['lines'] +=  $this->formatLines($lines);
                } else {
                    if ($tag == 'replace' || $tag == 'delete') {
                        $lines = array_slice($a, $i1, ($i2 - $i1));
                        $lines = $this->formatLines($lines);
                        $lines = str_replace(["\0", "\1"], ['<del>', '</del>'], $lines);
                        $blocks[$lastBlock]['base']['lines'] += $lines;
                    }

                    if ($tag == 'replace' || $tag == 'insert') {
                        $lines = array_slice($b, $j1, ($j2 - $j1));
                        $lines =  $this->formatLines($lines);
                        $lines = str_replace(["\0", "\1"], ['<ins>', '</ins>'], $lines);
                        $blocks[$lastBlock]['changed']['lines'] += $lines;
                    }
                }
            }

            $changes[] = $blocks;
        }
        return $changes;
    }

    /**
     * Given two strings, determine where the changes in the two strings
     * begin, and where the changes in the two strings end.
     *
     * @param string $fromLine The first string.
     * @param string $toLine The second string.
     * @return array Array containing the starting position (0 by default) and the ending position (-1 by default)
     */
    private function getChangeExtent($fromLine, $toLine)
    {
        $start = 0;
        $limit = min(mb_strlen($fromLine), mb_strlen($toLine));

        while ($start < $limit && mb_substr($fromLine, $start, 1) == mb_substr($toLine, $start, 1)) {
            ++$start;
        }

        $end = -1;
        $limit = $limit - $start;

        while (-$end <= $limit && mb_substr($fromLine, $end, 1) == mb_substr($toLine, $end, 1)) {
            --$end;
        }

        return [
            $start,
            $end + 1
        ];
    }

    /**
     * Format a series of lines suitable for output in a HTML rendered diff.
     * This involves replacing tab characters with spaces, making the HTML safe
     * for output, ensuring that double spaces are replaced with &nbsp; etc.
     *
     * @param array $lines Array of lines to format.
     * @return array Array of the formatted lines.
     */
    private function formatLines($lines)
    {
        if ($this->options['tabSize'] !== false) {
            $lines = array_map(array($this, 'ExpandTabs'), $lines);
        }

        $lines = array_map([$this, 'HtmlSafe'], $lines);

        foreach ($lines as &$line) {
            $line = preg_replace_callback('# ( +)|^ #', __CLASS__."::fixSpaces", $line);
        }

        return $lines;
    }

    /**
     * Replace a string containing spaces with a HTML representation using &nbsp;.
     *
     * @param string $matches Regex matches array.
     * @return string The HTML representation of the string.
     */
    public static function fixSpaces($matches)
    {
        $spaces = isset($matches[1]) ? $matches[1] : '';
        $count = strlen($spaces);

        if ($count == 0) {
            return '';
        }

        $div = floor($count / 2);
        $mod = $count % 2;

        return str_repeat('&nbsp; ', $div).str_repeat('&nbsp;', $mod);
    }

    /**
     * Replace tabs in a single line with a number of spaces as defined by the tabSize option.
     *
     * @param string $line The containing tabs to convert.
     * @return string The line with the tabs converted to spaces.
     */
    private function expandTabs($line)
    {
        return str_replace("\t", str_repeat(' ', $this->options['tabSize']), $line);
    }

    /**
     * Make a string containing HTML safe for output on a page.
     *
     * @param string $string The string.
     * @return string The string with the HTML characters replaced by entities.
     */
    private function htmlSafe($string)
    {
        return htmlspecialchars($string, ENT_NOQUOTES, 'UTF-8');
    }
}

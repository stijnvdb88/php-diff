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

namespace Phalcon\Diff\Renderer\Text;

use Phalcon\Diff\Renderer\AbstractRender;

/**
 * Unified diff generator for PHP DiffLib.
 *
 * @package Phalcon\Diff\Renderer\Text
 */
class Unified extends AbstractRender
{
    /**
     * Render and return a unified diff.
     *
     * @return string The unified diff.
     */
    public function render()
    {
        $diff = '';
        $opCodes = $this->diff->getGroupedOpcodes();

        foreach ($opCodes as $group) {
            $lastItem = count($group)-1;
            $i1 = $group[0][1];
            $i2 = $group[$lastItem][2];
            $j1 = $group[0][3];
            $j2 = $group[$lastItem][4];

            if ($i1 == 0 && $i2 == 0) {
                $i1 = -1;
                $i2 = -1;
            }

            $diff .= '@@ -'.($i1 + 1).','.($i2 - $i1).' +'.($j1 + 1).','.($j2 - $j1)." @@".PHP_EOL;

            foreach ($group as $code) {
                list ($tag, $i1, $i2, $j1, $j2) = $code;

                if ($tag == 'equal') {
                    $diff .= ' '.implode(PHP_EOL." ", $this->diff->getA($i1, $i2)).PHP_EOL;
                } else {
                    if ($tag == 'replace' || $tag == 'delete') {
                        $diff .= '-'.implode(PHP_EOL."-", $this->diff->getA($i1, $i2)).PHP_EOL;
                    }

                    if ($tag == 'replace' || $tag == 'insert') {
                        $diff .= '+'.implode(PHP_EOL."+", $this->diff->getB($j1, $j2)).PHP_EOL;
                    }
                }
            }
        }

        return $diff;
    }
}

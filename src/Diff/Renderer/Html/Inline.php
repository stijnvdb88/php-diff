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

namespace Phalcon\Diff\Renderer\Html;

/**
 * Inline HTML diff generator for PHP DiffLib.
 *
 * @package Phalcon\Diff\Renderer\Html
 */
class Inline extends BaseArray
{
    private $oldTitle = 'Old';
    private $newTitle = 'New';
    private $diffTitle = 'Differences';

    /**
     * Render a and return diff with changes between the two sequences
     * displayed inline (under each other)
     *
     * @return string The generated inline diff.
     */
    public function render()
    {
        $changes = parent::render();
        $html = '';

        if (empty($changes)) {
            return $html;
        }

        if (isset($this->options['oldTitle'])) {
            $this->oldTitle = $this->options['oldTitle'];
        }

        if (isset($this->options['newTitle'])) {
            $this->newTitle = $this->options['newTitle'];
        }

        if (isset($this->options['diffTitle'])) {
            $this->diffTitle = $this->options['diffTitle'];
        }

        $html .= '<table class="Differences DifferencesInline">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>' . htmlspecialchars($this->oldTitle) . '</th>';
        $html .= '<th>' . htmlspecialchars($this->newTitle) . '</th>';
        $html .= '<th>' . htmlspecialchars($this->diffTitle) . '</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        foreach ($changes as $i => $blocks) {
            // If this is a separate block, we're condensing code so output ...,
            // indicating a significant portion of the code has been collapsed as
            // it is the same
            if ($i > 0) {
                $html .= '<tbody class="Skipped">';
                $html .= '<th>&hellip;</th>';
                $html .= '<th>&hellip;</th>';
                $html .= '<td>&nbsp;</td>';
                $html .= '</tbody>';
            }

            foreach ($blocks as $change) {
                $html .= '<tbody class="Change'.ucfirst($change['tag']).'">';
                // Equal changes should be shown on both sides of the diff
                if ($change['tag'] == 'equal') {
                    foreach ($change['base']['lines'] as $no => $line) {
                        $fromLine = $change['base']['offset'] + $no + 1;
                        $toLine = $change['changed']['offset'] + $no + 1;
                        $html .= '<tr>';
                        $html .= '<th>'.$fromLine.'</th>';
                        $html .= '<th>'.$toLine.'</th>';
                        $html .= '<td class="Left">'.$line.'</td>';
                        $html .= '</tr>';
                    }
                } elseif ($change['tag'] == 'insert') { // Added lines only on the right side
                    foreach ($change['changed']['lines'] as $no => $line) {
                        $toLine = $change['changed']['offset'] + $no + 1;
                        $html .= '<tr>';
                        $html .= '<th>&nbsp;</th>';
                        $html .= '<th>'.$toLine.'</th>';
                        $html .= '<td class="Right"><ins>'.$line.'</ins>&nbsp;</td>';
                        $html .= '</tr>';
                    }
                } elseif ($change['tag'] == 'delete') { // Show deleted lines only on the left side
                    foreach ($change['base']['lines'] as $no => $line) {
                        $fromLine = $change['base']['offset'] + $no + 1;
                        $html .= '<tr>';
                        $html .= '<th>'.$fromLine.'</th>';
                        $html .= '<th>&nbsp;</th>';
                        $html .= '<td class="Left"><del>'.$line.'</del>&nbsp;</td>';
                        $html .= '</tr>';
                    }
                } elseif ($change['tag'] == 'replace') { // Show modified lines on both sides
                    foreach ($change['base']['lines'] as $no => $line) {
                        $fromLine = $change['base']['offset'] + $no + 1;
                        $html .= '<tr>';
                        $html .= '<th>'.$fromLine.'</th>';
                        $html .= '<th>&nbsp;</th>';
                        $html .= '<td class="Left"><span>'.$line.'</span></td>';
                        $html .= '</tr>';
                    }

                    foreach ($change['changed']['lines'] as $no => $line) {
                        $toLine = $change['changed']['offset'] + $no + 1;
                        $html .= '<tr>';
                        $html .= '<th>&nbsp;</th>';
                        $html .= '<th>'.$toLine.'</th>';
                        $html .= '<td class="Right"><span>'.$line.'</span></td>';
                        $html .= '</tr>';
                    }
                }
                $html .= '</tbody>';
            }
        }
        $html .= '</table>';

        return $html;
    }
}

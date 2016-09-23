<?php

namespace Phalcon\Test\Unit\Diff\Renderer\Html;

use UnitTester;
use Phalcon\Diff;
use Phalcon\Diff\Renderer\Html\BaseArray;

/**
 * \Phalcon\Test\Unit\Diff\Render\Html\BaseArrayCest
 * Tests the \Phalcon\Diff\Renderer\Html\BaseArray component
 *
 * @copyright (c) 2016 Phalcon Team
 * @link      https://www.phalconphp.com
 * @author    Serghei Iakovlev <serghei@phalconphp.com>
 * @package   Phalcon\Test\Unit\Diff\Renderer\Html
 *
 * The contents of this file are subject to the New BSD License that is
 * bundled with this package in the file LICENSE.txt
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@phalconphp.com
 * so that we can send you a copy immediately.
 */
class BaseArrayCest
{
    public function simpleDelete(UnitTester $I)
    {
        $renderer = new BaseArray;
        $renderer->diff = new Diff(['test'], []);

        $result = $renderer->render();
        $expected = [
            [
                [
                    'tag'  => 'delete',
                    'base' => [
                        'offset' => 0,
                        'lines'  =>  ['test'],
                    ],
                    'changed' => [
                        'offset' => 0,
                        'lines'  => [],
                    ],
                ]
            ]
        ];

        $I->assertEquals($result, $expected);
    }

    public function replaceSpaces(UnitTester $I)
    {
        $renderer = new BaseArray;
        $renderer->diff = new Diff(['    test'], ['test']);

        $result = $renderer->render();
        $expected = [
            [
                [
                    'tag'  => 'replace',
                    'base' => [
                        'offset' => 0,
                        'lines'  =>  ['<del>&nbsp; &nbsp;</del>test'],
                    ],
                    'changed' => [
                        'offset' => 0,
                        'lines'  => ['<ins></ins>test'],
                    ],
                ]
            ]
        ];

        $I->assertEquals($result, $expected);
    }
}

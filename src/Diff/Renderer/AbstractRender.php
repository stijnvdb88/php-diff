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

namespace Phalcon\Diff\Render;

use Phalcon\Diff;

/**
 * Abstract class for diff renderers.
 *
 * @package Phalcon\Diff\Render
 */
abstract class AbstractRender implements RenderInterface
{
    /**
     * Instance of the diff class that this renderer is generating the rendered diff for.
     * @var Diff
     */
    protected $diff;

    /**
     * Array of the default options that apply to this renderer.
     * @var array
     */
    protected $defaultOptions = [];

    /**
     * Array containing the user applied and merged default options for the renderer.
     * @var array
     */
    protected $options = [];

    /**
     * The constructor. Instantiates the rendering engine and if options are passed,
     * sets the options for the renderer.
     *
     * @param array $options An array of the options for the renderer. [Optional]
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Set the options of the renderer to those supplied in the passed in array.
     * Options are merged with the default to ensure that there are not any missing
     * options.
     *
     * @param array $options Array of options to set.
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = array_merge($this->defaultOptions, $options);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return Diff
     */
    public function getDiffObject()
    {
        return $this->diff;
    }

    /**
     * {@inheritdoc}
     *
     * @param Diff $diff
     * @return $this
     */
    public function setDiffObject(Diff $diff)
    {
        $this->diff = $diff;

        return $this;
    }
}

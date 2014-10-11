<?php

namespace ManiaScript\Builder;

/**
 * The Options class hold the different options which may be configured for the builder.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Options {
    /**
     * Whether to compress the built ManiaScript.
     * @var boolean
     */
    protected $compress = false;

    /**
     * Whether to surround the built ManiaScript with a script tag.
     * @var boolean
     */
    protected $includeScriptTag = false;

    /**
     * The prefix used on all functions created by the builder.
     * @var string
     */
    protected $functionPrefix = 'MSB';

    /**
     * Sets whether to compress the built ManiaScript.
     * @param boolean $compress The compress state.
     * @return $this Implementing fluent interface.
     */
    public function setCompress($compress) {
        $this->compress = (boolean) $compress;
        return $this;
    }

    /**
     * Returns whether to compress the built ManiaScript.
     * @return boolean The compress state.
     */
    public function getCompress() {
        return $this->compress;
    }

    /**
     * Sets whether to surround the built ManiaScript with a script tag.
     * @param boolean $includeScriptTag The include script tag state.
     * @return $this Implementing fluent interface.
     */
    public function setIncludeScriptTag($includeScriptTag) {
        $this->includeScriptTag = $includeScriptTag;
        return $this;
    }

    /**
     * Returns whether to surround the built ManiaScript with a script tag.
     * @return boolean The include script tag state.
     */
    public function getIncludeScriptTag() {
        return $this->includeScriptTag;
    }

    /**
     * Sets the prefix used on all functions created by the builder.
     * @param string $functionPrefix The function prefix
     * @return $this Implementing fluent interface.
     */
    public function setFunctionPrefix($functionPrefix) {
        $this->functionPrefix = $functionPrefix;
        return $this;
    }

    /**
     * Returns the prefix used on all functions created by the builder.
     * @return string The function prefix.
     */
    public function getFunctionPrefix() {
        return $this->functionPrefix;
    }
}
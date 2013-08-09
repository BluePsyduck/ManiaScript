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
     * Sets whether to compress the built ManiaScript.
     * @param boolean $compress The compress state.
     * @return \ManiaScript\Builder\Options Implementing fluent interface.
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
     * @return \ManiaScript\Builder\Options Implementing fluent interface.
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
}
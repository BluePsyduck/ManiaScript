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
     * Whether to render the #RequireContext directive.
     * @var bool
     */
    protected $renderContextDirective = true;

    /**
     * Whether to render the directives of the script.
     * @var bool
     */
    protected $renderDirectives = true;

    /**
     * Whether to render the global code, including all the handler functions.
     * @var bool
     */
    protected $renderGlobalCode = true;

    /**
     * Whether to render the main() function.
     * @var bool
     */
    protected $renderMainFunction = true;

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

    /**
     * Sets whether to render the #RequireContext directive.
     * @param boolean $renderContextDirective The render flag.
     * @return $this Implementing fluent interface.
     */
    public function setRenderContextDirective($renderContextDirective) {
        $this->renderContextDirective = $renderContextDirective;
        return $this;
    }

    /**
     * Returns whether to render the #RequireContext directive.
     * @return boolean The render flag.
     */
    public function getRenderContextDirective() {
        return $this->renderContextDirective;
    }

    /**
     * Sets whether to render the directives of the script.
     * @param boolean $renderDirectives The render flag.
     * @return $this Implementing fluent interface.
     */
    public function setRenderDirectives($renderDirectives) {
        $this->renderDirectives = $renderDirectives;
        return $this;
    }

    /**
     * Returns whether to render the directives of the script.
     * @return boolean The render flag.
     */
    public function getRenderDirectives() {
        return $this->renderDirectives;
    }

    /**
     * Sets whether to render the global code, including all the handler functions.
     * @param boolean $renderGlobalCode The render flag.
     * @return $this Implementing fluent interface.
     */
    public function setRenderGlobalCode($renderGlobalCode) {
        $this->renderGlobalCode = $renderGlobalCode;
        return $this;
    }

    /**
     * Returns whether to render the global code, including all the handler functions.
     * @return boolean The render flag.
     */
    public function getRenderGlobalCode() {
        return $this->renderGlobalCode;
    }

    /**
     * Sets whether to render the main() function.
     * @param boolean $renderMainFunction The render flag.
     * @return $this Implementing fluent interface.
     */
    public function setRenderMainFunction($renderMainFunction) {
        $this->renderMainFunction = $renderMainFunction;
        return $this;
    }

    /**
     * Returns whether to render the main() function.
     * @return boolean The render flag.
     */
    public function getRenderMainFunction() {
        return $this->renderMainFunction;
    }
}
<?php

namespace ManiaScript\Builder;

/**
 * Class for holding the parts of the rendered ManiaScript.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class RenderedCode {
    /**
     * The #RequireContext directive.
     */
    const CONTEXT_DIRECTIVE = '#RequireContext CMlBrowser';

    /**
     * The directives of the generated script, excluding the context directive.
     * @var string
     */
    protected $directives = '';

    /**
     * The global code of the generated ManiaScript.
     * @var string
     */
    protected $globalCode = '';

    /**
     * The main function of the generated ManiaScript.
     * @var string
     */
    protected $mainFunction = '';

    /**
     * Returns the context directive.
     * @return string The context directive code.
     */
    public function getContextDirective() {
        return self::CONTEXT_DIRECTIVE . PHP_EOL;
    }

    /**
     * Sets the directives of the generated ManiaScript, excluding the context directive.
     * @param string $directives The directives code.
     * @return $this Implementing fluent interface.
     */
    public function setDirectives($directives) {
        $this->directives = $directives;
        return $this;
    }

    /**
     * Returns the directives of the generated ManiaScript, excluding the context directive.
     * @return string The directives code.
     */
    public function getDirectives() {
        return $this->directives;
    }

    /**
     * Sets the global code of the generated ManiaScript.
     * @param string $globalCode The global code.
     * @return $this Implementing fluent interface.
     */
    public function setGlobalCode($globalCode) {
        $this->globalCode = $globalCode;
        return $this;
    }

    /**
     * Returns the global code of the generated ManiaScript.
     * @return string The global code.
     */
    public function getGlobalCode() {
        return $this->globalCode;
    }

    /**
     * Sets the main function of the generated ManiaScript.
     * @param string $mainFunction The main function code.
     * @return $this Implementing fluent interface.
     */
    public function setMainFunction($mainFunction) {
        $this->mainFunction = $mainFunction;
        return $this;
    }

    /**
     * Returns the main function of the generated ManiaScript.
     * @return string The main function code.
     */
    public function getMainFunction() {
        return $this->mainFunction;
    }
}
<?php

namespace ManiaScript;

use ManiaScript\Builder\Event\AbstractEvent;
use ManiaScript\Builder\Options;
use ManiaScript\Builder\Directive\AbstractDirective;

class Builder {

    /**
     * The options of the builder.
     * @var \ManiaScript\Builder\Options
     */
    protected $options;

    /**
     * The directives.
     * @var array
     */
    protected $directives = array();

    /**
     * The built code.
     * @var string
     */
    protected $code = '';

    /**
     * Initializes the builder instance.
     */
    public function __construct() {
        $this->options = new Options();
    }

    /**
     * Sets the options of the builder.
     * @param \ManiaScript\Builder\Options $options The options.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    public function setOptions(Options $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Returns the options of the builder.
     * @return \ManiaScript\Builder\Options The options.
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * Adds a directive to the ManiaScript.
     * @param \ManiaScript\Builder\Directive\AbstractDirective $directive The directive.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    public function addDirective(AbstractDirective $directive) {
        $this->directives[$directive->getName()] = $directive;
        return $this;
    }

    /**
     * Returns a previously added directive by its name.
     * @param string $name The name of the directive.
     * @return \ManiaScript\Builder\Directive\AbstractDirective|null The directive, or null if the name is not known.
     */
    public function getDirective($name) {
        $result = null;
        if (isset($this->directives[$name])) {
            $result = $this->directives[$name];
        }
        return $result;
    }

    /**
     * Removes a previously added directive.
     * @param string $name The name of the directive.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    public function removeDirective($name) {
        if (isset($this->directives[$name])) {
            unset($this->directives[$name]);
        }
        return $this;
    }

    /**
     * Builds the ManiaScript code.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    public function build() {
        $this->code = '#RequireContext CMlScript' . PHP_EOL;

        $this->buildDirectives()
             ->compress()
             ->addScriptTag();
        return $this;
    }

    /**
     * Returns the ManiaScript code.
     * @return string The code.
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Builds the directives.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    protected function buildDirectives() {
        foreach ($this->directives as $directive) {
            /* @var $directive \ManiaScript\Builder\Directive\AbstractDirective */
            $this->code .= $directive->buildCode();
        }
        return $this;
    }

    /**
     * Compresses the code if enabled in the options.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    protected function compress() {
        if ($this->options->getCompress()) {
            $compressor = new Compressor();
            $this->code = $compressor->setCode($this->code)
                                     ->compress()
                                     ->getCompressedCode();
        }
        return $this;
    }

    /**
     * Adds the script-tag to the code if enabled in the options.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    protected function addScriptTag() {
        if ($this->options->getIncludeScriptTag()) {
            $this->code = '<script><![CDATA[' . str_replace(']]>', ']]]]><![CDATA[', $this->code) . ']]></script>';
        }
        return $this;
    }
}
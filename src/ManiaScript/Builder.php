<?php

namespace ManiaScript;

use ManiaScript\Event\AbstractEvent;
use ManiaScript\Builder\Options;
use ManiaScript\Directive\AbstractDirective;
use ManiaScript\Event\Handler\Factory;


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
     * The event handler factory.
     * @var \ManiaScript\Event\Handler\Factory
     */
    protected $eventHandlerFactory;

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
        $this->eventHandlerFactory = new Factory();
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
     * @param \ManiaScript\Directive\AbstractDirective $directive The directive.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    public function addDirective(AbstractDirective $directive) {
        $this->directives[$directive->getName()] = $directive;
        return $this;
    }

    /**
     * Adds an event to the builder.
     * @param \ManiaScript\Event\AbstractEvent $event The event.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    public function addEvent(AbstractEvent $event) {
        $this->eventHandlerFactory->getEventHandler($event)->addEvent($event);
        return $this;
    }

    /**
     * Builds the ManiaScript code.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    public function build() {
        $this->code = '#RequireContext CMlScript' . PHP_EOL;

        $this->prepareEvents()
             ->buildDirectives()
             ->buildGlobalCode()
             ->buildMainFunction()
             ->compress()
             ->addScriptTag();
        return $this;
    }

    /**
     * Returns the ManiaScript code.
     * @return string The code.
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Prepares all the events.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    protected function prepareEvents() {
        foreach ($this->eventHandlerFactory->getAllHandlers() as $handler) {
            /* @var $handler \ManiaScript\Event\Handler\AbstractHandler */
            $handler->buildCode();
        }
        return $this;
    }

    /**
     * Builds the directives.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    protected function buildDirectives() {
        foreach ($this->directives as $directive) {
            /* @var $directive \ManiaScript\Directive\AbstractDirective */
            $this->code .= $directive->buildCode();
        }
        return $this;
    }

    /**
     * Builds the global code of the ManiaScript.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    protected function buildGlobalCode() {
        foreach ($this->eventHandlerFactory->getAllHandlers() as $handler) {
            /* @var $handler \ManiaScript\Event\Handler\AbstractHandler */
            $this->code .= $handler->getGlobalCode();
        }
        return $this;
    }

    /**
     * Builds the main function of the ManiaScript.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    protected function buildMainFunction() {
        $this->code .= 'main() {' . PHP_EOL
                     . '    while(True) {' . PHP_EOL
                     . '        yield;' . PHP_EOL
                     . '        foreach (Event in PendingEvents) {' . PHP_EOL
                     . '            switch (Event.Type) {' . PHP_EOL;

        foreach ($this->eventHandlerFactory->getAllControlHandlers() as $handler) {
            /* @var $handler \ManiaScript\Event\Handler\AbstractHandler */
            $this->code .= $handler->getInlineCode();
        }

        $this->code .= '            }' . PHP_EOL
                     . '        }' . PHP_EOL
                     . '    }'
                     . '}';
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
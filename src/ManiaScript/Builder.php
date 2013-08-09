<?php

namespace ManiaScript;

use ManiaScript\Builder\Code;
use ManiaScript\Builder\PriorityQueue;
use ManiaScript\Builder\Event\AbstractEvent;
use ManiaScript\Builder\Options;
use ManiaScript\Builder\Directive\AbstractDirective;
use ManiaScript\Builder\Event\Handler\Factory;

/**
 * The main Builder class for ManiaScript.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
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
     * @var \ManiaScript\Builder\Event\Handler\Factory
     */
    protected $eventHandlerFactory;

    /**
     * Any code in the global scope.
     * @var \ManiaScript\Builder\PriorityQueue
     */
    protected $globalCodes;

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
        $this->globalCodes = new PriorityQueue();
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
     * Adds code to the global scope.
     * @param \ManiaScript\Builder\Code $code The code.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    public function addGlobalCode(Code $code) {
        $this->globalCodes->add($code);
        return $this;
    }

    /**
     * Adds an event to the builder.
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    public function addEvent(AbstractEvent $event) {
        $this->eventHandlerFactory->getHandlerForEvent($event)->addEvent($event);
        return $this;
    }

    /**
     * Builds the ManiaScript code.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    public function build() {
        $this->code = '#RequireContext CMlScript' . PHP_EOL;

        $this->prepareHandlers()
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
    protected function prepareHandlers() {
        foreach ($this->eventHandlerFactory->getAllHandlers() as $handler) {
            /* @var $handler \ManiaScript\Builder\Event\Handler\AbstractHandler */
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
            /* @var $directive \ManiaScript\Builder\Directive\AbstractDirective */
            $this->code .= $directive->buildCode();
        }
        return $this;
    }

    /**
     * Builds the global code of the ManiaScript.
     * @return \ManiaScript\Builder Implementing fluent interface.
     */
    protected function buildGlobalCode() {
        foreach ($this->globalCodes as $code) {
            /* @var $code \ManiaScript\Builder\Code */
            $this->code .= $code->getCode() . PHP_EOL;
        }
        foreach ($this->eventHandlerFactory->getAllHandlers() as $handler) {
            /* @var $handler \ManiaScript\Builder\Event\Handler\AbstractHandler */
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
                     . $this->eventHandlerFactory->getHandler('Load')->getInlineCode()
                     . '    yield;' . PHP_EOL
                     . $this->eventHandlerFactory->getHandler('FirstLoop')->getInlineCode()
                     . '    while(True) {' . PHP_EOL
                     . $this->eventHandlerFactory->getHandler('Loop')->getInlineCode()
                     . '        foreach (Event in PendingEvents) {' . PHP_EOL
                     . '            switch (Event.Type) {' . PHP_EOL;

        foreach ($this->eventHandlerFactory->getAllControlHandlers() as $handler) {
            /* @var $handler \ManiaScript\Builder\Event\Handler\AbstractHandler */
            $this->code .= $handler->getInlineCode();
        }

        $this->code .= '            }' . PHP_EOL
                     . '        }' . PHP_EOL
                     . '    yield;' . PHP_EOL
                     . '    }' . PHP_EOL
                     . '}' . PHP_EOL;
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
            $this->code = '<script><![CDATA[' . str_replace(']]>', ']]]]><![CDATA[>', $this->code) . ']]></script>';
        }
        return $this;
    }
}
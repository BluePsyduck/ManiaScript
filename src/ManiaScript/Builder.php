<?php

namespace ManiaScript;

use ManiaScript\Builder\Code;
use ManiaScript\Builder\PriorityQueue;
use ManiaScript\Builder\Event\AbstractEvent;
use ManiaScript\Builder\Options;
use ManiaScript\Builder\Directive\AbstractDirective;
use ManiaScript\Builder\Event\Handler\Factory;
use ManiaScript\Builder\RenderedCode;

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
     * The rendered script instance.
     * @var \ManiaScript\Builder\RenderedCode
     */
    protected $renderedCode;

    /**
     * Initializes the builder instance.
     */
    public function __construct() {
        $this->options = new Options();
        $this->eventHandlerFactory = new Factory($this);
        $this->globalCodes = new PriorityQueue();
        $this->renderedCode = new RenderedCode();
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
     * @return $this Implementing fluent interface.
     */
    public function addDirective(AbstractDirective $directive) {
        $this->directives[$directive->getName()] = $directive;
        return $this;
    }

    /**
     * Adds code to the global scope.
     * @param \ManiaScript\Builder\Code $code The code.
     * @return $this Implementing fluent interface.
     */
    public function addGlobalCode(Code $code) {
        $this->globalCodes->add($code);
        return $this;
    }

    /**
     * Adds an event to the builder.
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event.
     * @return $this Implementing fluent interface.
     */
    public function addEvent(AbstractEvent $event) {
        $this->eventHandlerFactory->getHandlerForEvent($event)->addEvent($event);
        return $this;
    }

    /**
     * Returns the code to use to call a custom event.
     * @param string $name The name of the custom event to call.
     * @return string The code. Insert it into any other ManiaScript code.
     */
    public function getTriggerCustomEventCode($name) {
        /* @var $handler \ManiaScript\Builder\Event\Handler\Custom */
        $handler = $this->eventHandlerFactory->getHandler('Custom');
        return $handler->getTriggerCustomEventCode($name);

    }

    /**
     * Returns the code to add a new timer.
     * @param string $name The name of the timer.
     * @param int $delay The delay of the timer in milliseconds.
     * @param bool $replaceExisting Whether to replace existing timers with the same name.
     * @return string The code. Insert it into any other ManiaScript code.
     */
    public function getAddTimerCode($name, $delay, $replaceExisting = false) {
        /* @var $handler \ManiaScript\Builder\Event\Handler\Timer */
        $handler = $this->eventHandlerFactory->getHandler('Timer');
        return $handler->getAddTimerCode($name, $delay, $replaceExisting);
    }

    /**
     * Builds the ManiaScript code.
     * @return $this Implementing fluent interface.
     */
    public function build() {
        $this->prepareHandlers();
        $this->renderedCode = new RenderedCode();
        $this->renderedCode->setDirectives($this->buildDirectives())
                           ->setGlobalCode($this->buildGlobalCode())
                           ->setMainFunction($this->buildMainFunction());
        return $this;
    }

    /**
     * Returns the rendered ManiaScript code.
     * @return string The code.
     */
    public function getCode() {
        $result = '';

        if ($this->options->getRenderContextDirective()) {
            $result .= $this->renderedCode->getContextDirective();
        }
        if ($this->options->getRenderDirectives()) {
            $result .= $this->renderedCode->getDirectives();
        }
        if ($this->options->getRenderGlobalCode()) {
            $result .= $this->renderedCode->getGlobalCode();
        }
        if ($this->options->getRenderMainFunction()) {
            $result .= $this->renderedCode->getMainFunction();
        }

        if ($this->options->getCompress()) {
            $result = $this->compress($result);
        }
        if ($this->options->getIncludeScriptTag()) {
            $result = $this->addScriptTag($result);
        }

        return $result;
    }

    /**
     * Prepares all the events.
     * @return $this Implementing fluent interface.
     */
    protected function prepareHandlers() {
        foreach ($this->eventHandlerFactory->getAllHandlers() as $handler) {
            /* @var $handler \ManiaScript\Builder\Event\Handler\AbstractHandler */
            $handler->prepare();
        }
        return $this;
    }

    /**
     * Builds the directives.
     * @return $this Implementing fluent interface.
     */
    protected function buildDirectives() {
        $result = '';
        foreach ($this->directives as $directive) {
            /* @var $directive \ManiaScript\Builder\Directive\AbstractDirective */
            $result .= $directive->buildCode();
        }
        return $result;
    }

    /**
     * Builds the global code of the ManiaScript.
     * @return $this Implementing fluent interface.
     */
    protected function buildGlobalCode() {
        $result = '';
        foreach ($this->globalCodes as $code) {
            /* @var $code \ManiaScript\Builder\Code */
            $result .= $code->getCode() . PHP_EOL;
        }
        return $result;
    }

    /**
     * Builds the main function of the ManiaScript.
     * @return $this Implementing fluent interface.
     */
    protected function buildMainFunction() {
        $functionPrefix = $this->options->getFunctionPrefix();
        $result = 'Void ' . $functionPrefix . '_Dummy() {}' . PHP_EOL
                . 'main() {' . PHP_EOL
                . $this->eventHandlerFactory->getHandler('Load')->getInlineCode()
                . '    yield;' . PHP_EOL
                . $this->eventHandlerFactory->getHandler('FirstLoop')->getInlineCode()
                . $this->buildEventLoop()
                . '}' . PHP_EOL;
        return $result;
    }

    /**
     * Builds the infinite event loop.
     * @return string The built code.
     */
    protected function buildEventLoop() {
        $eventLoop = $this->eventHandlerFactory->getHandler('Loop')->getInlineCode()
            . $this->buildControlHandlerLoop()
            . $this->eventHandlerFactory->getHandler('Custom')->getInlineCode()
            . $this->eventHandlerFactory->getHandler('Timer')->getInlineCode();

        $result = '';
        if (!empty($eventLoop)) {
            $result = '    while(True) {' . PHP_EOL
                    . $eventLoop
                    . '        yield;' . PHP_EOL
                    . '    }';
        }
        return $result;
    }

    /**
     * Builds the control handler loop.
     * @return string The built code.
     */
    protected function buildControlHandlerLoop() {
        $controlHandlerCases = $this->buildControlHandlerCases();
        $result = '';
        if (!empty($controlHandlerCases)) {
            $result = '        foreach (Event in PendingEvents) {' . PHP_EOL
                    . '            switch (Event.Type) {' . PHP_EOL
                    . $controlHandlerCases
                    . '            }' . PHP_EOL
                    . '        }' . PHP_EOL;
        }
        return $result;
    }

    /**
     * Build the control handler cases.
     * @return string The built code.
     */
    protected function buildControlHandlerCases() {
        $result = '';
        foreach ($this->eventHandlerFactory->getAllControlHandlers() as $handler) {
            /* @var $handler \ManiaScript\Builder\Event\Handler\AbstractHandler */
            $result .= $handler->getInlineCode();
        }
        return $result;
    }

    /**
     * Compresses the code.
     * @param string $code The code to compress.
     * @return string The compressed code.
     */
    protected function compress($code) {
        $compressor = new Compressor();
        $result = $compressor->setCode($code)
                             ->compress()
                             ->getCompressedCode();
        return $result;
    }

    /**
     * Adds the script tag to the code.
     * @param string $code The code to include in the script tag.
     * @return string The code with the script tag.
     */
    protected function addScriptTag($code) {
        $result = '<script><![CDATA[' . str_replace(']]>', ']]]]><![CDATA[>', $code) . ']]></script>';
        return $result;
    }
}
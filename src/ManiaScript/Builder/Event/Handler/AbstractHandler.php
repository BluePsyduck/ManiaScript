<?php

namespace ManiaScript\Builder\Event\Handler;

use ManiaScript\Builder;
use ManiaScript\Builder\Code;
use ManiaScript\Builder\PriorityQueue;
use ManiaScript\Builder\Event\AbstractEvent;

/**
 * The abstract base class of all event handlers.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
abstract class AbstractHandler {
    /**
     * The builder instance.
     * @var \ManiaScript\Builder
     */
    protected $builder;

    /**
     * The queue holding all the events of the handler.
     * @var \ManiaScript\Builder\PriorityQueue
     */
    protected $events;

    /**
     * The code to be inserted in the global scope of the ManiaScript.
     * @var string
     */
    protected $globalCode;

    /**
     * The code to be inserted directly in the event handling loop of the ManiaScript.
     * @var string
     */
    protected $inlineCode;

    /**
     * All the handler function names currently used. Keys are the names, and values are the related events.
     * @var array
     */
    protected $handlerFunctionNames = array();

    /**
     * Initializes the event handler.
     * @param \ManiaScript\Builder The builder instance.
     */
    public function __construct(Builder $builder) {
        $this->builder = $builder;
        $this->events = new PriorityQueue();
    }

    /**
     * Adds a new event ot the handler.
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event.
     * @return $this Implementing fluent interface.
     */
    public function addEvent(AbstractEvent $event) {
        $this->events->add($event);
        return $this;
    }

    /**
     * Prepares the handlers, having the code built.
     * @return $this Implementing fluent interface.
     */
    public function prepare() {
        $this->inlineCode = $this->buildInlineCode();
        $this->addGlobalCode($this->buildGlobalCode());
        return $this;
    }

    /**
     * Builds the code to be inserted directly in the event handling loop of the ManiaScript.
     * @return string The internal code.
     */
    protected function buildInlineCode() {
        return '';
    }

    /**
     * Builds the code to be inserted in the global scope of the ManiaScript.
     * @return string The global code.
     */
    protected function buildGlobalCode() {
        return '';
    }

    /**
     * Adds global code to the builder.
     * @param string $globalCode The global code.
     * @param int $priority The priority of the code. Defaults to PHP_INT_MAX to add the code to the end of the script.
     * @return $this Implementing fluent interface.
     */
    protected function addGlobalCode($globalCode, $priority = PHP_INT_MAX) {
        if (!empty($globalCode)) {
            $code = new Code();
            $code->setCode($globalCode)
                 ->setPriority($priority);
            $this->builder->addGlobalCode($code);
        }
        return $this;
    }

    /**
     * Returns the code to be inserted directly in the event handling loop of the ManiaScript.
     * @return string The inline code.
     */
    public function getInlineCode() {
        return $this->inlineCode;
    }

    /**
     * Returns the name of the handler function to be used for the specified event.
     * @param \ManiaScript\Builder\Event\AbstractEvent The event.
     * @return string The handler function name.
     */
    protected function getHandlerFunctionName(AbstractEvent $event) {
        $name = array_search($event, $this->handlerFunctionNames);
        if ($name === false) {
            $functionPrefix = $this->builder->getOptions()->getFunctionPrefix();
            $parts = explode('\\', get_class($event));
            $class = end($parts);
            $name = $functionPrefix . '_Handle' . $class . count($this->handlerFunctionNames);
            $this->handlerFunctionNames[$name] = $event;
        }
        return $name;
    }

    /**
     * Builds the handler function of the event.
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event.
     * @return string THe handler function.
     */
    protected function buildHandlerFunction($event) {
        return 'Void ' . $this->getHandlerFunctionName($event) . '() {' . PHP_EOL
            . $event->getCode() . PHP_EOL
            . '}' . PHP_EOL;
    }

    /**
     * Builds the call of the handler function of the event.
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event.
     * @return string The handler function call.
     */
    protected function buildHandlerFunctionCall($event) {
        return $this->getHandlerFunctionName($event) . '();' . PHP_EOL;
    }
}
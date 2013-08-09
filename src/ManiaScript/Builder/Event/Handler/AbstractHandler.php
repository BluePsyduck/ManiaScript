<?php

namespace ManiaScript\Builder\Event\Handler;

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
     */
    public function __construct() {
        $this->events = new PriorityQueue();
    }

    /**
     * Adds a new event ot the handler.
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event.
     * @return \ManiaScript\Builder\Event\Handler\AbstractHandler Implementing fluent interface.
     */
    public function addEvent(AbstractEvent $event) {
        $this->events->add($event);
        return $this;
    }

    /**
     * Builds the code of the events.
     * @return \ManiaScript\Builder\Event\Handler\AbstractHandler Implementing fluent interface.
     */
    public abstract function buildCode();

    /**
     * Returns the code to be inserted in the global scope of the ManiaScript.
     * @return string The global code.
     */
    public function getGlobalCode() {
        return $this->globalCode;
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
            $parts = explode('\\', get_class($event));
            $class = end($parts);
            $name = '__Handle' . $class . count($this->handlerFunctionNames);
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
        return 'Void ' . $this->getHandlerFunctionName($event) . '(CMlEvent Event) {' . PHP_EOL
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
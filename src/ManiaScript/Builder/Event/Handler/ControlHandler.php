<?php

namespace ManiaScript\Builder\Event\Handler;

use ManiaScript\Builder\Event\AbstractEvent;

/**
 * An extension of the basic event handler for all control based events.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
abstract class ControlHandler extends AbstractHandler {
    /**
     * Returns the type of the event for the ManiaScript.
     * @return string The event type.
     */
    protected function getEventType() {
        $parts = explode('\\', get_class($this));
        return end($parts);
    }

    /**
     * Builds the code to be inserted directly in the event handling loop of the ManiaScript.
     * @return string The internal code.
     */
    protected function buildInlineCode() {
        $result = '';
        if (!$this->events->isEmpty()) {
            $result .= '                case CMlEvent::Type::' . $this->getEventType() . ': {' . PHP_EOL;
            foreach ($this->events as $event) {
                $result .= $this->buildInlineCodeOfEvent($event);
            }
            $result .= '                }' . PHP_EOL;
        }
        return $result;
    }

    /**
     * Builds the code to be inserted in the global scope of the ManiaScript.
     * @return string The global code.
     */
    protected function buildGlobalCode() {
        $result = '';
        foreach ($this->events as $event) {
            $result .= $this->buildGlobalCodeOfEvent($event);
        }
        return $result;
    }

    /**
     * Builds the global code of a concrete event.
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event.
     * @return string The built global code.
     */
    protected function buildGlobalCodeOfEvent(AbstractEvent $event) {
        $result = '';
        if (!$event->getInline()) {
            $result .= $this->buildHandlerFunction($event);
        }
        return $result;
    }

    /**
     * Builds the inline code of a concrete event.
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event.
     * @return string The inline code.
     */
    protected function buildInlineCodeOfEvent(AbstractEvent $event) {
        if ($event->getInline()) {
            $code = $event->getCode() . PHP_EOL;
        } else {
            $code = $this->buildHandlerFunctionCall($event);
        }
        $condition = $this->buildCondition($event);
        if (empty($condition)) {
            $result = $code;
        } else {
            $result = '                    if (' . $condition . ') {' . PHP_EOL
                    . $code
                    . '                    }' . PHP_EOL;
        }
        return $result;
    }

    /**
     * Builds the condition to be used for the specified event.
     * @param \ManiaScript\Builder\Event\ControlEvent $event The event.
     * @return string The condition.
     */
    protected function buildCondition($event) {
        $conditions = array();
        foreach ($event->getControlIds() as $control) {
            if (substr($control, 0, 1) === '.') {
                $condition = 'Event.Control.HasClass("' . substr($control, 1) . '")';
            } else {
                $condition = 'Event.ControlId == "' . $control . '"';
            }
            $conditions[$control] = $condition;
        }

        $result = '';
        if (!empty($conditions)) {
            $result = implode(' || ', $conditions);
        }
        return $result;
    }

    /**
     * Builds the handler function of the event.
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event.
     * @return string The handler function.
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
        return $this->getHandlerFunctionName($event) . '(Event);' . PHP_EOL;
    }
}
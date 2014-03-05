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
     * Builds the code of the events.
     * @return \ManiaScript\Builder\Event\Handler\AbstractHandler Implementing fluent interface.
     */
    public function buildCode() {
        $this->globalCode = '';
        $this->inlineCode = '';

        if (!$this->events->isEmpty()) {
            $this->inlineCode .= '                case CMlEvent::Type::' . $this->getEventType() . ': {' . PHP_EOL;
            foreach ($this->events as $event) {
                $this->globalCode .= $this->buildGlobalCodeOfEvent($event);
                $this->inlineCode .= $this->buildInlineCodeOfEvent($event);
            }
            $this->inlineCode .= '                }' . PHP_EOL;
        }
        return $this;
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
     * @return \ManiaScript\Builder\Event\Handler\ControlHandler Implementing fluent interface.
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
            $conditions[$control] = 'Event.ControlId == "' . $control . '"';
        }

        $result = '';
        if (!empty($conditions)) {
            $result = implode(' || ', $conditions);
        }
        return $result;
    }
}
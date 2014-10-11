<?php

namespace ManiaScript\Builder\Event\Handler;

/**
 *  The handler for custom events.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Custom extends AbstractHandler {
    /**
     * Returns the name of the global Timers variable.
     * @return string The variable name.
     */
    protected function getTriggeredCustomEventsVariableName() {
        return $this->builder->getOptions()->getFunctionPrefix() . '_TriggeredCustomEvents';
    }

    /**
     * Prepares the handlers, having the code built.
     * @return $this Implementing fluent interface.
     */
    public function prepare() {
        parent::prepare();
        $this->addGlobalCode($this->buildInternalCode(), 0);
        return $this;
    }

    /**
     * Builds the code to be inserted directly in the event handling loop of the ManiaScript.
     * @return string The internal code.
     */
    protected function buildInlineCode() {
        $result = '';
        if (!$this->events->isEmpty()) {
            $variableName = $this->getTriggeredCustomEventsVariableName();

            $result = 'while (' . $variableName . '.count > 0) {' . PHP_EOL
                . '    switch (' . $variableName . '[0]) {' . PHP_EOL;

            foreach ($this->events as $event) {
                /* @var $event \ManiaScript\Builder\Event\Custom */
                $result .= '        case "' . $event->getName() . '": {' . PHP_EOL;
                if ($event->getInline()) {
                    $result .= $event->getCode() . PHP_EOL;
                } else {
                    $result .= '            ' . $this->buildHandlerFunctionCall($event) . PHP_EOL;
                }
                $result .= '        }' . PHP_EOL;
            }

            $result .= '    }' . PHP_EOL
                . '    declare Temp = ' . $variableName . '.removekey(0);' . PHP_EOL
                . '}' . PHP_EOL;
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
            /* @var $event \ManiaScript\Builder\Event\Custom */
            if (!$event->getInline()) {
                $result .= $this->buildHandlerFunction($event);
            }
        }
        return $result;
    }

    /**
     * Builds the code to be inserted right at the beginning of the ManiaScript.
     * @return string
     */
    protected function buildInternalCode() {
        $variableName = $this->getTriggeredCustomEventsVariableName();
        return '/** @var The list of triggered custom events, of which the handlers must be executed. */' . PHP_EOL
            . 'declare Text[] ' . $variableName . ';';
    }

    /**
     * Returns the code to use to call a custom event.
     * @param string $name The name of the custom event to call.
     * @return string The code
     */
    public function getTriggerCustomEventCode($name) {
        $variableName = $this->getTriggeredCustomEventsVariableName();
        return $variableName . '.add("' . $name . '");';
    }
}
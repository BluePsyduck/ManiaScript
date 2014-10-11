<?php

namespace ManiaScript\Builder\Event\Handler;

/**
 * The handler for the timers.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Timer extends AbstractHandler {
    /**
     * Returns the name of the global Timers variable.
     * @return string The variable name.
     */
    protected function getTimersVariableName() {
        return $this->builder->getOptions()->getFunctionPrefix() . '_Timers';
    }

    /**
     * Returns the name of the global Timers variable.
     * @return string The variable name.
     */
    protected function getAddTimerFunctionName() {
        return $this->builder->getOptions()->getFunctionPrefix() . '_AddTimer';
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
        $variableName = $this->getTimersVariableName();
        $result = 'foreach (Time => Name in ' . $variableName . ') {' . PHP_EOL
            . '    if (Time <= CurrentTime) {' . PHP_EOL
            . '        switch (Name) {' . PHP_EOL;

        foreach ($this->events as $event) {
            /* @var $event \ManiaScript\Builder\Event\Timer */
            $result .= '            case "' . $event->getName() . '": {' . PHP_EOL;
            if ($event->getInline()) {
                $result .= $event->getCode() . PHP_EOL;
            } else {
                $result .= '                ' . $this->buildHandlerFunctionCall($event) . PHP_EOL;
            }
            $result .= '            }' . PHP_EOL;
        }

        $result .= '        }' . PHP_EOL
            . '        declare Temp = ' . $variableName . '.removekey(Time);' . PHP_EOL
            . '    }' . PHP_EOL
            . '}' . PHP_EOL;
        return $result;
    }

    /**
     * Builds the code to be inserted in the global scope of the ManiaScript.
     * @return string The global code.
     */
    protected function buildGlobalCode() {
        $result = '';
        foreach ($this->events as $event) {
            /* @var $event \ManiaScript\Builder\Event\Timer */
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
        $timersVariableName = $this->getTimersVariableName();
        $addTimerFunctionName = $this->getAddTimerFunctionName();

        return <<<EOT
/** @var The list of timers waiting to be executed. */
declare Text[Integer] {$timersVariableName};

/**
 * Adds a new timer to the list of timers.
 * @param Name The name of the timer to add.
 * @param Delay The delay of the timer in milliseconds.
 * @param ReplacePrevious Whether to remove previously added timers with the same name.
 */
Void {$addTimerFunctionName}(Text Name, Integer Delay, Boolean ReplacePrevious) {
    if (ReplacePrevious) {
        while ({$timersVariableName}.exists(Name)) {
            declare Temp = {$timersVariableName}.remove(Name);
        }
    }
    declare Integer Time = CurrentTime + Delay;
    while ({$timersVariableName}.existskey(Time)) {
        Time = Time + 1; // Avoid collisions with timers triggering on the same millisecond
    }
    {$timersVariableName}[Time] = Name;
}
EOT;
    }

    /**
     * Returns the code to add a new timer.
     * @param string $name The name of the timer.
     * @param int $delay The delay of the timer in milliseconds.
     * @param bool $replaceExisting Whether to replace existing timers with the same name.
     * @return string The code.
     */
    public function getAddTimerCode($name, $delay, $replaceExisting = false) {
        $functionName = $this->getAddTimerFunctionName();
        return $functionName . '("' . $name . '", ' . $delay . ', ' . ($replaceExisting ? 'True' : 'False') . ');';
    }
}
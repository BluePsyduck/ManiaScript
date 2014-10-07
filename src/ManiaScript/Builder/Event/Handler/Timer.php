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
     * Builds the code of the events.
     * @return $this Implementing fluent interface.
     */
    public function buildCode() {
        $this->globalCode = '';
        $this->inlineCode = 'foreach (Time => Name in __Timers) {' . PHP_EOL
            . '    if (Time <= CurrentTime) {' . PHP_EOL
            . '        switch (Name) {' . PHP_EOL;

        foreach ($this->events as $event) {
            /* @var $event \ManiaScript\Builder\Event\Timer */
            $this->inlineCode .= '            case "' . $event->getName() . '": {' . PHP_EOL;
            if ($event->getInline()) {
                $this->inlineCode .= $event->getCode() . PHP_EOL;
            } else {
                $this->globalCode .= $this->buildHandlerFunction($event);
                $this->inlineCode .= '                ' . $this->buildHandlerFunctionCall($event) . PHP_EOL;
            }
            $this->inlineCode .= '            }' . PHP_EOL;
        }
        $this->inlineCode .= '        }' . PHP_EOL
            . '        declare Temp = __Timers.removekey(Time);' . PHP_EOL
            . '    }' . PHP_EOL
            . '}' . PHP_EOL;
        return $this;
    }
}
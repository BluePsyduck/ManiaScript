<?php

namespace ManiaScript\Builder\Event\Handler;

/**
 * A generic handler for all pseudo events.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class PseudoHandler extends AbstractHandler {
    /**
     * Builds the code to be inserted directly in the event handling loop of the ManiaScript.
     * @return string The internal code.
     */
    protected function buildInlineCode() {
        $result = '';
        foreach ($this->events as $event) {
            /* @var $event \ManiaScript\Builder\Event\AbstractEvent */
            if ($event->getInline()) {
                $result .= $event->getCode() . PHP_EOL;
            } else {
                $result .= $this->buildHandlerFunctionCall($event);
            }
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
            /* @var $event \ManiaScript\Builder\Event\AbstractEvent */
            if (!$event->getInline()) {
                $result .= $this->buildHandlerFunction($event);
            }
        }
        return $result;
    }
}
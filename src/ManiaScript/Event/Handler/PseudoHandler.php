<?php

namespace ManiaScript\Event\Handler;

/**
 * A generic handler for all pseudo events.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class PseudoHandler extends AbstractHandler {
    /**
     * Builds the code of the events.
     * @return \ManiaScript\Event\Handler\AbstractHandler Implementing fluent interface.
     */
    public function buildCode() {
        $this->globalCode = '';
        $this->inlineCode = '';

        foreach ($this->events as $event) {
            /* @var $event \ManiaScript\Event\AbstractEvent */
            if ($event->getInline()) {
                $this->inlineCode .= $event->getCode() . PHP_EOL;
            } else {
                $this->globalCode .= $this->buildHandlerFunction($event);
                $this->inlineCode .= $this->buildHandlerFunctionCall($event);
            }
        }
        return $this;
    }
}
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
     * Builds the code of the events.
     * @return $this Implementing fluent interface.
     */
    public function buildCode() {
        $this->inlineCode = '';
        $this->globalCode = '';

        foreach ($this->events as $event) {
            $this->globalCode .= $this->buildCodeOfEvent($event);
        }
        return $this;
    }

    /**
     * Builds the code of the specified event.
     * @param \ManiaScript\Builder\Event\Custom $event The event.
     * @return string The code of the event.
     */
    protected function buildCodeOfEvent($event) {
        $code = $event->getCode();
        $result = '';
        if (!empty($code)) {
            $result = '***' . $event->getName() . '***' . PHP_EOL
                . '***' . PHP_EOL
                . $event->getCode() . PHP_EOL
                . '***' . PHP_EOL;
        }
        return $result;
    }
}
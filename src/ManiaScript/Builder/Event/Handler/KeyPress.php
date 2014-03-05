<?php

namespace ManiaScript\Builder\Event\Handler;

/**
 * The handler for KeyPress events.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class KeyPress extends ControlHandler {
    /**
     * Builds the condition to be used for the specified event.
     * @param \ManiaScript\Builder\Event\KeyPress $event The event.
     * @return string The condition.
     */
    protected function buildCondition($event) {
        $conditions = array();
        foreach ($event->getKeyCodes() as $code) {
            $conditions[$code] = 'Event.KeyCode == ' . $code;
        }

        $result = '';
        if (!empty($conditions)) {
            $result = implode(' || ', $conditions);
        }
        return $result;
    }
}
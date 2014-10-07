<?php

namespace ManiaScript\Builder\Event\Handler;

/**
 * The handler for MenuNavigation events.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class MenuNavigation extends ControlHandler {
    /**
     * Builds the condition to be used for the specified event.
     * @param \ManiaScript\Builder\Event\MenuNavigation $event The event.
     * @return string The condition.
     */
    protected function buildCondition($event) {
        $conditions = array();
        foreach ($event->getActions() as $action) {
            $conditions[$action] = 'Event.MenuNavAction == CMlEvent::EMenuNavAction::' . $action;
        }

        $result = '';
        if (!empty($conditions)) {
            $result = implode(' || ', $conditions);
        }
        return $result;
    }
}
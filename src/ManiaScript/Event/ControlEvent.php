<?php

namespace ManiaScript\Event;

/**
 * The base class of all events, which get triggered by a concrete control of the ManiaLink.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
abstract class ControlEvent extends AbstractEvent {
    /**
     * The IDs of the controls, this event should listen for.
     * @var array
     */
    protected $controlIds = array();

    /**
     * Sets the IDs of the controls, this event should listen to.
     * @param string|array $controlIds Either a single ID as string, or multiple IDs as array.
     * @return ControlEvent Implementing fluent interface.
     */
    public function setControlIds($controlIds) {
        if (!is_array($controlIds)) {
            $controlIds = array($controlIds);
        }
        $this->controlIds = $controlIds;
        return $this;
    }

    /**
     * Returns the IDs of the controls, this event should listen to.
     * @return array The IDs of the controls as array.
     */
    public function getControlIds() {
        return $this->controlIds;
    }
}
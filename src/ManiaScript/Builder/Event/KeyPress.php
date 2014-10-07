<?php

namespace ManiaScript\Builder\Event;

/**
 * This class represents a KeyPress event of ManiaScript.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class KeyPress extends AbstractEvent {
    /**
     * The Key Codes, this event should listen to.
     * @var array
     */
    protected $keyCodes = array();

    /**
     * Sets the Key Codes, this event should listen to. Use the values of the \ManiaScript\Keys class.
     * @param int|array $keyCodes Either a single code as integer, or multiple codes as array.
     * @return $this Implementing fluent interface.
     */
    public function setKeyCodes($keyCodes) {
        if (!is_array($keyCodes)) {
            $keyCodes = array($keyCodes);
        }
        $this->keyCodes = $keyCodes;
        return $this;
    }

    /**
     * Returns the Key Codes, this event should listen to.
     * @return array The IDs of the controls as array.
     */
    public function getKeyCodes() {
        return $this->keyCodes;
    }
}
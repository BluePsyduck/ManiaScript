<?php

namespace ManiaScript\Builder\Event;

/**
 * This class represents a custom event defined and used by the ManiaScript.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Custom extends AbstractEvent {
    /**
     * The name of the custom event.
     * @var string
     */
    protected $name = '';

    /**
     * Sets the name of the custom event.
     * @param string $name The name.
     * @return $this Implementing fluent interface.
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns the name of the custom event.
     * @return string The name.
     */
    public function getName() {
        return $this->name;
    }
}
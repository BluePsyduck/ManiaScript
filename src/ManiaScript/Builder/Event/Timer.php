<?php

namespace ManiaScript\Builder\Event;

/**
 * This class represents a timer, i.e. code that is executed with a certain delay.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Timer extends AbstractEvent {
    /**
     * The name of the timer.
     * @var string
     */
    protected $name = '';

    /**
     * Sets the name of the timer.
     * @param string $name The name.
     * @return $this Implementing fluent interface.
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns the name of the timer.
     * @return string The name.
     */
    public function getName() {
        return $this->name;
    }
}
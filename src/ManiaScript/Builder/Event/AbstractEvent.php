<?php

namespace ManiaScript\Builder\Event;

use ManiaScript\Builder\PriorityQueueItem;

/**
 * The abstract base class of all events.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
abstract class AbstractEvent implements PriorityQueueItem {
    /**
     * The code to be executed when triggering the event.
     * @var string
     */
    protected $code = '';

    /**
     * The priority of the event. 0 for most important, greater for less important.
     * @var int
     */
    protected $priority = 5;

    /**
     * Whether this event is handled inline, i.e. without wrapping function.
     * @var boolean
     */
    protected $inline = false;

    /**
     * Sets the code to be executed when triggering the event.
     * @param string $code The code.
     * @return $this Implementing fluent interface.
     */
    public function setCode($code) {
        $this->code = trim($code);
        return $this;
    }

    /**
     * Returns the code to be executed when triggering the event.
     * @return string The code.
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Sets the priority of the event. 0 for most important, greater for less important.
     * @param int $priority The priority.
     * @return $this Implementing fluent interface.
     */
    public function setPriority($priority) {
        $this->priority = max(intval($priority), 0);
        return $this;
    }

    /**
     * Returns the priority of the event. 0 for most important, greater for less important.
     * @return int The priority.
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * Sets whether this event is handled inline, i.e. without wrapping function.
     * @param boolean $inline The inline state.
     * @return $this Implementing fluent interface.
     */
    public function setInline($inline) {
        $this->inline = (bool) $inline;
        return $this;
    }

    /**
     * Returns whether this event is handled inline, i.e. without wrapping function.
     * @return boolean The inline state.
     */
    public function getInline() {
        return $this->inline;
    }
}
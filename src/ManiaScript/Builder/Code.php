<?php

namespace ManiaScript\Builder;

/**
 * A class for handling generic code.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Code implements PriorityQueueItem {
    /**
     * The priority of the code.
     * @var int
     */
    protected $priority = 5;

    /**
     * The actual code.
     * @var string
     */
    protected $code = '';

    /**
     * Sets the priority of the code.
     * @param int $priority The priority.
     * @return \ManiaScript\Builder\Code Implementing fluent interface.
     */
    public function setPriority($priority) {
        $this->priority = max(intval($priority), 0);
        return $this;
    }

    /**
     * Returns the priority of the code.
     * @return int The priority.
     */
    public function getPriority() {
        return $this->priority;
    }
    /**
     * Sets the actual code.
     * @param string $code The code.
     * @return \ManiaScript\Builder\Code Implementing fluent interface.
     */
    public function setCode($code) {
        $this->code = trim($code);
        return $this;
    }

    /**
     * Returns the actual code.
     * @return string The code.
     */
    public function getCode() {
        return $this->code;
    }
}
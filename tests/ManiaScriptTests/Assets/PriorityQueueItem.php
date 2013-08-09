<?php

namespace ManiaScriptTests\Assets;

use ManiaScript\Builder\PriorityQueueItem as PriorityQueueItemInterface;

/**
 * A simple item class for the priority queue.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class PriorityQueueItem implements PriorityQueueItemInterface {
    /**
     * The priority.
     * @var int
     */
    protected $priority;

    /**
     * Initializes the item.
     * @param int $priority The priority.
     */
    public function __construct($priority) {
        $this->priority = $priority;
    }

    /**
     * Return the priority of the item. 0 for most important, bigger for less important.
     * @return int The priority.
     */
    public function getPriority() {
        return $this->priority;
    }
}
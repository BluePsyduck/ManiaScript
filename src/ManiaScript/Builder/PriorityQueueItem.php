<?php

namespace ManiaScript\Builder;

/**
 * The interface each item of the priority queue must implement.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
interface PriorityQueueItem {
    /**
     * Return the priority of the item. 0 for most important, bigger for less important.
     * @return int The priority.
     */
    public function getPriority();
}
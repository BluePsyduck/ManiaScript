<?php

namespace ManiaScript\Builder;

use Iterator;

/**
 * A queue which is able to sort the items with a priority.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class PriorityQueue implements Iterator {
    /**
     * The items of the queue as multi dimensional array: [priority][] => item
     * @var array
     */
    protected $items = array();

    /**
     * The merged item lists without priorities.
     * @var array
     */
    protected $mergedItems = array();

    /**
     * The current index while iterating through the items.
     * @var int
     */
    protected $currentIndex = -1;

    /**
     * Adds a new item to the priority queue.
     * @param PriorityQueueItem $item THe item to be added.
     * @return \ManiaScript\Builder\PriorityQueue Implementing fluent interface.
     */
    public function add(PriorityQueueItem $item) {
        $priority = $item->getPriority();
        if (!isset($this->items[$priority])) {
            $this->items[$priority] = array($item);
        } else {
            $this->items[$priority][] = $item;
        }
        return $this;
    }

    /**
     * Checks whether the queue is currently empty.
     * @return boolean The result of the check.
     */
    public function isEmpty() {
        return empty($this->items);
    }

    /**
     * Merges the items of the priority queue to one single list, paying attention to the priorities. The result is
     * written to the mergedItems property.
     * @return \ManiaScript\Builder\PriorityQueue Implementing fluent interface.
     */
    protected function mergeItems() {
        ksort($this->items);
        $this->mergedItems = array();
        foreach ($this->items as $items) {
            $this->mergedItems = array_merge($this->mergedItems, $items);
        }
        return $this;
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind() {
        $this->mergeItems();
        $this->currentIndex = 0;
    }

    /**
     * Move forward to next element
     */
    public function next() {
        ++$this->currentIndex;
    }

    /**
     * Checks if current position is valid
     * @return boolean The valid state.
     */
    public function valid() {
        return $this->currentIndex < count($this->mergedItems);
    }

    /**
     * Return the key of the current element
     * @return mixed The key, or null if no key is available.
     */
    public function key() {
        return $this->currentIndex;
    }

    /**
     * Return the current element
     * @return mixed The current element.
     */
    public function current()
    {
        $current = null;
        if (isset($this->mergedItems[$this->currentIndex])) {
            $current = $this->mergedItems[$this->currentIndex];
        }
        return $current;
    }
}
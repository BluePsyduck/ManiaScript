<?php

namespace ManiaScript\Event\Handler;

use ManiaScript\Event\AbstractEvent;

/**
 * A factory managing the event handlers.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Factory {
    /**
     * The already created event handler instances as associative array: class name => instance.
     * @var array
     */
    protected $instances = array();

    /**
     * Returns the event handler responsible for the specified event.
     * @param \ManiaScript\Event\AbstractEvent $event The event.
     * @return \ManiaScript\Event\Handler\AbstractHandler The event handler.
     */
    public function getEventHandler(AbstractEvent $event) {
        $class = end(explode('\\', get_class($event)));
        if (!isset($this->instances[$class])) {
            $handlerClass = __NAMESPACE__ . '\\' . $class;
            $this->instances[$class] = new $handlerClass();
        }
        return $this->instances[$class];
    }

    /**
     * Returns all known handlers of the factory.
     * @return array The handlers.
     */
    public function getAllHandlers() {
        return array_values($this->instances);
    }

    public function getAllControlHandlers() {
        $result = array();
        foreach ($this->instances as $instance) {
            if ($instance instanceof ControlHandler) {
                $result[] = $instance;
            }
        }
        return $result;
    }
}
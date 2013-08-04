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
     * Returns the handler of the specified name.
     * @param string $name The handler name.
     * @return \ManiaScript\Event\Handler\AbstractHandler The handler.
     */
    public function getHandler($name) {
        if (!isset($this->instances[$name])) {
            $handlerClass = __NAMESPACE__ . '\\' . $name;
            $this->instances[$name] = new $handlerClass();
        }
        return $this->instances[$name];
    }

    /**
     * Returns the event handler responsible for the specified event.
     * @param \ManiaScript\Event\AbstractEvent $event The event.
     * @return \ManiaScript\Event\Handler\AbstractHandler The event handler.
     */
    public function getHandlerForEvent(AbstractEvent $event) {
        $parts = explode('\\', get_class($event));
        $class = end($parts);
        return $this->getHandler($class);
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
<?php

namespace ManiaScript\Builder\Event\Handler;

use ManiaScript\Builder;
use ManiaScript\Builder\Event\AbstractEvent;

/**
 * A factory managing the event handlers.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Factory {
    /**
     * The builder instance.
     * @var \ManiaScript\Builder
     */
    protected $builder;

    /**
     * The already created event handler instances as associative array: class name => instance.
     * @var array
     */
    protected $instances = array();

    /**
     * Initializes the handler factory.
     * @param \ManiaScript\Builder $builder The builder instance.
     */
    public function __construct(Builder $builder) {
        $this->builder = $builder;
    }

    /**
     * Returns the handler of the specified name.
     * @param string $name The handler name.
     * @return \ManiaScript\Builder\Event\Handler\AbstractHandler The handler.
     */
    public function getHandler($name) {
        if (!isset($this->instances[$name])) {
            $handlerClass = __NAMESPACE__ . '\\' . $name;
            $this->instances[$name] = new $handlerClass($this->builder);
        }
        return $this->instances[$name];
    }

    /**
     * Returns the event handler responsible for the specified event.
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event.
     * @return \ManiaScript\Builder\Event\Handler\AbstractHandler The event handler.
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

    /**
     * Returns all ControlHandler instances currently known to the factory.
     * @return array The ControlHandlers
     */
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
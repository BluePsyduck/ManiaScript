<?php

namespace ManiaScript\Directive;

/**
 * Base class of the ManiaScript directives.
 *
 * @author Marcel
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
abstract class AbstractDirective {
    /**
     * The name of the directive.
     * @var string
     */
    protected $name;

    /**
     * Sets the name of the directive.
     * @param string $name The name.
     * @return \ManiaScript\Event\AbstractDirective Implementing fluent interface.
     */
    public function setName($name) {
        $this->name = trim($name);
        return $this;
    }

    /**
     * Returns the name of the directive.
     * @return string The name.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Builds the directive code.
     * @return string The code.
     */
    abstract public function buildCode();
}
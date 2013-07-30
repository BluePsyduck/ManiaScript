<?php

namespace ManiaScript\Builder\Directive;

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
     * The value of the directive.
     * @var string
     */
    protected $value;

    /**
     * Sets the name of the directive.
     * @param string $name The name.
     * @return \ManiaScript\Builder\Directive\AbstractDirective Implementing fluent interface.
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
     * Sets the value of the directive.
     * @param string $value The value.
     * @return \ManiaScript\Builder\Directive\AbstractDirective Implementing fluent interface.
     */
    public function setValue($value) {
        $this->value = trim($value);
        return $this;
    }

    /**
     * Returns the value of the directive.
     * @return string The value.
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Builds the directive.
     * @return string The directive.
     */
    abstract public function getCode();
}
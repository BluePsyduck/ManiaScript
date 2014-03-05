<?php

namespace ManiaScript\Builder\Directive;

/**
 * The Constant directive generates a #Const line in the ManiaScript.
 *
 * @author Marcel
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Constant extends AbstractDirective {
    /**
     * The value of the directive.
     * @var string
     */
    protected $value;

    /**
     * Sets the value of the directive.
     * @param string $value The value
     * @return \ManiaScript\Builder\Directive\Constant Implementing fluent interface.
     */
    public function setValue($value) {
        $this->value = $value;
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
     * Builds the directive code.
     * @return string The code.
     */
    public function buildCode() {
        return '#Const ' . $this->getName() . ' ' . $this->getValue() . PHP_EOL;
    }
}
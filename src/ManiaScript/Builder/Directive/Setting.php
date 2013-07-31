<?php

namespace ManiaScript\Builder\Directive;

use ManiaScript\Builder\Directive\AbstractDirective;

/**
 * The Setting directive generates a #Setting line in the ManiaScript.
 *
 * @author Marcel
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Setting extends AbstractDirective {
    /**
     * The value of the directive.
     * @var string
     */
    protected $value;

    /**
     * Sets the value of the directive.
     * @param string $value The value
     * @return \ManiaScript\Builder\Directive\Setting Implementing fluent interface.
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
        return '#Setting ' . $this->getName() . ' ' . $this->getValue() . PHP_EOL;
    }
}
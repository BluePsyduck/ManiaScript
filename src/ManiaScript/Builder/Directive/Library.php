<?php

namespace ManiaScript\Builder\Directive;

use ManiaScript\Builder\Directive\AbstractDirective;

/**
 * The Library directive generates a #Include line in the ManiaScript.
 *
 * @author Marcel
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Library extends AbstractDirective {
    /**
     * Builds the directive.
     * @return string The directive.
     */
    public function getCode() {
        $value = $this->getValue();
        if (empty($value)) {
            $value = $this->getName();
        }
        return '#Include "' . $this->getName() . '" as ' . $value . "\n";
    }
}
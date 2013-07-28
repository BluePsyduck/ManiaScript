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
     * Builds the directive.
     * @return string The directive.
     */
    public function build() {
        return '#Setting ' . $this->name . ' ' . $this->value . "\n";
    }
}
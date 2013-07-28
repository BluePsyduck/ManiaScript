<?php

namespace ManiaScript\Builder\Directive;

use ManiaScript\Builder\Directive\AbstractDirective;

/**
 * The Constant directive generates a #Const line in the ManiaScript.
 *
 * @author Marcel
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Constant extends AbstractDirective {
    /**
     * Builds the directive.
     * @return string The directive.
     */
    public function build() {
        return '#Const ' . $this->name . ' ' . $this->value . "\n";
    }
}
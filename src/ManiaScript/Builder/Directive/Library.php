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
     * Initialiozes the Library directive.
     * @param string $library The name of the library.
     * @param string $alias The alias. If omitted, the name will be used.
     */
    public function __construct($library, $alias = '') {
        if (empty($alias)) {
            $alias = $library;
        }
        parent::__construct($library, $alias);
    }

    /**
     * Builds the directive.
     * @return string The directive.
     */
    public function build() {
        return '#Include "' . $this->name . '" as ' . $this->value . "\n";
    }
}
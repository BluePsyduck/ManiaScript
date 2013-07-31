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
     * The alias of the library.
     * @var string
     */
    protected $alias;

    /**
     * Sets the alias of the library.
     * @param string $alias The alias.
     * @return Library Implementing fluent interface.
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Returns the alias of the library.
     * @return string The alias.
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Builds the directive.
     * @return string The directive.
     */
    public function buildCode() {
        $name = $this->getName();
        $alias = $this->getAlias();
        if (empty($alias)) {
            $alias = $name;
        }
        return '#Include "' . $name . '" as ' . $alias . PHP_EOL;
    }
}
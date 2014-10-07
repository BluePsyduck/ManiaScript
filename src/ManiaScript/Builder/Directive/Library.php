<?php

namespace ManiaScript\Builder\Directive;

/**
 * The Library directive generates a #Include line in the ManiaScript.
 *
 * @author Marcel
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Library extends AbstractDirective {
    /**
     * The library to include.
     * @var string
     */
    protected $library;

    /**
     * Sets the library to include.
     * @param string $library The library.
     * @return $this Implementing fluent interface.
     */
    public function setLibrary($library)
    {
        $this->library = $library;
        return $this;
    }

    /**
     * Returns the library to include.
     * @return string The alias.
     */
    public function getLibrary()
    {
        return $this->library;
    }

    /**
     * Sets the alias of the library.
     * @param string $alias The alias.
     * @return $this Implementing fluent interface.
     */
    public function setAlias($alias) {
        return $this->setName($alias);
    }

    /**
     * Returns the alias of the library.
     * @return string The alias.
     */
    public function getAlias() {
        return $this->getName();
    }

    /**
     * Builds the directive.
     * @return string The directive.
     */
    public function buildCode() {
        $library = $this->getLibrary();
        $alias = $this->getAlias();
        if (empty($alias)) {
            $alias = $library;
        }
        return '#Include "' . $library . '" as ' . $alias . PHP_EOL;
    }
}
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
     * Initializes the directive.
     * @param string $name The name.
     * @param string $value The value.
     */
    public function __construct($name, $value) {
        $this->name = trim($name);
        $this->value = trim($value);
    }

    /**
     * Builds the directive.
     * @return string The directive.
     */
    abstract public function build();
}
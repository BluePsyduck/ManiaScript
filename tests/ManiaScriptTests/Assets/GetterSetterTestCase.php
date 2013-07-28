<?php

namespace ManiaScriptTests\Assets;

use PHPUnit_Framework_TestCase;
use ReflectionProperty;

/**
 * Helper class for testing classes with private or protected properties.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class GetterSetterTestCase extends PHPUnit_Framework_TestCase {
    /**
     * Asserts that a property of an object equals the an expected value.
     * @param mixed $expected The value to be expected.
     * @param object $object The object.
     * @param string $name The name of the property.
     * @return \ManiaScriptTests\Assets\GetterSetterTestCase Implementing fluent interface.
     */
    protected function assertPropertyEquals($expected, $object, $name) {
        $reflectedProperty = new ReflectionProperty($object, $name);
        $reflectedProperty->setAccessible(true);
        $this->assertEquals($expected, $reflectedProperty->getValue($object));
        return $this;
    }

    /**
     * Injects a property value to an object.
     * @param object $object The object.
     * @param string $name The name of the property.
     * @param mixed $value The property to be injected.
     * @return \ManiaScriptTests\Assets\GetterSetterTestCase Implementing fluent interface.
     */
    protected function injectProperty($object, $name, $value) {
        $reflectedProperty = new ReflectionProperty($object, $name);
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue($object, $value);
        return $this;
    }
}
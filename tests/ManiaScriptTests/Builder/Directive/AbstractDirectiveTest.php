<?php

namespace ManiaScriptTests\Builder\Directive;

use ManiaScriptTests\Assets\GetterSetterTestCase;

/**
 * The PHPUnit test of the AbstractDirective class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class AbstractDirectiveTest extends GetterSetterTestCase {
    /**
     * Tests the setName() method.
     */
    public function testSetName() {
        $expected = 'abc';
        $directive = $this->getMockForAbstractClass('ManiaScript\Builder\Directive\AbstractDirective');
        $result = $directive->setName($expected);
        $this->assertPropertyEquals($expected, $directive, 'name');
        $this->assertEquals($directive, $result);
    }

    /**
     * Tests the getName() method.
     */
    public function testGetName() {
        $expected = 'abc';
        $directive = $this->getMockForAbstractClass('ManiaScript\Builder\Directive\AbstractDirective');
        $this->injectProperty($directive, 'name', $expected);
        $this->assertEquals($expected, $directive->getName());
    }

    /**
     * Tests the setValue() method.
     */
    public function testSetValue() {
        $expected = 'abc';
        $directive = $this->getMockForAbstractClass('ManiaScript\Builder\Directive\AbstractDirective');
        $result = $directive->setValue($expected);
        $this->assertPropertyEquals($expected, $directive, 'value');
        $this->assertEquals($directive, $result);
    }

    /**
     * Tests the getValue() method.
     */
    public function testGetValue() {
        $expected = 'abc';
        $directive = $this->getMockForAbstractClass('ManiaScript\Builder\Directive\AbstractDirective');
        $this->injectProperty($directive, 'value', $expected);
        $this->assertEquals($expected, $directive->getValue());
    }
}
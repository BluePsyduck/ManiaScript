<?php

namespace ManiaScriptTests\Directive;

use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the AbstractDirective class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class AbstractDirectiveTest extends TestCase {
    /**
     * Tests the setName() method.
     */
    public function testSetName() {
        $expected = 'abc';
        $directive = $this->getMockForAbstractClass('ManiaScript\Directive\AbstractDirective');
        $result = $directive->setName($expected);
        $this->assertPropertyEquals($expected, $directive, 'name');
        $this->assertEquals($directive, $result);
    }

    /**
     * Tests the getName() method.
     */
    public function testGetName() {
        $expected = 'abc';
        $directive = $this->getMockForAbstractClass('ManiaScript\Directive\AbstractDirective');
        $this->injectProperty($directive, 'name', $expected);
        $this->assertEquals($expected, $directive->getName());
    }
}
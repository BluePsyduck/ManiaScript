<?php

namespace ManiaScriptTests\Builder\Directive;

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
     * @covers \ManiaScript\Builder\Directive\AbstractDirective::setName
     */
    public function testSetName() {
        $expected = 'abc';
        /* @var $directive \ManiaScript\Builder\Directive\AbstractDirective|\PHPUnit_Framework_MockObject_MockObject */
        $directive = $this->getMockBuilder('ManiaScript\Builder\Directive\AbstractDirective')
                          ->getMockForAbstractClass();
        $result = $directive->setName($expected);
        $this->assertPropertyEquals($expected, $directive, 'name');
        $this->assertEquals($directive, $result);
    }

    /**
     * Tests the getName() method.
     * @covers \ManiaScript\Builder\Directive\AbstractDirective::getName
     */
    public function testGetName() {
        $expected = 'abc';
        /* @var $directive \ManiaScript\Builder\Directive\AbstractDirective|\PHPUnit_Framework_MockObject_MockObject */
        $directive = $this->getMockBuilder('ManiaScript\Builder\Directive\AbstractDirective')
                          ->getMockForAbstractClass();
        $this->injectProperty($directive, 'name', $expected);
        $this->assertEquals($expected, $directive->getName());
    }
}
<?php

namespace ManiaScriptTests\Builder\Directive;

use ManiaScript\Builder\Directive\Constant;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the Constant directive.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class ConstantTest extends TestCase {
    /**
     * Tests the setValue() method.
     * @covers \ManiaScript\Builder\Directive\Constant::setValue
     */
    public function testSetValue() {
        $expected = 'abc';
        $directive = new Constant();
        $result = $directive->setValue($expected);
        $this->assertPropertyEquals($expected, $directive, 'value');
        $this->assertEquals($directive, $result);
    }

    /**
     * Tests the getValue() method.
     * @covers \ManiaScript\Builder\Directive\Constant::getValue
     */
    public function testGetValue() {
        $expected = 'abc';
        $directive = new Constant();
        $this->injectProperty($directive, 'value', $expected);
        $this->assertEquals($expected, $directive->getValue());
    }

    /**
     * Tests the buildCode() method.
     * @covers \ManiaScript\Builder\Directive\Constant::buildCode
     */
    public function testBuildCode() {
        /* @var $directive \ManiaScript\Builder\Directive\Constant|\PHPUnit_Framework_MockObject_MockObject */
        $directive = $this->getMockBuilder('ManiaScript\Builder\Directive\Constant')
                          ->setMethods(array('getName', 'getValue'))
                          ->getMock();
        $directive->expects($this->any())
                  ->method('getName')
                  ->will($this->returnValue('abc'));
        $directive->expects($this->any())
                  ->method('getValue')
                  ->will($this->returnValue('def'));
        $result = $directive->buildCode();
        $this->assertEquals('#Const abc def' . PHP_EOL, $result);
    }
}
<?php

namespace ManiaScriptTests\Builder\Directive;

use ManiaScript\Builder\Directive\Setting;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the Setting directive.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class SettingTest extends TestCase {
    /**
     * Tests the setValue() method.
     * @covers \ManiaScript\Builder\Directive\Setting::setValue
     */
    public function testSetValue() {
        $expected = 'abc';
        $directive = new Setting();
        $result = $directive->setValue($expected);
        $this->assertPropertyEquals($expected, $directive, 'value');
        $this->assertEquals($directive, $result);
    }

    /**
     * Tests the getValue() method.
     * @covers \ManiaScript\Builder\Directive\Setting::getValue
     */
    public function testGetValue() {
        $expected = 'abc';
        $directive = new Setting();
        $this->injectProperty($directive, 'value', $expected);
        $this->assertEquals($expected, $directive->getValue());
    }

    /**
     * Tests the buildCode() method.
     * @covers \ManiaScript\Builder\Directive\Setting::buildCode
     */
    public function testBuildCode() {
        /* @var $directive \ManiaScript\Builder\Directive\Setting|\PHPUnit_Framework_MockObject_MockObject */
        $directive = $this->getMock('ManiaScript\Builder\Directive\Setting', array('getName', 'getValue'));
        $directive->expects($this->any())
                  ->method('getName')
                  ->will($this->returnValue('abc'));
        $directive->expects($this->any())
                  ->method('getValue')
                  ->will($this->returnValue('def'));
        $result = $directive->buildCode();
        $this->assertEquals('#Setting abc def' . PHP_EOL, $result);
    }
}
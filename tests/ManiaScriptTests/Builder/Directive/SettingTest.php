<?php

namespace ManiaScriptTests\Builder\Directive;

use PHPUnit_Framework_TestCase;

/**
 * The PHPUnit test of the Setting directive.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class SettingTest extends PHPUnit_Framework_TestCase {
    /**
     * Tests the getCode() method.
     */
    public function testGetCode() {
        $directive = $this->getMock('ManiaScript\Builder\Directive\Setting', array('getName', 'getValue'));
        $directive->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('abc'));
        $directive->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue('def'));
        $result = $directive->getCode();
        $this->assertEquals('#Setting abc def' . "\n", $result);
    }
}
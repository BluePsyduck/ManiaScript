<?php

namespace ManiaScriptTests\Builder\Directive;

use ManiaScriptTests\Assets\GetterSetterTestCase;

/**
 * The PHPUnit test of the Library directive.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class LibraryTest extends GetterSetterTestCase {
    /**
     * Data provider of the constructor test.
     * @return array The data.
     */
    public function providerGetCode() {
        return array(
            array('#Include "abc" as def' . "\n", 'abc', 'def'),
            array('#Include "abc" as abc' . "\n", 'abc', '')
        );
    }

    /**
     * Tests the getCode() method.
     * @param string $expected The expected string.
     * @param string $name The name of the library.
     * @param string $value The alias of the library.
     * @dataProvider providerGetCode
     */
    public function testGetCode($expected, $name, $value) {
        $directive = $this->getMock('ManiaScript\Builder\Directive\Library', array('getName', 'getValue'));
        $directive->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($name));
        $directive->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue($value));
        $result = $directive->getCode();
        $this->assertEquals($expected, $result);
    }
}
<?php

namespace ManiaScriptTests\Directive;

use ManiaScript\Directive\Library;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the Library directive.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class LibraryTest extends TestCase {
    /**
     * Tests the setAlias() method.
     */
    public function testSetAlias() {
        $expected = 'abc';
        $directive = new Library();
        $result = $directive->setAlias($expected);
        $this->assertPropertyEquals($expected, $directive, 'alias');
        $this->assertEquals($directive, $result);
    }

    /**
     * Tests the getAlias() method.
     */
    public function testGetAlias() {
        $expected = 'abc';
        $directive = new Library();
        $this->injectProperty($directive, 'alias', $expected);
        $this->assertEquals($expected, $directive->getAlias());
    }

    /**
     * Data provider of the constructor test.
     * @return array The data.
     */
    public function providerGetCode() {
        return array(
            array('#Include "abc" as def' . PHP_EOL, 'abc', 'def'),
            array('#Include "abc" as abc' . PHP_EOL, 'abc', '')
        );
    }

    /**
     * Tests the getCode() method.
     * @param string $expected The expected string.
     * @param string $name The name of the library.
     * @param string $alias The alias of the library.
     * @dataProvider providerGetCode
     */
    public function testGetCode($expected, $name, $alias) {
        /* @var $directive \ManiaScript\Directive\Library|\PHPUnit_Framework_MockObject_MockObject */
        $directive = $this->getMock('ManiaScript\Directive\Library', array('getName', 'getAlias'));
        $directive->expects($this->any())
                  ->method('getName')
                  ->will($this->returnValue($name));
        $directive->expects($this->any())
                  ->method('getAlias')
                  ->will($this->returnValue($alias));
        $result = $directive->buildCode();
        $this->assertEquals($expected, $result);
    }
}
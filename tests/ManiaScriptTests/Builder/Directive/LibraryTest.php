<?php

namespace ManiaScriptTests\Builder\Directive;

use ManiaScript\Builder\Directive\Library;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the Library directive.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class LibraryTest extends TestCase {
    /**
     * Tests the setLibrary() method.
     * @covers \ManiaScript\Builder\Directive\Library::setLibrary
     */
    public function testSetLibrary() {
        $expected = 'abc';
        $directive = new Library();
        $result = $directive->setLibrary($expected);
        $this->assertEquals($directive, $result);
        $this->assertPropertyEquals($expected, $directive, 'library');
    }

    /**
     * Tests the getLibrary() method.
     * @covers \ManiaScript\Builder\Directive\Library::getLibrary
     */
    public function testGetLibrary() {
        $expected = 'abc';
        $directive = new Library();
        $this->injectProperty($directive, 'library', $expected);
        $result = $directive->getLibrary();
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the setAlias() method.
     * @covers \ManiaScript\Builder\Directive\Library::setAlias
     */
    public function testSetAlias() {
        $expected = 'abc';

        /* @var $directive \ManiaScript\Builder\Directive\Library|\PHPUnit_Framework_MockObject_MockObject */
        $directive = $this->getMockBuilder('ManiaScript\Builder\Directive\Library')
                          ->setMethods(array('setName'))
                          ->getMock();
        $directive->expects($this->once())
                  ->method('setName')
                  ->with($expected)
                  ->will($this->returnSelf());

        $result = $directive->setAlias($expected);
        $this->assertEquals($directive, $result);
    }

    /**
     * Tests the getAlias() method.
     * @covers \ManiaScript\Builder\Directive\Library::getAlias
     */
    public function testGetAlias() {
        $expected = 'abc';

        /* @var $directive \ManiaScript\Builder\Directive\Library|\PHPUnit_Framework_MockObject_MockObject */
        $directive = $this->getMockBuilder('ManiaScript\Builder\Directive\Library')
                          ->setMethods(array('getName'))
                          ->getMock();
        $directive->expects($this->once())
                  ->method('getName')
                  ->will($this->returnValue($expected));

        $result = $directive->getAlias();
        $this->assertEquals($expected, $result);
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
     * Tests the buildCode() method.
     * @param string $expected The expected string.
     * @param string $library The name of the library.
     * @param string $alias The alias of the library.
     * @covers \ManiaScript\Builder\Directive\Library::buildCode
     * @dataProvider providerGetCode
     */
    public function testBuildCode($expected, $library, $alias) {
        /* @var $directive \ManiaScript\Builder\Directive\Library|\PHPUnit_Framework_MockObject_MockObject */
        $directive = $this->getMockBuilder('ManiaScript\Builder\Directive\Library')
                          ->setMethods(array('getLibrary', 'getAlias'))
                          ->getMock();
        $directive->expects($this->any())
                  ->method('getLibrary')
                  ->will($this->returnValue($library));
        $directive->expects($this->any())
                  ->method('getAlias')
                  ->will($this->returnValue($alias));
        $result = $directive->buildCode();
        $this->assertEquals($expected, $result);
    }
}
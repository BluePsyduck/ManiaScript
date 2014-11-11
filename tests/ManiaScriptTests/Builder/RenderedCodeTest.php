<?php

namespace ManiaScriptTests\Builder;

use ManiaScript\Builder\RenderedCode;
use ManiaScriptTests\Assets\TestCase;

/**
 * PHPUnit test of the rendered code class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class RenderedCodeTest extends TestCase {
    /**
     * Tests the __construct() method.
     * @coversNothing
     */
    public function testConstruct() {
        $renderedCode = new RenderedCode();
        $this->assertPropertyEquals('', $renderedCode, 'directives');
        $this->assertPropertyEquals('', $renderedCode, 'globalCode');
        $this->assertPropertyEquals('', $renderedCode, 'mainFunction');
    }

    /**
     * Tests the getContextDirective() method.
     * @covers \ManiaScript\Builder\RenderedCode::getContextDirective
     */
    public function testGetContextDirective() {
        $expected = '#RequireContext CMlBrowser' . PHP_EOL;
        $renderedCode = new RenderedCode();
        $result = $renderedCode->getContextDirective();
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the setDirectives() method.
     * @covers \ManiaScript\Builder\RenderedCode::setDirectives
     */
    public function testSetDirectives() {
        $expected = 'abc';
        $renderedCode = new RenderedCode();
        $result = $renderedCode->setDirectives($expected);
        $this->assertEquals($renderedCode, $result);
        $this->assertPropertyEquals($expected, $renderedCode, 'directives');
    }

    /**
     * Tests the getDirectives() method.
     * @covers \ManiaScript\Builder\RenderedCode::getDirectives
     */
    public function testGetDirectives() {
        $expected = 'abc';
        $renderedCode = new RenderedCode();
        $this->injectProperty($renderedCode, 'directives', $expected);
        $result = $renderedCode->getDirectives();
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the setGlobalCode() method.
     * @covers \ManiaScript\Builder\RenderedCode::setGlobalCode
     */
    public function testSetGlobalCode() {
        $expected = 'abc';
        $renderedCode = new RenderedCode();
        $result = $renderedCode->setGlobalCode($expected);
        $this->assertEquals($renderedCode, $result);
        $this->assertPropertyEquals($expected, $renderedCode, 'globalCode');
    }

    /**
     * Tests the getGlobalCode() method.
     * @covers \ManiaScript\Builder\RenderedCode::getGlobalCode
     */
    public function testGetGlobalCode() {
        $expected = 'abc';
        $renderedCode = new RenderedCode();
        $this->injectProperty($renderedCode, 'globalCode', $expected);
        $result = $renderedCode->getGlobalCode();
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the setMainFunction() method.
     * @covers \ManiaScript\Builder\RenderedCode::setMainFunction
     */
    public function testSetMainFunction() {
        $expected = 'abc';
        $renderedCode = new RenderedCode();
        $result = $renderedCode->setMainFunction($expected);
        $this->assertEquals($renderedCode, $result);
        $this->assertPropertyEquals($expected, $renderedCode, 'mainFunction');
    }

    /**
     * Tests the getMainFunction() method.
     * @covers \ManiaScript\Builder\RenderedCode::getMainFunction
     */
    public function testGetMainFunction() {
        $expected = 'abc';
        $renderedCode = new RenderedCode();
        $this->injectProperty($renderedCode, 'mainFunction', $expected);
        $result = $renderedCode->getMainFunction();
        $this->assertEquals($expected, $result);
    }
}

<?php

namespace ManiaScriptTests\Builder;

use ManiaScript\Builder\Options;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the Options class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class OptionsTest extends TestCase {
    /**
     * Tests property initialization on class construction.
     * @coversNothing
     */
    public function testConstruct() {
        $options = new Options();
        $this->assertPropertyEquals(false, $options, 'compress');
        $this->assertPropertyEquals(false, $options, 'includeScriptTag');
        $this->assertPropertyEquals('MSB', $options, 'functionPrefix');
        $this->assertPropertyEquals(true, $options, 'renderContextDirective');
        $this->assertPropertyEquals(true, $options, 'renderDirectives');
        $this->assertPropertyEquals(true, $options, 'renderGlobalCode');
        $this->assertPropertyEquals(true, $options, 'renderMainFunction');
    }

    /**
     * Tests the setCompress() method.
     * @covers \ManiaScript\Builder\Options::setCompress
     */
    public function testSetCompress() {
        $expected = true;
        $options = new Options();
        $result = $options->setCompress($expected);
        $this->assertPropertyEquals($expected, $options, 'compress');
        $this->assertEquals($options, $result);
    }

    /**
     * Tests the getCompress() method.
     * @covers \ManiaScript\Builder\Options::getCompress
     */
    public function testGetCompress() {
        $expected = true;
        $options = new Options();
        $this->injectProperty($options, 'compress', $expected);
        $this->assertEquals($expected, $options->getCompress());
    }

    /**
     * Tests the setIncludeScriptTag() method.
     * @covers \ManiaScript\Builder\Options::setIncludeScriptTag
     */
    public function testSetIncludeScriptTag() {
        $expected = true;
        $options = new Options();
        $result = $options->setIncludeScriptTag($expected);
        $this->assertPropertyEquals($expected, $options, 'includeScriptTag');
        $this->assertEquals($options, $result);
    }

    /**
     * Tests the getIncludeScriptTag() method.
     * @covers \ManiaScript\Builder\Options::getIncludeScriptTag
     */
    public function testGetIncludeScriptTag() {
        $expected = true;
        $options = new Options();
        $this->injectProperty($options, 'includeScriptTag', $expected);
        $this->assertEquals($expected, $options->getIncludeScriptTag());
    }

    /**
     * Tests the setFunctionPrefix() method.
     * @covers \ManiaScript\Builder\Options::setFunctionPrefix
     */
    public function testSetFunctionPrefix() {
        $expected = 'abc';
        $options = new Options();
        $result = $options->setFunctionPrefix($expected);
        $this->assertEquals($options, $result);
        $this->assertPropertyEquals($expected, $options, 'functionPrefix');
    }

    /**
     * Tests the getFunctionPrefix() method.
     * @covers \ManiaScript\Builder\Options::getFunctionPrefix
     */
    public function testGetFunctionPrefix() {
        $expected = 'abc';
        $options = new Options();
        $this->injectProperty($options, 'functionPrefix', $expected);
        $result = $options->getFunctionPrefix();
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the setRenderContextDirective() method.
     * @covers \ManiaScript\Builder\Options::setRenderContextDirective
     */
    public function testSetRenderContextDirective() {
        $expected = false;
        $options = new Options();
        $result = $options->setRenderContextDirective($expected);
        $this->assertEquals($options, $result);
        $this->assertPropertyEquals($expected, $options, 'renderContextDirective');
    }

    /**
     * Tests the getRenderContextDirective() method.
     * @covers \ManiaScript\Builder\Options::getRenderContextDirective
     */
    public function testGetRenderContextDirective() {
        $expected = false;
        $options = new Options();
        $this->injectProperty($options, 'renderContextDirective', $expected);
        $result = $options->getRenderContextDirective();
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the setRenderDirectives() method.
     * @covers \ManiaScript\Builder\Options::setRenderDirectives
     */
    public function testSetRenderDirectives() {
        $expected = false;
        $options = new Options();
        $result = $options->setRenderDirectives($expected);
        $this->assertEquals($options, $result);
        $this->assertPropertyEquals($expected, $options, 'renderDirectives');
    }

    /**
     * Tests the getRenderDirectives() method.
     * @covers \ManiaScript\Builder\Options::getRenderDirectives
     */
    public function testGetRenderDirectives() {
        $expected = false;
        $options = new Options();
        $this->injectProperty($options, 'renderDirectives', $expected);
        $result = $options->getRenderDirectives();
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the setRenderGlobalCode() method.
     * @covers \ManiaScript\Builder\Options::setRenderGlobalCode
     */
    public function testSetRenderGlobalCode() {
        $expected = false;
        $options = new Options();
        $result = $options->setRenderGlobalCode($expected);
        $this->assertEquals($options, $result);
        $this->assertPropertyEquals($expected, $options, 'renderGlobalCode');
    }

    /**
     * Tests the getRenderGlobalCode() method.
     * @covers \ManiaScript\Builder\Options::getRenderGlobalCode
     */
    public function testGetRenderGlobalCode() {
        $expected = false;
        $options = new Options();
        $this->injectProperty($options, 'renderGlobalCode', $expected);
        $result = $options->getRenderGlobalCode();
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the setRenderMainFunction() method.
     * @covers \ManiaScript\Builder\Options::setRenderMainFunction
     */
    public function testSetRenderMainFunction() {
        $expected = false;
        $options = new Options();
        $result = $options->setRenderMainFunction($expected);
        $this->assertEquals($options, $result);
        $this->assertPropertyEquals($expected, $options, 'renderMainFunction');
    }

    /**
     * Tests the getRenderMainFunction() method.
     * @covers \ManiaScript\Builder\Options::getRenderMainFunction
     */
    public function testGetRenderMainFunction() {
        $expected = false;
        $options = new Options();
        $this->injectProperty($options, 'renderMainFunction', $expected);
        $result = $options->getRenderMainFunction();
        $this->assertEquals($expected, $result);
    }
}
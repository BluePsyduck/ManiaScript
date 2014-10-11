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
}
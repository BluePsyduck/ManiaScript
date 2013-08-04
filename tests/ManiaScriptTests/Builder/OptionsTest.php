<?php

namespace ManiaScriptTests\Builder;

use ManiaScript\Builder\Options;
use ManiaScriptTests\Assets\GetterSetterTestCase;

/**
 * The PHPUnit test of the Options class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class OptionsTest extends GetterSetterTestCase {
    /**
     * Tests property initialization on class construction.
     */
    public function testConstruct() {
        $options = new Options();
        $this->assertPropertyEquals(false, $options, 'compress');
        $this->assertPropertyEquals(false, $options, 'includeScriptTag');
    }

    /**
     * Tests the setCompress() method.
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
     */
    public function testGetCompress() {
        $expected = true;
        $options = new Options();
        $this->injectProperty($options, 'compress', $expected);
        $this->assertEquals($expected, $options->getCompress());
    }

    /**
     * Tests the setIncludeScriptTag() method.
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
     */
    public function testGetIncludeScriptTag() {
        $expected = true;
        $options = new Options();
        $this->injectProperty($options, 'includeScriptTag', $expected);
        $this->assertEquals($expected, $options->getIncludeScriptTag());
    }
}
<?php

namespace ManiaScriptTests\Builder;

use ManiaScript\Builder\Code;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test for the Code class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class CodeTest extends TestCase {
    /**
     * Tests the setCode() method.
     */
    public function testSetCode() {
        $expected = 'abc';
        $code = new Code();
        $result = $code->setCode($expected);
        $this->assertPropertyEquals($expected, $code, 'code');
        $this->assertEquals($code, $result);
    }

    /**
     * Tests the getCode() method.
     */
    public function testGetCode() {
        $expected = 'abc';
        $code = new Code();
        $this->injectProperty($code, 'code', $expected);
        $this->assertEquals($expected, $code->getCode());
    }

    /**
     * Provides the data for the setPriority() test.
     * @return array The data.
     */
    public function provideSetPriority() {
        return array(
            array(42, 42),
            array(0, -42),
            array(0, 'abc')
        );
    }

    /**
     * Tests the setPriority() method.
     * @param int $expected The expected priority.
     * @param mixed $priority The priority to be set.
     * @dataProvider provideSetPriority
     */
    public function testSetPriority($expected, $priority) {
        $code = new Code();
        $result = $code->setPriority($priority);
        $this->assertPropertyEquals($expected, $code, 'priority');
        $this->assertEquals($code, $result);
    }

    /**
     * Tests the getPriority() method.
     */
    public function testGetPriority() {
        $expected = 42;
        $code = new Code();
        $this->injectProperty($code, 'priority', $expected);
        $this->assertEquals($expected, $code->getPriority());
    }
}

<?php

namespace ManiaScriptTests\Event;

use ManiaScript\AbstractEvent;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the AbstractEvent class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class EventTest extends TestCase {
    /**
     * Tests the setCode() method.
     */
    public function testSetCode() {
        $expected = 'abc';
        $event = $this->getMockForAbstractClass('ManiaScript\Builder\Event\AbstractEvent');
        $result = $event->setCode($expected);
        $this->assertPropertyEquals($expected, $event, 'code');
        $this->assertEquals($event, $result);
    }

    /**
     * Tests the getCode() method.
     */
    public function testGetCode() {
        $expected = 'abc';
        $event = $this->getMockForAbstractClass('ManiaScript\Builder\Event\AbstractEvent');
        $this->injectProperty($event, 'code', $expected);
        $this->assertEquals($expected, $event->getCode());
    }

    /**
     * Data provider for the setPriority test.
     * @return array The test data.
     */
    public function providerSetCode() {
        return array(
            array(42, 42),
            array(0, -42),
            array(0, 'abc')
        );
    }

    /**
     * Tests the setCode() method.
     * @param int $expected The expected priority.
     * @param mixed $priority The priority to be set.
     * @dataProvider providerSetCode
     */
    public function testSetPriority($expected, $priority) {
        $event = $this->getMockForAbstractClass('ManiaScript\Builder\Event\AbstractEvent');
        $result = $event->setPriority($priority);
        $this->assertPropertyEquals($expected, $event, 'priority');
        $this->assertEquals($event, $result);
    }

    /**
     * Tests the getPriority() method.
     */
    public function testGetPriority() {
        $expected = 42;
        $event = $this->getMockForAbstractClass('ManiaScript\Builder\Event\AbstractEvent');
        $this->injectProperty($event, 'priority', $expected);
        $this->assertEquals($expected, $event->getPriority());
    }

    /**
     * Tests the setInline() method.
     */
    public function testSetInline() {
        $expected = true;
        $event = $this->getMockForAbstractClass('ManiaScript\Builder\Event\AbstractEvent');
        $result = $event->setInline($expected);
        $this->assertPropertyEquals($expected, $event, 'inline');
        $this->assertEquals($event, $result);
    }

    /**
     * Tests the getInline() method.
     */
    public function testGetInline() {
        $expected = true;
        $event = $this->getMockForAbstractClass('ManiaScript\Builder\Event\AbstractEvent');
        $this->injectProperty($event, 'inline', $expected);
        $this->assertEquals($expected, $event->getInline());
    }
}
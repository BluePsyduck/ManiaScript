<?php

namespace ManiaScriptTests\Builder\Event;

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
     * @covers \ManiaScript\Builder\Event\AbstractEvent::setCode
     */
    public function testSetCode() {
        $expected = 'abc';
        /* @var $event \ManiaScript\Builder\Event\AbstractEvent */
        $event = $this->getMockBuilder('ManiaScript\Builder\Event\AbstractEvent')
                      ->getMockForAbstractClass();
        $result = $event->setCode($expected);
        $this->assertPropertyEquals($expected, $event, 'code');
        $this->assertEquals($event, $result);
    }

    /**
     * Tests the getCode() method.
     * @covers \ManiaScript\Builder\Event\AbstractEvent::getCode
     */
    public function testGetCode() {
        $expected = 'abc';
        /* @var $event \ManiaScript\Builder\Event\AbstractEvent */
        $event = $this->getMockBuilder('ManiaScript\Builder\Event\AbstractEvent')
                      ->getMockForAbstractClass();
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
     * Tests the setPriority() method.
     * @param int $expected The expected priority.
     * @param mixed $priority The priority to be set.
     * @covers \ManiaScript\Builder\Event\AbstractEvent::setPriority
     * @dataProvider providerSetCode
     */
    public function testSetPriority($expected, $priority) {
        /* @var $event \ManiaScript\Builder\Event\AbstractEvent */
        $event = $this->getMockBuilder('ManiaScript\Builder\Event\AbstractEvent')
                      ->getMockForAbstractClass();
        $result = $event->setPriority($priority);
        $this->assertPropertyEquals($expected, $event, 'priority');
        $this->assertEquals($event, $result);
    }

    /**
     * Tests the getPriority() method.
     * @covers \ManiaScript\Builder\Event\AbstractEvent::getPriority
     */
    public function testGetPriority() {
        $expected = 42;
        /* @var $event \ManiaScript\Builder\Event\AbstractEvent */
        $event = $this->getMockBuilder('ManiaScript\Builder\Event\AbstractEvent')
                      ->getMockForAbstractClass();
        $this->injectProperty($event, 'priority', $expected);
        $this->assertEquals($expected, $event->getPriority());
    }

    /**
     * Tests the setInline() method.
     * @covers \ManiaScript\Builder\Event\AbstractEvent::setInline
     */
    public function testSetInline() {
        $expected = true;
        /* @var $event \ManiaScript\Builder\Event\AbstractEvent */
        $event = $this->getMockBuilder('ManiaScript\Builder\Event\AbstractEvent')
                      ->getMockForAbstractClass();
        $result = $event->setInline($expected);
        $this->assertPropertyEquals($expected, $event, 'inline');
        $this->assertEquals($event, $result);
    }

    /**
     * Tests the getInline() method.
     * @covers \ManiaScript\Builder\Event\AbstractEvent::getInline
     */
    public function testGetInline() {
        $expected = true;
        /* @var $event \ManiaScript\Builder\Event\AbstractEvent */
        $event = $this->getMockBuilder('ManiaScript\Builder\Event\AbstractEvent')
                      ->getMockForAbstractClass();
        $this->injectProperty($event, 'inline', $expected);
        $this->assertEquals($expected, $event->getInline());
    }
}
<?php

namespace ManiaScriptTests\Event\Handler;

use ManiaScriptTests\Assets\Event;
use PHPUnit_Framework_TestCase;
use ReflectionMethod;

/**
 * The PHPUnit test for the generic control handler.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class ControlHandlerTest extends PHPUnit_Framework_TestCase {
    /**
     * Tests the getEventType() method.
     */
    public function testGetEventType() {
        /* @var $handler \ManiaScript\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Event\Handler\ControlHandler', array(), 'Mocked');

        $reflectedMethod = new ReflectionMethod($handler, 'getEventType');
        $reflectedMethod->setAccessible(true);
        $result = $reflectedMethod->invoke($handler);
        $this->assertEquals('Mocked', $result);
    }

    /**
     * Provides the data for the buildCondition() test.
     * @return array The data.
     */
    public function providerBuildCondition() {
        return array(
            array(
                '',
                array()
            ),
            array(
                'Event.ControlId == "abc"',
                array('abc')
            ),
            array(
                'Event.ControlId == "abc" || Event.ControlId == "def"',
                array('abc', 'def')
            )
        );
    }

    /**
     * Tests the buildCondition() method.
     * @param string $expected The expected condition.
     * @param array $controlIds The Control IDs to be used.
     * @dataProvider providerBuildCondition
     */
    public function testBuildCondition($expected, $controlIds) {
        /* @var $handler \ManiaScript\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Event\Handler\ControlHandler');

        $event = new Event();
        $event->setControlIds($controlIds);

        $reflectedMethod = new ReflectionMethod($handler, 'buildCondition');
        $reflectedMethod->setAccessible(true);
        $result = $reflectedMethod->invoke($handler, $event);
        $this->assertEquals($expected, $result);
    }
}

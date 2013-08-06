<?php

namespace ManiaScriptTests\Event\Handler;

use ManiaScript\Builder\PriorityQueue;
use ManiaScript\Event\MouseClick;
use ManiaScriptTests\Assets\Event;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test for the generic control handler.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class ControlHandlerTest extends TestCase {
    /**
     * Tests the getEventType() method.
     */
    public function testGetEventType() {
        /* @var $handler \ManiaScript\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Event\Handler\ControlHandler', array(), 'Mocked');
        $result = $this->invokeMethod($handler, 'getEventType');
        $this->assertEquals('Mocked', $result);
    }

    /**
     * Provides the data for the buildCode() test.
     * @return array The data.
     */
    public function provideBuildCode() {
        $queue = new PriorityQueue();
        $queue->add(new Event())
              ->add(new Event());
        return array(
            array(
                '',
                '',
                '',
                new PriorityQueue()
            ),
            array(
                'CMlEvent::Type::Mock',
                'abcabc',
                'defdef',
                $queue
            )
        );
    }



    /**
     * Tests the buildCode() method.
     * @param string $expectType The type to be expected in the inline code.
     * @param string $expectInline Part of the inline code to be expected.
     * @param string $expectGlobal Part of the global code to be expected.
     * @param \ManiaScript\Builder\PriorityQueue $queue The priority queue.
     * @dataProvider provideBuildCode
     */
    public function testBuildCode($expectType, $expectInline, $expectGlobal, $queue) {
        /* @var $handler \ManiaScript\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass(
            'ManiaScript\Event\Handler\ControlHandler',
            array(),
            '',
            true,
            true,
            true,
            array('getEventType', 'buildGlobalCodeOfEvent', 'buildInlineCodeOfEvent')
        );
        $handler->expects($this->any())
                ->method('getEventType')
                ->will($this->returnValue('Mock'));
        $handler->expects($this->any())
                ->method('buildInlineCodeOfEvent')
                ->will($this->returnValue('abc'));
        $handler->expects($this->any())
                ->method('buildGlobalCodeOfEvent')
                ->will($this->returnValue('def'));

        $this->injectProperty($handler, 'events', $queue);

        $handler->buildCode();

        $globalCode = $this->extractProperty($handler, 'globalCode');
        $inlineCode = $this->extractProperty($handler, 'inlineCode');

        if (!empty($expectType)) {
            $this->assertContains($expectType, $inlineCode);
        }
        if (!empty($expectInline)) {
            $this->assertContains($expectInline, $inlineCode);
        } else {
            $this->assertEquals('', $inlineCode);
        }
        if (!empty($globalCode)) {
            $this->assertContains($expectGlobal, $globalCode);
        } else {
            $this->assertEquals('', $globalCode);
        }
    }

    /**
     * Provides the data for the buildGlobalCodeOfEvent() test.
     * @return array The data.
     */
    public function provideBuildGlobalCodeOfEvent() {
        $event1 = new Event();
        $event2 = new Event();
        $event2->setInline(true);

        return array(
            array('abc', $event1, 'abc'),
            array('', $event2, 'def')
        );
    }

    /**
     * Tests the buildGlobalCodeOfEvent() method.
     * @param string $expected The expected code.
     * @param \ManiaScript\Event\AbstractEvent The event to be used.
     * @params tring The code to be returned by buildHandlerFunction().
     * @dataProvider provideBuildGlobalCodeOfEvent
     */
    public function testBuildGlobalCodeOfEvent($expected, $event, $code) {
        /* @var $handler \ManiaScript\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass(
            'ManiaScript\Event\Handler\ControlHandler',
            array(),
            '',
            true,
            true,
            true,
            array('buildHandlerFunction')
        );
        $handler->expects($this->any())
                ->method('buildHandlerFunction')
                ->with($event)
                ->will($this->returnValue($code));
        $result = $this->invokeMethod($handler, 'buildGlobalCodeOfEvent', array($event));
        $this->assertEquals($expected, $result);
    }

    /**
     * Provides the data for the buildInlineCodeOfEvent() test.
     * @return array The data.
     */
    public function provideBuildInlineCodeOfEvent() {
        $event1 = new MouseClick();
        $event1->setCode('abc')
               ->setInline(true);
        $event2 = new MouseClick();
        return array(
            array('abc', $event1, null, 'def'),
            array('abc', $event2, 'abc', '')
        );
    }

    /**
     * Tests the buildInlineCodeOfEvent() method.
     * @param string $expected The expected code.
     * @param \ManiaScript\Event\AbstractEvent $event The event to be used.
     * @param string $functionCall The result of the build function call.
     * @param string $condition The condition.
     * @dataProvider provideBuildInlineCodeOfEvent
     */
    public function testBuildInlineCodeOfEvent($expected, $event, $functionCall, $condition) {
        /* @var $handler \ManiaScript\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass(
            'ManiaScript\Event\Handler\ControlHandler',
            array(),
            '',
            true,
            true,
            true,
            array('buildCondition', 'buildHandlerFunctionCall')
        );
        $handler->expects($this->once())
                ->method('buildCondition')
                ->with($event)
                ->will($this->returnValue($condition));
        if (is_null($functionCall)) {
            $handler->expects($this->never())
                    ->method('buildHandlerFunctionCall');
        } else {
            $handler->expects($this->once())
                    ->method('buildHandlerFunctionCall')
                    ->with($event)
                    ->will($this->returnValue($functionCall));
        }
        $result = $this->invokeMethod($handler, 'buildInlineCodeOfEvent', array($event));
        if (!empty($condition)) {
            $this->assertContains($condition, $result);
        }
        $this->assertContains($expected, $result);
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
        $event = new Event();
        $event->setControlIds($controlIds);

        /* @var $handler \ManiaScript\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Event\Handler\ControlHandler');
        $result = $this->invokeMethod($handler, 'buildCondition', array($event));
        $this->assertEquals($expected, $result);
    }
}

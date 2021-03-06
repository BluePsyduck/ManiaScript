<?php

namespace ManiaScriptTests\Builder\Event\Handler;

use ManiaScript\Builder;
use ManiaScript\Builder\PriorityQueue;
use ManiaScript\Builder\Event\MouseClick;
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
     * @covers \ManiaScript\Builder\Event\Handler\ControlHandler::getEventType
     */
    public function testGetEventType() {
        /* @var $handler \ManiaScript\Builder\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\ControlHandler')
                        ->setConstructorArgs(array(new Builder()))
                        ->setMockClassName('Mocked')
                        ->getMockForAbstractClass();
        $result = $this->invokeMethod($handler, 'getEventType');
        $this->assertEquals('Mocked', $result);
    }

    /**
     * Provides the data for the buildInlineCode() test.
     * @return array The data.
     */
    public function provideBuildInlineCode() {
        $queue = new PriorityQueue();
        $queue->add(new Event())
              ->add(new Event());
        return array(
            array(
                '',
                '',
                new PriorityQueue()
            ),
            array(
                'CMlEvent::Type::Mock',
                'abcabc',
                $queue
            )
        );
    }

    /**
     * Tests the buildInlineCode() method.
     * @param string $expectType The type to be expected in the inline code.
     * @param string $expectInline Part of the inline code to be expected.
     * @param \ManiaScript\Builder\PriorityQueue $queue The priority queue.
     * @covers \ManiaScript\Builder\Event\Handler\ControlHandler::buildInlineCode
     * @dataProvider provideBuildInlineCode
     */
    public function testBuildInlineCode($expectType, $expectInline, $queue) {
        /* @var $handler \ManiaScript\Builder\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\ControlHandler')
                        ->setMethods(array('getEventType', 'buildInlineCodeOfEvent'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMockForAbstractClass();
        $handler->expects($this->any())
                ->method('getEventType')
                ->will($this->returnValue('Mock'));
        $handler->expects($this->any())
                ->method('buildInlineCodeOfEvent')
                ->will($this->returnValue('abc'));

        $this->injectProperty($handler, 'events', $queue);

        $result = $this->invokeMethod($handler, 'buildInlineCode');
        if (!empty($expectType)) {
            $this->assertContains($expectType, $result);
        }
        if (!empty($expectInline)) {
            $this->assertContains($expectInline, $result);
        } else {
            $this->assertEquals('', $result);
        }
    }

    /**
     * Tests the buildGlobalCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\ControlHandler::buildGlobalCode
     */
    public function testBuildGlobalCode() {
        $event1 = new Event();
        $event1->setCode('abc');
        $event2 = new Event();
        $event2->setCode('def');

        $queue = new PriorityQueue();
        $queue->add($event1)
              ->add($event2);

        /* @var $handler \ManiaScript\Builder\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\ControlHandler')
                        ->setMethods(array('buildGlobalCodeOfEvent'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMockForAbstractClass();
        $handler->expects($this->at(0))
                ->method('buildGlobalCodeOfEvent')
                ->with($event1)
                ->will($this->returnValue('ghi'));
        $handler->expects($this->at(1))
                ->method('buildGlobalCodeOfEvent')
                ->with($event2)
                ->will($this->returnValue('jkl'));
        $this->injectProperty($handler, 'events', $queue);

        $result = $this->invokeMethod($handler, 'buildGlobalCode');
        $this->assertEquals('ghijkl', $result);
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
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event to be used.
     * @param string $code The code to be returned by buildHandlerFunction().
     * @covers \ManiaScript\Builder\Event\Handler\ControlHandler::buildGlobalCodeOfEvent
     * @dataProvider provideBuildGlobalCodeOfEvent
     */
    public function testBuildGlobalCodeOfEvent($expected, $event, $code) {
        /* @var $handler \ManiaScript\Builder\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\ControlHandler')
                        ->setMethods(array('buildHandlerFunction'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMockForAbstractClass();
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
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event to be used.
     * @param string $functionCall The result of the build function call.
     * @param string $condition The condition.
     * @covers \ManiaScript\Builder\Event\Handler\ControlHandler::buildInlineCodeOfEvent
     * @dataProvider provideBuildInlineCodeOfEvent
     */
    public function testBuildInlineCodeOfEvent($expected, $event, $functionCall, $condition) {
        /* @var $handler \ManiaScript\Builder\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\ControlHandler')
                        ->setMethods(array('buildCondition', 'buildHandlerFunctionCall'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMockForAbstractClass();
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
            ),
            array(
                'Event.Control.HasClass("abc")',
                array('.abc')
            ),
            array(
                'Event.Control.HasClass("abc") || Event.Control.HasClass("def")',
                array('.abc', '.def')
            ),
            array(
                'Event.ControlId == "abc" || Event.Control.HasClass("def")',
                array('abc', '.def')
            ),
        );
    }

    /**
     * Tests the buildCondition() method.
     * @param string $expected The expected condition.
     * @param array $controlIds The Control IDs to be used.
     * @covers \ManiaScript\Builder\Event\Handler\ControlHandler::buildCondition
     * @dataProvider providerBuildCondition
     */
    public function testBuildCondition($expected, $controlIds) {
        $event = new Event();
        $event->setControlIds($controlIds);

        /* @var $handler \ManiaScript\Builder\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\ControlHandler')
                        ->setConstructorArgs(array(new Builder()))
                        ->getMockForAbstractClass();
        $result = $this->invokeMethod($handler, 'buildCondition', array($event));
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the buildHandlerFunction() method.
     * @covers \ManiaScript\Builder\Event\Handler\ControlHandler::buildHandlerFunction
     */
    public function testBuildHandlerFunction() {
        $event = new Event();
        $event->setCode('abc');

        /* @var $handler \ManiaScript\Builder\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\ControlHandler')
                        ->setMethods(array('getHandlerFunctionName'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMockForAbstractClass();
        $handler->expects($this->once())
                ->method('getHandlerFunctionName')
                ->with($event)
                ->will($this->returnValue('def'));

        $result = $this->invokeMethod($handler, 'buildHandlerFunction', array($event));
        $this->assertContains('Void def(CMlEvent Event)', $result);
        $this->assertContains('abc', $result);
    }

    /**
     * Tests the buildHandlerFunctionCall() method.
     * @covers \ManiaScript\Builder\Event\Handler\ControlHandler::buildHandlerFunctionCall
     */
    public function testBuildHandlerFunctionCall() {
        $event = new Event();
        /* @var $handler \ManiaScript\Builder\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\ControlHandler')
                        ->setMethods(array('getHandlerFunctionName'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMockForAbstractClass();
        $handler->expects($this->once())
                ->method('getHandlerFunctionName')
                ->with($event)
                ->will($this->returnValue('abc'));
        $result = $this->invokeMethod($handler, 'buildHandlerFunctionCall', array($event));
        $this->assertEquals('abc(Event);' . PHP_EOL, $result);
    }
}

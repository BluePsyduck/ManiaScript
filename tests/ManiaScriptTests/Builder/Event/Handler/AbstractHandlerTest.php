<?php

namespace ManiaScriptTests\Builder\Event\Handler;

use ManiaScript\Builder;
use ManiaScriptTests\Assets\Event;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test for the abstract event handler.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class AbstractHandlerTest extends TestCase {
    /**
     * Tests the constructor.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::__construct
     */
    public function testConstruct() {
        $builder = new Builder();

        /* @var $handler \ManiaScript\Builder\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\AbstractHandler')
                        ->setConstructorArgs(array($builder))
                        ->getMockForAbstractClass();

        $this->assertPropertyEquals($builder, $handler, 'builder');
        $this->assertPropertyInstanceOf('ManiaScript\Builder\PriorityQueue', $handler, 'events');
    }

    /**
     * Tests the addEvent() method.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::addEvent
     */
    public function testAddEvent() {
        $expected = new Event();

        /* @var $handler \ManiaScript\Builder\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Builder\Event\Handler\AbstractHandler');

        /* @var $queue \ManiaScript\Builder\PriorityQueue|\PHPUnit_Framework_MockObject_MockObject */
        $queue = $this->getMock('ManiaScript\Builder\PriorityQueue', array('add'));
        $queue->expects($this->once())
              ->method('add')
              ->with($expected);
        $this->injectProperty($handler, 'events', $queue);

        $handler->addEvent($expected);
    }

    /**
     * Tests the prepare() method.
     * @covers Class::prepare
     */
    public function testPrepare() {
        $this->markTestIncomplete('Test for prepare() not implemented.');
    }

    /**
     * Tests the getGlobalCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::getGlobalCode
     */
    public function testGetGlobalCode() {
        $expected = 'abc';

        /* @var $handler \ManiaScript\Builder\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Builder\Event\Handler\AbstractHandler');
        $this->injectProperty($handler, 'globalCode', $expected);
        $result = $handler->getGlobalCode();
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the getInlineCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::getInlineCode
     */
    public function testGetInlineCode() {
        $expected = 'abc';

        /* @var $handler \ManiaScript\Builder\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Builder\Event\Handler\AbstractHandler');
        $this->injectProperty($handler, 'inlineCode', $expected);
        $result = $handler->getInlineCode();
        $this->assertEquals($expected, $result);
    }

    /**
     * Provides the data for the getHandlerFunctionName() method.
     * @return array The data.
     */
    public function providerGetHandlerFunctionName() {
        $event1 = new Event();
        $event1->setCode('abc');
        $event2 = new Event();
        $event2->setCode('def');

        return array(
            array( // No events known
                '__HandleEvent0',
                array('__HandleEvent0' => $event1),
                array(),
                $event1
            ),
            array( // Events known, get name for new one
                '__HandleEvent1',
                array('__HandleEvent0' => $event1, '__HandleEvent1' => $event2),
                array('__HandleEvent0' => $event1),
                $event2
            ),
            array( // Get name of already known event
                '__HandleEvent0',
                array('__HandleEvent0' => $event1, '__HandleEvent1' => $event2),
                array('__HandleEvent0' => $event1, '__HandleEvent1' => $event2),
                $event1
            )
        );
    }

    /**
     * Tests the getHandlerFunctionName() method.
     * @param string $expectedName The expected function name.
     * @param array $expectedArray The array to be expected in the handler.
     * @param array $array The array to be set into the handler.
     * @param \ManiaScript\Builder\Event\AbstractEvent $event The event to be used.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::getHandlerFunctionName
     * @dataProvider providerGetHandlerFunctionName
     */
    public function testGetHandlerFunctionName($expectedName, $expectedArray, $array, $event) {
        /* @var $handler \ManiaScript\Builder\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Builder\Event\Handler\AbstractHandler');

        $this->injectProperty($handler, 'handlerFunctionNames', $array);
        $result = $this->invokeMethod($handler, 'getHandlerFunctionName', array($event));

        $this->assertPropertyEquals($expectedArray, $handler, 'handlerFunctionNames');
        $this->assertEquals($expectedName, $result);
    }

    /**
     * Tests the buildHandlerFunction() method.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::buildHandlerFunction
     */
    public function testBuildHandlerFunction() {
        $event = new Event();
        $event->setCode('abc');

        /* @var $handler \ManiaScript\Builder\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass(
            'ManiaScript\Builder\Event\Handler\AbstractHandler',
            array(),
            '',
            true,
            true,
            true,
            array('getHandlerFunctionName')
        );
        $handler->expects($this->once())
            ->method('getHandlerFunctionName')
            ->with($event)
            ->will($this->returnValue('def'));

        $result = $this->invokeMethod($handler, 'buildHandlerFunction', array($event));
        $this->assertContains('Void def()', $result);
        $this->assertContains('abc', $result);
    }

    /**
     * Tests the buildHandlerFunctionCall() method.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::buildHandlerFunctionCall
     */
    public function testBuildHandlerFunctionCall() {
        $event = new Event();
        /* @var $handler \ManiaScript\Builder\Event\Handler\ControlHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass(
            'ManiaScript\Builder\Event\Handler\AbstractHandler',
            array(),
            '',
            true,
            true,
            true,
            array('getHandlerFunctionName')
        );
        $handler->expects($this->once())
                ->method('getHandlerFunctionName')
                ->with($event)
                ->will($this->returnValue('abc'));
        $result = $this->invokeMethod($handler, 'buildHandlerFunctionCall', array($event));
        $this->assertEquals('abc();' . PHP_EOL, $result);
    }
}
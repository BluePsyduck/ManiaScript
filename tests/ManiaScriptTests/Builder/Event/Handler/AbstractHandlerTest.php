<?php

namespace ManiaScriptTests\Builder\Event\Handler;

use ManiaScript\Builder;
use ManiaScript\Builder\Code;
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
     * The builder instance used in the mocked handler.
     * @var \ManiaScript\Builder
     */
    protected $builder;

    /**
     * The mocked abstract handler.
     * @var \ManiaScript\Builder\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockedHandler;

    /**
     * Sets up the tests case.
     */
    protected function setUp() {
        $this->builder = new Builder();
        $this->builder->getOptions()->setFunctionPrefix('foo');
        $this->mockedHandler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\AbstractHandler')
                                    ->setConstructorArgs(array($this->builder))
                                    ->getMockForAbstractClass();
    }

    /**
     * Tears down the tests case.
     */
    protected function tearDown() {
        $this->mockedHandler = null;
        $this->builder = null;
    }

    /**
     * Tests the constructor.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::__construct
     */
    public function testConstruct() {
        $this->assertPropertyEquals($this->builder, $this->mockedHandler, 'builder');
        $this->assertPropertyInstanceOf('ManiaScript\Builder\PriorityQueue', $this->mockedHandler, 'events');
    }

    /**
     * Tests the addEvent() method.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::addEvent
     */
    public function testAddEvent() {
        $expected = new Event();

        /* @var $queue \ManiaScript\Builder\PriorityQueue|\PHPUnit_Framework_MockObject_MockObject */
        $queue = $this->getMockBuilder('ManiaScript\Builder\PriorityQueue')
                      ->setMethods(array('add'))
                      ->getMock();
        $queue->expects($this->once())
              ->method('add')
              ->with($expected);
        $this->injectProperty($this->mockedHandler, 'events', $queue);

        $result = $this->mockedHandler->addEvent($expected);
        $this->assertEquals($this->mockedHandler, $result);
    }

    /**
     * Tests the prepare() method.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::prepare
     */
    public function testPrepare() {
        $inlineCode = 'abc';
        $globalCode = 'def';

        /* @var $handler \ManiaScript\Builder\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\AbstractHandler')
                        ->setMethods(array('buildInlineCode', 'buildGlobalCode', 'addGlobalCode'))
                        ->setConstructorArgs(array($this->builder))
                        ->getMockForAbstractClass();
        $handler->expects($this->once())
                ->method('buildInlineCode')
                ->will($this->returnValue($inlineCode));
        $handler->expects($this->once())
                ->method('buildGlobalCode')
                ->will($this->returnValue($globalCode));
        $handler->expects($this->once())
                ->method('addGlobalCode')
                ->with($globalCode, PHP_INT_MAX)
                ->will($this->returnSelf());

        $handler->prepare();
        $this->assertPropertyEquals($inlineCode, $handler, 'inlineCode');
    }

    /**
     * Tests the buildInlineCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::buildInlineCode
     */
    public function testBuildInlineCode() {
        $result = $this->invokeMethod($this->mockedHandler, 'buildInlineCode');
        $this->assertEquals('', $result);
    }

    /**
     * Tests the buildGlobalCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::buildGlobalCode
     */
    public function testBuildGlobalCode() {
        $result = $this->invokeMethod($this->mockedHandler, 'buildGlobalCode');
        $this->assertEquals('', $result);
    }

    /**
     * Provides the data for the addGlobalCode() test.
     * @return array The data.
     */
    public function provideAddGlobalCode() {
        $code = new Code();
        $code->setCode('abc')
             ->setPriority(42);

        return array(
            array(null, '', 1337),
            array($code, 'abc', 42)
        );
    }

    /**
     * Tests the addGlobalCode() method.
     * @param \ManiaScript\Builder\Code|null $expectCode The code to expect in the addGlobalCode() method call.
     * @param string $globalCode The global code to use.
     * @param int $priority The priority to use.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::addGlobalCode
     * @dataProvider provideAddGlobalCode
     */
    public function testAddGlobalCode($expectCode, $globalCode, $priority) {
        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMockBuilder('ManiaScript\Builder')
                        ->setMethods(array('addGlobalCode'))
                        ->getMock();
        if (is_null($expectCode)) {
            $builder->expects($this->never())
                    ->method('addGlobalCode');
        } else {
            $builder->expects($this->once())
                    ->method('addGlobalCode')
                    ->with($expectCode)
                    ->will($this->returnSelf());
        }

        /* @var $handler \ManiaScript\Builder\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\AbstractHandler')
                        ->setConstructorArgs(array($builder))
                        ->getMockForAbstractClass();

        $result = $this->invokeMethod($handler, 'addGlobalCode', array($globalCode, $priority));
        $this->assertEquals($handler, $result);
    }

    /**
     * Tests the getInlineCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\AbstractHandler::getInlineCode
     */
    public function testGetInlineCode() {
        $expected = 'abc';
        $this->injectProperty($this->mockedHandler, 'inlineCode', $expected);
        $result = $this->mockedHandler->getInlineCode();
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
                'foo_HandleEvent0',
                array('foo_HandleEvent0' => $event1),
                array(),
                $event1
            ),
            array( // Events known, get name for new one
                'foo_HandleEvent1',
                array('foo_HandleEvent0' => $event1, 'foo_HandleEvent1' => $event2),
                array('foo_HandleEvent0' => $event1),
                $event2
            ),
            array( // Get name of already known event
                'foo_HandleEvent0',
                array('foo_HandleEvent0' => $event1, 'foo_HandleEvent1' => $event2),
                array('foo_HandleEvent0' => $event1, 'foo_HandleEvent1' => $event2),
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
        $this->injectProperty($this->mockedHandler, 'handlerFunctionNames', $array);
        $result = $this->invokeMethod($this->mockedHandler, 'getHandlerFunctionName', array($event));

        $this->assertPropertyEquals($expectedArray, $this->mockedHandler, 'handlerFunctionNames');
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
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\AbstractHandler')
                        ->setMethods(array('getHandlerFunctionName'))
                        ->setConstructorArgs(array($this->builder))
                        ->getMockForAbstractClass();
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
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\AbstractHandler')
                        ->setMethods(array('getHandlerFunctionName'))
                        ->setConstructorArgs(array($this->builder))
                        ->getMockForAbstractClass();

        $handler->expects($this->once())
                ->method('getHandlerFunctionName')
                ->with($event)
                ->will($this->returnValue('abc'));
        $result = $this->invokeMethod($handler, 'buildHandlerFunctionCall', array($event));
        $this->assertEquals('abc();' . PHP_EOL, $result);
    }
}
<?php

namespace ManiaScriptTests\Event\Handler;

use ManiaScriptTests\Assets\Event;
use ManiaScriptTests\Assets\GetterSetterTestCase;
use ReflectionMethod;
use ReflectionProperty;

/**
 * The PHPUnit test for the abstract event handler.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class AbstractHandlerTest extends GetterSetterTestCase {
    /**
     * Tests the constructor.
     */
    public function testConstruct() {
        /* @var $handler \ManiaScript\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Event\Handler\AbstractHandler');

        $reflectedProperty = new ReflectionProperty($handler, 'events');
        $reflectedProperty->setAccessible(true);
        $result = $reflectedProperty->getValue($handler);
        $this->assertInstanceOf('ManiaScript\Builder\PriorityQueue', $result);
    }

    /**
     * Tests the addEvent() method.
     */
    public function testAddEvent() {
        $expected = new Event();

        /* @var $handler \ManiaScript\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Event\Handler\AbstractHandler');

        /* @var $queue \ManiaScript\Builder\PriorityQueue|\PHPUnit_Framework_MockObject_MockObject */
        $queue = $this->getMock('ManiaScript\Builder\PriorityQueue', array('add'));
        $queue->expects($this->once())
              ->method('add')
              ->with($expected);
        $this->injectProperty($handler, 'events', $queue);

        $handler->addEvent($expected);
    }

    /**
     * Tests the getGlobalCode() method.
     */
    public function testGetGlobalCode() {
        $expected = 'abc';

        /* @var $handler \ManiaScript\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Event\Handler\AbstractHandler');
        $this->injectProperty($handler, 'globalCode', $expected);
        $result = $handler->getGlobalCode();
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the getInlineCode() method.
     */
    public function testGetInlineCode() {
        $expected = 'abc';

        /* @var $handler \ManiaScript\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Event\Handler\AbstractHandler');
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
     * @param \ManiaScript\Event\AbstractEvent $event The event to be used.
     * @dataProvider providerGetHandlerFunctionName
     */
    public function testGetHandlerFunctionName($expectedName, $expectedArray, $array, $event) {
        /* @var $handler \ManiaScript\Event\Handler\AbstractHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockForAbstractClass('ManiaScript\Event\Handler\AbstractHandler');

        $this->injectProperty($handler, 'handlerFunctionNames', $array);

        $reflectedMethod = new ReflectionMethod($handler, 'getHandlerFunctionName');
        $reflectedMethod->setAccessible(true);
        $result = $reflectedMethod->invoke($handler, $event);

        $this->assertPropertyEquals($expectedArray, $handler, 'handlerFunctionNames');
        $this->assertEquals($expectedName, $result);
    }
}

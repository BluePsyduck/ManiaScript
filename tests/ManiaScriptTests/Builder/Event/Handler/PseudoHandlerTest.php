<?php

namespace ManiaScriptTests\Builder\Event\Handler;

use ManiaScript\Builder;
use ManiaScript\Builder\PriorityQueue;
use ManiaScriptTests\Assets\Event;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the pseudo event handler.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class PseudoHandlerTest extends TestCase {

    /**
     * Tests the buildInlineCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\PseudoHandler::buildInlineCode
     */
    public function testBuildInlineCode() {
        $event1 = new Event();
        $event1->setCode('abc');
        $event2 = new Event();
        $event2->setCode('def')
               ->setInline(true);

        $queue = new PriorityQueue();
        $queue->add($event1)
              ->add($event2);

        /* @var $handler \ManiaScript\Builder\Event\Handler\PseudoHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\PseudoHandler')
                        ->setMethods(array('buildHandlerFunctionCall'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMockForAbstractClass();
        $handler->expects($this->once())
                ->method('buildHandlerFunctionCall')
                ->with($event1)
                ->will($this->returnValue('jkl'));

        $this->injectProperty($handler, 'events', $queue);
        $result = $this->invokeMethod($handler, 'buildInlineCode');
        $this->assertContains('def', $result);
        $this->assertContains('jkl', $result);
    }

    /**
     * Tests the buildGlobalCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\PseudoHandler::buildGlobalCode
     */
    public function testBuildGlobalCode() {
        $event1 = new Event();
        $event1->setCode('abc');
        $event2 = new Event();
        $event2->setCode('def')
               ->setInline(true);

        $queue = new PriorityQueue();
        $queue->add($event1)
              ->add($event2);

        /* @var $handler \ManiaScript\Builder\Event\Handler\PseudoHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\PseudoHandler')
                        ->setMethods(array('buildHandlerFunction'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMockForAbstractClass();
        $handler->expects($this->once())
                ->method('buildHandlerFunction')
                ->with($event1)
                ->will($this->returnValue('ghi'));

        $this->injectProperty($handler, 'events', $queue);
        $result = $this->invokeMethod($handler, 'buildGlobalCode');
        $this->assertContains('ghi', $result);
    }
}

<?php

namespace ManiaScriptTests\Builder\Event\Handler;

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
     * Tests the buildCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\PseudoHandler::buildCode
     */
    public function testBuildCode() {
        $event1 = new Event();
        $event1->setCode('abc');
        $event2 = new Event();
        $event2->setCode('def')
               ->setInline(true);

        $queue = new PriorityQueue();
        $queue->add($event1)
              ->add($event2);

        /* @var $handler \ManiaScript\Builder\Event\Handler\PseudoHandler|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMock(
            'ManiaScript\Builder\Event\Handler\PseudoHandler',
            array('buildHandlerFunction', 'buildHandlerFunctionCall')
        );
        $handler->expects($this->once())
                ->method('buildHandlerFunction')
                ->with($event1)
                ->will($this->returnValue('ghi'));
        $handler->expects($this->once())
                ->method('buildHandlerFunctionCall')
                ->with($event1)
                ->will($this->returnValue('jkl'));

        $this->injectProperty($handler, 'events', $queue);
        $result = $handler->buildCode();
        $this->assertEquals($handler, $result);

        $globalCode = $this->extractProperty($handler, 'globalCode');
        $inlineCode = $this->extractProperty($handler, 'inlineCode');
        $this->assertContains('def', $inlineCode);
        $this->assertContains('ghi', $globalCode);
        $this->assertContains('jkl', $inlineCode);
    }
}

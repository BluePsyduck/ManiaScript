<?php

namespace ManiaScriptTests\Builder\Event\Handler;

use ManiaScript\Builder;
use ManiaScript\Builder\Event\Custom as CustomEvent;
use ManiaScript\Builder\Event\Handler\Custom;
use ManiaScript\Builder\PriorityQueue;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the custom event handler.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class CustomTest extends TestCase {
    /**
     * Tests the getTriggeredCustomEventsVariableName() method.
     * @covers \ManiaScript\Builder\Event\Handler\Custom::getTriggeredCustomEventsVariableName
     */
    public function testGetTriggeredCustomEventsVariableName() {
        $builder = new Builder();
        $builder->getOptions()->setFunctionPrefix('foo');

        $handler = new Custom($builder);
        $result = $this->invokeMethod($handler, 'getTriggeredCustomEventsVariableName');
        $this->assertEquals('foo_TriggeredCustomEvents', $result);
    }

    /**
     * Tests the prepare() method.
     * @covers \ManiaScript\Builder\Event\Handler\Custom::prepare
     */
    public function testPrepare() {
        $inlineCode = 'abc';
        $globalCode = 'def';
        $internalCode = 'jkl';

        /* @var $handler \ManiaScript\Builder\Event\Handler\Custom|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Custom')
                        ->setMethods(array('buildInlineCode', 'buildGlobalCode', 'buildInternalCode', 'addGlobalCode'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMockForAbstractClass();
        $handler->expects($this->once())
                ->method('buildInlineCode')
                ->will($this->returnValue($inlineCode));
        $handler->expects($this->once())
                ->method('buildGlobalCode')
                ->will($this->returnValue($globalCode));
        $handler->expects($this->once())
                ->method('buildInternalCode')
                ->will($this->returnValue($internalCode));
        $handler->expects($this->at(2))
                ->method('addGlobalCode')
                ->with($globalCode, PHP_INT_MAX)
                ->will($this->returnSelf());
        $handler->expects($this->at(4))
                ->method('addGlobalCode')
                ->with($internalCode, 0)
                ->will($this->returnSelf());

        $handler->prepare();
        $this->assertPropertyEquals($inlineCode, $handler, 'inlineCode');
    }

    /**
     * Tests the buildInlineCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\Custom::buildInlineCode
     */
    public function testBuildInlineCode() {
        $mergedEvents = array(
            'abc' => 'def' . PHP_EOL,
            'ghi' => 'jkl' . PHP_EOL
        );

        $queue = new PriorityQueue();
        $queue->add(new CustomEvent());

        $expectedInlineCode = <<<EOT
while (foo.count > 0) {
    switch (foo[0]) {
        case "abc": {
def
        }
        case "ghi": {
jkl
        }
    }
    declare Temp = foo.removekey(0);
}

EOT;

        /* @var $handler \ManiaScript\Builder\Event\Handler\Custom|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Custom')
                        ->setMethods(array('mergeEvents', 'getTriggeredCustomEventsVariableName'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMock();
        $handler->expects($this->once())
                ->method('mergeEvents')
                ->with($queue)
                ->will($this->returnValue($mergedEvents));
        $handler->expects($this->once())
                ->method('getTriggeredCustomEventsVariableName')
                ->will($this->returnValue('foo'));

        $this->injectProperty($handler, 'events', $queue);
        $result = $this->invokeMethod($handler, 'buildInlineCode');
        $this->assertEquals($expectedInlineCode, $result);
    }

    /**
     * Tests the mergeEvents() method.
     * @covers \ManiaScript\Builder\Event\Handler\Custom::mergeEvents
     */
    public function testMergeEvents() {
        $event1 = new CustomEvent();
        $event1->setName('abc')
               ->setCode('def')
               ->setInline(true);
        $event2 = new CustomEvent();
        $event2->setName('ghi')
               ->setCode('jkl')
               ->setInline(true);
        $event3 = new CustomEvent();
        $event3->setName('abc')
               ->setCode('mno')
               ->setInline(false);

        $expectedResult = array(
            'abc' => 'def' . PHP_EOL . 'pqr' . PHP_EOL,
            'ghi' => 'jkl' . PHP_EOL
        );

        $queue = new PriorityQueue();
        $queue->add($event1)
              ->add($event2)
              ->add($event3);

        /* @var $handler \ManiaScript\Builder\Event\Handler\Custom|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Custom')
                        ->setMethods(array('buildHandlerFunctionCall'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMock();
        $handler->expects($this->once())
                ->method('buildHandlerFunctionCall')
                ->with($event3)
                ->will($this->returnValue('pqr'));

        $result = $this->invokeMethod($handler, 'mergeEvents', array($queue));
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Tests the buildGlobalCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\Custom::buildGlobalCode
     */
    public function testBuildGlobalCode() {
        $event1 = new CustomEvent();
        $event1->setName('abc')
               ->setCode('def')
               ->setInline(true);

        $event2 = new CustomEvent();
        $event2->setName('ghi')
               ->setCode('jkl')
               ->setInline(false);

        $expectedGlobalCode = 'mno';

        /* @var $handler \ManiaScript\Builder\Event\Handler\Custom|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Custom')
                        ->setMethods(array('buildHandlerFunction'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMock();
        $handler->expects($this->once())
                ->method('buildHandlerFunction')
                ->with($event2)
                ->will($this->returnValue('mno'));

        $this->injectProperty($handler, 'events', array($event1, $event2));
        $result = $this->invokeMethod($handler, 'buildGlobalCode');
        $this->assertEquals($expectedGlobalCode, $result);
    }

    /**
     * Tests the buildInternalCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\Custom::buildInternalCode
     */
    public function testBuildInternalCode() {
        /* @var $handler \ManiaScript\Builder\Event\Handler\Custom|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Custom')
                        ->setMethods(array('getTriggeredCustomEventsVariableName'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMock();
        $handler->expects($this->once())
                ->method('getTriggeredCustomEventsVariableName')
                ->will($this->returnValue('abc'));

        $result = $this->invokeMethod($handler, 'buildInternalCode');
        $this->assertContains('declare Text[] abc;', $result);
    }

    /**
     * Tests the getTriggerCustomEventCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\Custom::getTriggerCustomEventCode
     */
    public function testGetTriggerCustomEventCode() {
        $name = 'abc';
        $expectedResult = 'def.add("abc");';

        /* @var $handler \ManiaScript\Builder\Event\Handler\Custom|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Custom')
                        ->setMethods(array('getTriggeredCustomEventsVariableName'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMock();
        $handler->expects($this->once())
                ->method('getTriggeredCustomEventsVariableName')
                ->will($this->returnValue('def'));

        $result = $handler->getTriggerCustomEventCode($name);
        $this->assertEquals($expectedResult, $result);
    }
}

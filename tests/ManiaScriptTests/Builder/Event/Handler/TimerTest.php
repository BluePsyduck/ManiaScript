<?php

namespace ManiaScriptTests\Builder\Event\Handler;

use ManiaScript\Builder\Event\Timer;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the timer event handler.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class TimerTest extends TestCase {
    /**
     * Tests the buildCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\Timer::buildCode
     */
    public function testBuildCode() {
        $event1 = new Timer();
        $event1->setName('abc')
               ->setCode('def')
               ->setInline(true);

        $event2 = new Timer();
        $event2->setName('ghi')
               ->setCode('jkl')
               ->setInline(false);

        $expectedInlineCode = <<<EOT
foreach (Time => Name in __Timers) {
    if (Time <= CurrentTime) {
        switch (Name) {
            case "abc": {
def
            }
            case "ghi": {
                pqr
            }
        }
        declare Temp = __Timers.removekey(Time);
    }
}

EOT;
        $expectedGlobalCode = 'mno';

        /* @var $handler \ManiaScript\Builder\Event\Handler\Timer|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Timer')
                        ->setMethods(array('buildHandlerFunction', 'buildHandlerFunctionCall'))
                        ->getMock();
        $handler->expects($this->once())
                ->method('buildHandlerFunction')
                ->with($event2)
                ->will($this->returnValue('mno'));
        $handler->expects($this->once())
                ->method('buildHandlerFunctionCall')
                ->with($event2)
                ->will($this->returnValue('pqr'));

        $this->injectProperty($handler, 'events', array($event1, $event2));
        $result = $handler->buildCode();
        $this->assertEquals($handler, $result);
        $this->assertPropertyEquals($expectedGlobalCode, $handler, 'globalCode');
        $this->assertPropertyEquals($expectedInlineCode, $handler, 'inlineCode');
    }
}

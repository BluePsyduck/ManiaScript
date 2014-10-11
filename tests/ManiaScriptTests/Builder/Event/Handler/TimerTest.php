<?php

namespace ManiaScriptTests\Builder\Event\Handler;

use ManiaScript\Builder;
use ManiaScript\Builder\Event\Handler\Timer;
use ManiaScript\Builder\Event\Timer as TimerEvent;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the timer event handler.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class TimerTest extends TestCase {
    /**
     * Tests the getTimersVariableName() method.
     * @covers \ManiaScript\Builder\Event\Handler\Timer::getTimersVariableName
     */
    public function testGetTimersVariableName() {
        $builder = new Builder();
        $builder->getOptions()->setFunctionPrefix('foo');

        $handler = new Timer($builder);
        $result = $this->invokeMethod($handler, 'getTimersVariableName');
        $this->assertEquals('foo_Timers', $result);
    }

    /**
     * Tests the getAddTimerFunctionName() method.
     * @covers \ManiaScript\Builder\Event\Handler\Timer::getAddTimerFunctionName
     */
    public function testGetAddTimerFunctionName() {
        $builder = new Builder();
        $builder->getOptions()->setFunctionPrefix('foo');

        $handler = new Timer($builder);
        $result = $this->invokeMethod($handler, 'getAddTimerFunctionName');
        $this->assertEquals('foo_AddTimer', $result);
    }

    /**
     * Tests the prepare() method.
     * @covers \ManiaScript\Builder\Event\Handler\Timer::prepare
     */
    public function testPrepare() {
        $inlineCode = 'abc';
        $globalCode = 'def';
        $internalCode = 'jkl';

        /* @var $handler \ManiaScript\Builder\Event\Handler\Timer|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Timer')
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
     * @covers \ManiaScript\Builder\Event\Handler\Timer::buildInlineCode
     */
    public function testBuildInlineCode() {
        $event1 = new TimerEvent();
        $event1->setName('abc')
               ->setCode('def')
               ->setInline(true);
        $event2 = new TimerEvent();
        $event2->setName('ghi')
               ->setCode('jkl')
               ->setInline(false);

        $expectedInlineCode = <<<EOT
foreach (Time => Name in pqr) {
    if (Time <= CurrentTime) {
        switch (Name) {
            case "abc": {
def
            }
            case "ghi": {
                mno
            }
        }
        declare Temp = pqr.removekey(Time);
    }
}

EOT;

        /* @var $handler \ManiaScript\Builder\Event\Handler\Timer|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Timer')
                        ->setMethods(array('buildHandlerFunctionCall', 'getTimersVariableName'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMock();
        $handler->expects($this->once())
                ->method('buildHandlerFunctionCall')
                ->with($event2)
                ->will($this->returnValue('mno'));
        $handler->expects($this->once())
                ->method('getTimersVariableName')
                ->will($this->returnValue('pqr'));

        $this->injectProperty($handler, 'events', array($event1, $event2));
        $result = $this->invokeMethod($handler, 'buildInlineCode');
        $this->assertEquals($expectedInlineCode, $result);
    }

    /**
     * Tests the buildGlobalCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\Timer::buildGlobalCode
     */
    public function testBuildGlobalCode() {
        $event1 = new TimerEvent();
        $event1->setName('abc')
               ->setCode('def')
               ->setInline(true);

        $event2 = new TimerEvent();
        $event2->setName('ghi')
               ->setCode('jkl')
               ->setInline(false);

        $expectedGlobalCode = 'mno';

        /* @var $handler \ManiaScript\Builder\Event\Handler\Timer|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Timer')
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
     * @covers \ManiaScript\Builder\Event\Handler\Timer::buildInternalCode
     */
    public function testBuildInternalCode() {
        /* @var $handler \ManiaScript\Builder\Event\Handler\Timer|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Timer')
                        ->setMethods(array('getTimersVariableName', 'getAddTimerFunctionName'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMock();
        $handler->expects($this->once())
                ->method('getTimersVariableName')
                ->will($this->returnValue('abc'));
        $handler->expects($this->once())
                ->method('getAddTimerFunctionName')
                ->will($this->returnValue('def'));

        $result = $this->invokeMethod($handler, 'buildInternalCode');
        $this->assertContains('declare Text[Integer] abc;', $result);
        $this->assertContains('Void def(Text Name, Integer Delay, Boolean ReplacePrevious) {', $result);
    }

    /**
     * Provides the data for the getAddTimerCode() test.
     * @return array The data.
     */
    public function provideGetAddTimerCode() {
        return array(
            array('foo("abc", 1337, False);', 'abc', 1337, false, 'foo'),
            array('foo("def", 42, True);', 'def', 42, true, 'foo')
        );
    }

    /**
     * Tests the getAddTimerCode() method.
     * @param string $expectedResult The expected result.
     * @param string $name The name to use.
     * @param int $delay The delay to use.
     * @param bool $replaceExisting The replace existing flag to use.
     * @param string $resultFunctionName The result of the getAddTimerFunctionName() method call.
     * @covers \ManiaScript\Builder\Event\Handler\Timer::getAddTimerCode
     * @dataProvider provideGetAddTimerCode
     */
    public function testGetAddTimerCode($expectedResult, $name, $delay, $replaceExisting, $resultFunctionName) {
        /* @var $handler \ManiaScript\Builder\Event\Handler\Timer|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Timer')
                        ->setMethods(array('getAddTimerFunctionName'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMock();
        $handler->expects($this->once())
                ->method('getAddTimerFunctionName')
                ->will($this->returnValue($resultFunctionName));

        $result = $handler->getAddTimerCode($name, $delay, $replaceExisting);
        $this->assertEquals($expectedResult, $result);
    }
}

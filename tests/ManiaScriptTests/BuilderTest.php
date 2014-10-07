<?php

namespace ManiaScriptTests;

use ManiaScript\Builder;
use ManiaScript\Builder\Code;
use ManiaScript\Builder\Options;
use ManiaScript\Builder\Directive\Constant;
use ManiaScript\Builder\Directive\Library;
use ManiaScript\Builder\Directive\Setting;
use ManiaScriptTests\Assets\Event;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the Builder class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class BuilderTest extends TestCase {
    /**
     * Tests the constructor.
     * @covers \ManiaScript\Builder::__construct
     */
    public function testConstruct() {
        $builder = new Builder();
        $result = $this->extractProperty($builder, 'options');
        $this->assertInstanceOf('ManiaScript\Builder\Options', $result);
    }

    /**
     * Tests the getOptions() method.
     * @covers \ManiaScript\Builder::getOptions
     */
    public function testGetOptions() {
        $builder = new Builder();
        $options = new Options();
        $this->injectProperty($builder, 'options', $options);
        $this->assertEquals($options, $builder->getOptions());
    }

    /**
     * Data provider for the addDirective test.
     * @return array The data.
     */
    public function providerAddDirective() {
        $directive1 = new Setting();
        $directive1->setValue('def')
                   ->setName('abc');

        $directive2 = new Constant();
        $directive2->setValue('ghi')
                   ->setName('def');

        $directive3 = new Library();
        $directive3->setLibrary('jkl')
                   ->setName('abc');

        return array(
            array( // Add directive to empty array.
                array($directive1->getName() => $directive1),
                $directive1,
                array()
            ),
            array( // Add directive with different name non-empty array.
                array($directive1->getName() => $directive1, $directive2->getName() => $directive2),
                $directive2,
                array($directive1->getName() => $directive1)
            ),
            array( // Override existing directive with same name.
                array($directive3->getName() => $directive3),
                $directive3,
                array($directive1->getName() => $directive1)
            )
        );
    }

    /**
     * Tests the addDirective() method.
     * @param array $expected The expected directives of the builder.
     * @param \ManiaScript\Builder\Directive\AbstractDirective $newDirective The directive to be added.
     * @param array $directives The directives before adding the new one.
     * @covers \ManiaScript\Builder::addDirective
     * @dataProvider providerAddDirective
     */
    public function testAddDirective($expected, $newDirective, $directives) {
        $builder = new Builder();
        $this->injectProperty($builder, 'directives', $directives);
        $result = $builder->addDirective($newDirective);
        $this->assertEquals($builder, $result);
        $this->assertPropertyEquals($expected, $builder, 'directives');
    }

    /**
     * Tests the addGlobalCode() method.
     * @covers \ManiaScript\Builder::addGlobalCode
     */
    public function testAddGlobalCode() {
        $code = new Code();

        $queue = $this->getMock('ManiaScript\Builder\PriorityQueue', array('add'));
        $queue->expects($this->once())
              ->method('add')
              ->with($code);

        $builder = new Builder();
        $this->injectProperty($builder, 'globalCodes', $queue);
        $result = $builder->addGlobalCode($code);
        $this->assertEquals($builder, $result);
    }

    /**
     * Tests the addEvent() method.
     * @covers \ManiaScript\Builder::addEvent
     */
    public function testAddEvent() {
        $event = new Event();

        $factory = $this->getMock('stdClass', array('getHandlerForEvent', 'addEvent'));
        $factory->expects($this->any())
                ->method('getHandlerForEvent')
                ->with($event)
                ->will($this->returnSelf());
        $factory->expects($this->once())
                ->method('addEvent')
                ->with($event);

        $builder = new Builder();
        $this->injectProperty($builder, 'eventHandlerFactory', $factory);

        $result = $builder->addEvent($event);
        $this->assertEquals($builder, $result);
    }

    /**
     * Tests the getTriggerCustomEventCode() method.
     * @covers \ManiaScript\Builder::getTriggerCustomEventCode
     */
    public function testGetTriggerCustomEventCode() {
        $name = 'abc';
        $expectedResult = '+++abc+++';

        $builder = new Builder();
        $result = $builder->getTriggerCustomEventCode($name);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Provides the data for the getAddTimerCode() test.
     * @return array The data.
     */
    public function provideGetAddTimerCode() {
        return array(
            array('__AddTimer("abc", 1337, False);', 'abc', 1337, false),
            array('__AddTimer("def", 42, True);', 'def', 42, true)
        );
    }

    /**
     * Tests the getAddTimerCode() method.
     * @param string $expectedResult The expected result.
     * @param string $name The name to use.
     * @param int $delay The delay to use.
     * @param bool $replaceExisting The replace existing flag to use.
     * @covers \ManiaScript\Builder::getAddTimerCode
     * @dataProvider provideGetAddTimerCode
     */
    public function testGetAddTimerCode($expectedResult, $name, $delay, $replaceExisting) {
        $builder = new Builder();
        $this->assertPropertyEquals(false, $builder, 'useTimers');
        $result = $builder->getAddTimerCode($name, $delay, $replaceExisting);
        $this->assertEquals($expectedResult, $result);
        $this->assertPropertyEquals(true, $builder, 'useTimers');
    }

    /**
     * Tests the build() method.
     * @covers \ManiaScript\Builder::build
     */
    public function testBuild() {
        $methods = array(
            'prepareHandlers', 'buildDirectives', 'buildInternalCode', 'buildGlobalCode', 'buildMainFunction',
            'compress', 'addScriptTag'
        );
        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMock('ManiaScript\Builder', $methods);
        foreach ($methods as $method) {
            $builder->expects($this->once())
                    ->method($method)
                    ->will($this->returnSelf());
        }

        $result = $builder->build();
        $this->assertEquals($builder, $result);
    }

    /**
     * Tests the getCode() method.
     * @covers \ManiaScript\Builder::getCode
     */
    public function testGetCode() {
        $expected = 'abc';
        $builder = new Builder();
        $this->injectProperty($builder, 'code', $expected);
        $this->assertEquals($expected, $builder->getCode());
    }

    /**
     * Tests the prepareHandlers() method.
     * @covers \ManiaScript\Builder::prepareHandlers
     */
    public function testPrepareHandlers() {
        /* @var $handler1 \ManiaScript\Builder\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler1 = $this->getMock('ManiaScript\Builder\Event\Handler\MouseClick', array('buildCode'));
        $handler1->expects($this->once())
                 ->method('buildCode');

        /* @var $handler2 \ManiaScript\Builder\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler2 = $this->getMock('ManiaScript\Builder\Event\Handler\MouseClick', array('buildCode'));
        $handler2->expects($this->once())
                 ->method('buildCode');

        /* @var $handler \ManiaScript\Builder\Event\Handler\Factory|\PHPUnit_Framework_MockObject_MockObject */
        $factory = $this->getMock('ManiaScript\Builder\Event\Handler\Factory', array('getAllHandlers'));
        $factory->expects($this->once())
                ->method('getAllHandlers')
                ->will($this->returnValue(array($handler1, $handler2)));

        $builder = new Builder();
        $this->injectProperty($builder, 'eventHandlerFactory', $factory);
        $result = $this->invokeMethod($builder, 'prepareHandlers');
        $this->assertEquals($builder, $result);
    }

    /**
     * Tests the buildDirectives() method.
     * @covers \ManiaScript\Builder::buildDirectives
     */
    public function testBuildDirectives() {
        /* @var $directive1 \ManiaScript\Builder\Directive\Setting|\PHPUnit_Framework_MockObject_MockObject */
        $directive1 = $this->getMock('ManiaScript\Builder\Directive\Setting', array('buildCode'));
        $directive1->expects($this->once())
                   ->method('buildCode')
                   ->will($this->returnValue('abc'));

        /* @var $directive2 \ManiaScript\Builder\Directive\Setting|\PHPUnit_Framework_MockObject_MockObject */
        $directive2 = $this->getMock('ManiaScript\Builder\Directive\Setting', array('buildCode'));
        $directive2->expects($this->once())
                   ->method('buildCode')
                   ->will($this->returnValue('def'));

        $builder = new Builder();
        $this->injectProperty($builder, 'directives', array($directive1, $directive2));

        $result = $this->invokeMethod($builder, 'buildDirectives');
        $this->assertEquals($builder, $result);
    }

    /**
     * Tests the buildInternalCode() method.
     * @covers \ManiaScript\Builder::buildInternalCode
     */
    public function testBuildInternalCode() {
        $builder = new Builder();
        $this->injectProperty($builder, 'useTimers', true)
             ->injectProperty($builder, 'code', 'abc');

        $result = $this->invokeMethod($builder, 'buildInternalCode');
        $this->assertEquals($builder, $result);

        $code = $this->extractProperty($builder, 'code');
        $this->assertContains('abc', $code);
        $this->assertContains('Void __AddTimer(Text Name, Integer Delay, Boolean ReplacePrevious) {', $code);
    }

    /**
     * Tests the buildGlobalCode() method.
     * @covers \ManiaScript\Builder::buildGlobalCode
     */
    public function testBuildGlobalCode() {
        /* @var $code1 \ManiaScript\Builder\Code|\PHPUnit_Framework_MockObject_MockObject */
        $code1 = $this->getMock('ManiaScript\Builder\Code', array('getCode'));
        $code1->expects($this->once())
              ->method('getCode')
              ->will($this->returnValue('abc'));

        /* @var $code2 \ManiaScript\Builder\Code|\PHPUnit_Framework_MockObject_MockObject */
        $code2 = $this->getMock('ManiaScript\Builder\Code', array('getCode'));
        $code2->expects($this->once())
              ->method('getCode')
              ->will($this->returnValue('def'));

        /* @var $handler1 \ManiaScript\Builder\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler1 = $this->getMock('ManiaScript\Builder\Event\Handler\MouseClick', array('getGlobalCode'));
        $handler1->expects($this->once())
                 ->method('getGlobalCode')
                 ->will($this->returnValue('ghi'));

        /* @var $handler2 \ManiaScript\Builder\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler2 = $this->getMock('ManiaScript\Builder\Event\Handler\MouseClick', array('getGlobalCode'));
        $handler2->expects($this->once())
                 ->method('getGlobalCode')
                 ->will($this->returnValue('jkl'));

        /* @var $handler \ManiaScript\Builder\Event\Handler\Factory|\PHPUnit_Framework_MockObject_MockObject */
        $factory = $this->getMock('ManiaScript\Builder\Event\Handler\Factory', array('getAllHandlers'));
        $factory->expects($this->once())
            ->method('getAllHandlers')
            ->will($this->returnValue(array($handler1, $handler2)));

        $builder = new Builder();
        $this->injectProperty($builder, 'globalCodes', array($code1, $code2))
             ->injectProperty($builder, 'eventHandlerFactory', $factory);

        $result = $this->invokeMethod($builder, 'buildGlobalCode');
        $this->assertEquals($builder, $result);

        $code = $this->extractProperty($builder, 'code');
        $this->assertContains('abc', $code);
        $this->assertContains('def', $code);
        $this->assertContains('ghi', $code);
        $this->assertContains('jkl', $code);
    }


    /**
     * Tests the buildMainFunction() method.
     * @covers \ManiaScript\Builder::buildMainFunction
     */
    public function testBuildMainFunction() {
        /* @var $handler1 \ManiaScript\Builder\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler1 = $this->getMock('ManiaScript\Builder\Event\Handler\MouseClick', array('getInlineCode'));
        $handler1->expects($this->once())
                 ->method('getInlineCode')
                 ->will($this->returnValue('abc'));

        /* @var $handler2 \ManiaScript\Builder\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler2 = $this->getMock('ManiaScript\Builder\Event\Handler\MouseClick', array('getInlineCode'));
        $handler2->expects($this->once())
                 ->method('getInlineCode')
                 ->will($this->returnValue('def'));

        /* @var $handler \ManiaScript\Builder\Event\Handler\Factory|\PHPUnit_Framework_MockObject_MockObject */
        $factory = $this->getMock('ManiaScript\Builder\Event\Handler\Factory', array('getHandler'));
        $factory->expects($this->any())
                ->method('getHandler')
                ->will($this->returnValueMap(array(
                    array('Load', $handler1),
                    array('FirstLoop', $handler2),
                )));

        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMockBuilder('ManiaScript\Builder')
                        ->setMethods(array('buildEventLoop'))
                        ->getMock();
        $builder->expects($this->once())
                ->method('buildEventLoop')
                ->will($this->returnValue('ghi'));

        $this->injectProperty($builder, 'eventHandlerFactory', $factory);
        $this->invokeMethod($builder, 'buildMainFunction');
        $code = $this->extractProperty($builder, 'code');
        $this->assertContains('Void __Dummy() {}', $code);
        $this->assertContains('main() {', $code);
        $this->assertContains('abc', $code);
        $this->assertContains('def', $code);
        $this->assertContains('ghi', $code);
    }

    /**
     * Provides the data for the buildEventLoop() test.
     * @return array The data.
     */
    public function provideBuildEventLoop() {
        return array(
            array(array('while(True)', 'yield;', 'abc', 'def'), array(), 'abc', 'def'),
            array(array('while(True)', 'yield;', 'abc'), array(), 'abc', ''),
            array(array('while(True)', 'yield;', 'def'), array(), '', 'def'),
            array(array(), array('while(True)', 'yield;'), '', ''),
        );
    }

    /**
     * Tests the buildEventLoop() method.
     * @param array $expectedStrings The strings expected in the result.
     * @param array $notExpectedStrings The strings expected to be not in the result.
     * @param string $resultLoopInline The result of the getInlineCode() method call of the Loop handler.
     * @param string $resultControlHandlerLoop The result of the buildControlHandlerLoop() method call.
     * @covers \ManiaScript\Builder::buildEventLoop
     * @dataProvider provideBuildEventLoop
     */
    public function testBuildEventLoop(
        $expectedStrings, $notExpectedStrings, $resultLoopInline, $resultControlHandlerLoop
    ) {
        /* @var $handler \ManiaScript\Builder\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMock('ManiaScript\Builder\Event\Handler\MouseClick', array('getInlineCode'));
        $handler->expects($this->any())
                ->method('getInlineCode')
                ->will($this->returnValue($resultLoopInline));

        /* @var $factory \ManiaScript\Builder\Event\Handler\Factory|\PHPUnit_Framework_MockObject_MockObject */
        $factory = $this->getMock('ManiaScript\Builder\Event\Handler\Factory', array('getHandler'));
        $factory->expects($this->at(0))
                ->method('getHandler')
                ->with('Loop')
                ->will($this->returnValue($handler));
        $factory->expects($this->at(1))
                ->method('getHandler')
                ->with('Timer')
                ->will($this->returnValue($handler));

        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMockBuilder('ManiaScript\Builder')
                        ->setMethods(array('buildControlHandlerLoop'))
                        ->getMock();
        $builder->expects($this->once())
                ->method('buildControlHandlerLoop')
                ->will($this->returnValue($resultControlHandlerLoop));
        $this->injectProperty($builder, 'eventHandlerFactory', $factory);

        $result = $this->invokeMethod($builder, 'buildEventLoop');
        foreach ($expectedStrings as $expectedString) {
            $this->assertContains($expectedString, $result);
        }
        foreach ($notExpectedStrings as $notExpectedString) {
            $this->assertNotContains($notExpectedString, $result);
        }
    }

    /**
     * Provides the data for the buildControlHandlerLoop() test.
     * @return array The data.
     */
    public function provideBuildControlHandlerLoop() {
        return array(
            array(array('foreach (Event in PendingEvents)', 'switch (Event.Type)', 'abc'), array(), 'abc'),
            array(array(), array('foreach (Event in PendingEvents)', 'switch (Event.Type)'), ''),
        );
    }

    /**
     * Tests the buildControlHandlerLoop() method.
     * @param array $expectedStrings The strings expected in the result.
     * @param array $notExpectedStrings The strings expected to be not in the result.
     * @param string $resultControlHandlerCases The result of the buildControlHandlerCases() method call.
     * @covers \ManiaScript\Builder::buildControlHandlerLoop
     * @dataProvider provideBuildControlHandlerLoop
     */
    public function testBuildControlHandlerLoop($expectedStrings, $notExpectedStrings, $resultControlHandlerCases) {
        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMockBuilder('ManiaScript\Builder')
                        ->setMethods(array('buildControlHandlerCases'))
                        ->getMock();
        $builder->expects($this->once())
                ->method('buildControlHandlerCases')
                ->will($this->returnValue($resultControlHandlerCases));

        $result = $this->invokeMethod($builder, 'buildControlHandlerLoop');
        foreach ($expectedStrings as $expectedString) {
            $this->assertContains($expectedString, $result);
        }
        foreach ($notExpectedStrings as $notExpectedString) {
            $this->assertNotContains($notExpectedString, $result);
        }
    }

    /**
     * Tests the buildControlHandlerCases() method.
     * @covers \ManiaScript\Builder::buildControlHandlerCases
     */
    public function testBuildControlHandlerCases() {
        /* @var $handler1 \ManiaScript\Builder\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler1 = $this->getMock('ManiaScript\Builder\Event\Handler\MouseClick', array('getInlineCode'));
        $handler1->expects($this->once())
                 ->method('getInlineCode')
                 ->will($this->returnValue('abc'));

        /* @var $handler2 \ManiaScript\Builder\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler2 = $this->getMock('ManiaScript\Builder\Event\Handler\MouseClick', array('getInlineCode'));
        $handler2->expects($this->once())
                 ->method('getInlineCode')
                 ->will($this->returnValue('def'));

        /* @var $factory \ManiaScript\Builder\Event\Handler\Factory|\PHPUnit_Framework_MockObject_MockObject */
        $factory = $this->getMock('ManiaScript\Builder\Event\Handler\Factory', array('getAllControlHandlers'));
        $factory->expects($this->once())
                ->method('getAllControlHandlers')
                ->will($this->returnValue(array($handler1, $handler2)));

        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMockBuilder('ManiaScript\Builder')
                        ->setMethods(array('buildControlHandlerLoop'))
                        ->getMock();
        $this->injectProperty($builder, 'eventHandlerFactory', $factory);

        $result = $this->invokeMethod($builder, 'buildControlHandlerCases');
        $this->assertEquals('abcdef', $result);
    }

    /**
     * Provides the data for the compress() test.
     * @return array The data.
     */
    public function provideCompress() {
        return array(
            array(' abc ', ' abc ', false),
            array('abc', ' abc ', true)
        );
    }

    /**
     * Tests the compress() method.
     * @param string $expected The expected code.
     * @param string $code The code to be set.
     * @param boolean $optionCompress The compress option.
     * @covers \ManiaScript\Builder::compress
     * @dataProvider provideCompress
     */
    public function testCompress($expected, $code, $optionCompress) {
        $builder = new Builder();
        $builder->getOptions()->setCompress($optionCompress);
        $this->injectProperty($builder, 'code', $code);
        $result = $this->invokeMethod($builder, 'compress');
        $this->assertEquals($builder, $result);
        $this->assertPropertyEquals($expected, $builder, 'code');
    }

    /**
     * Provides the data for the addScriptTag() test.
     * @return array The data.
     */
    public function provideAddScriptTag() {
        return array(
            array('abc', 'abc', false),
            array('<script><![CDATA[abc]]></script>', 'abc', true),
            array('<script><![CDATA[abc]]]]><![CDATA[>def]]></script>', 'abc]]>def', true)
        );
    }

    /**
     * Tests the addScriptTag() method.
     * @param string $expected The expected code.
     * @param string $code The code to be set.
     * @param boolean $optionIncludeTag The include tag option.
     * @covers \ManiaScript\Builder::addScriptTag
     * @dataProvider provideAddScriptTag
     */
    public function testAddScriptTag($expected, $code, $optionIncludeTag) {
        $builder = new Builder();
        $builder->getOptions()->setIncludeScriptTag($optionIncludeTag);
        $this->injectProperty($builder, 'code', $code);

        $result = $this->invokeMethod($builder, 'addScriptTag');
        $this->assertPropertyEquals($expected, $builder, 'code');
        $this->assertEquals($builder, $result);
    }
}
<?php

namespace ManiaScriptTests;

use ManiaScript\Builder;
use ManiaScript\Builder\Code;
use ManiaScript\Builder\Options;
use ManiaScript\Directive\Constant;
use ManiaScript\Directive\Library;
use ManiaScript\Directive\Setting;
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
     */
    public function testConstruct() {
        $builder = new Builder();
        $result = $this->extractProperty($builder, 'options');
        $this->assertInstanceOf('ManiaScript\Builder\Options', $result);
    }

    /**
     * Tests the getOptions() method.
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
        $directive1->setName('abc')
                   ->setValue('def');

        $directive2 = new Constant();
        $directive2->setName('def')
                   ->setValue('ghi');

        $directive3 = new Library();
        $directive3->setName('abc')
                   ->setAlias('jkl');

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
     * @param \ManiaScript\Directive\AbstractDirective $newDirective The directive to be added.
     * @param array $directives The directives before adding the new one.
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
     * Tests the build() method.
     */
    public function testBuild() {
        $methods = array(
            'prepareHandlers', 'buildDirectives', 'buildGlobalCode', 'buildMainFunction', 'compress', 'addScriptTag'
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
     */
    public function testGetCode() {
        $expected = 'abc';
        $builder = new Builder();
        $this->injectProperty($builder, 'code', $expected);
        $this->assertEquals($expected, $builder->getCode());
    }

    /**
     * Tests the prepareHandlers() method.
     */
    public function testPrepareHandlers() {
        /* @var $handler1 \ManiaScript\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler1 = $this->getMock('ManiaScript\Event\Handler\MouseClick', array('buildCode'));
        $handler1->expects($this->once())
                 ->method('buildCode');

        /* @var $handler2 \ManiaScript\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler2 = $this->getMock('ManiaScript\Event\Handler\MouseClick', array('buildCode'));
        $handler2->expects($this->once())
                 ->method('buildCode');

        /* @var $handler \ManiaScript\Event\Handler\Factory|\PHPUnit_Framework_MockObject_MockObject */
        $factory = $this->getMock('ManiaScript\Event\Handler\Factory', array('getAllHandlers'));
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
     */
    public function testBuildDirectives() {
        /* @var $directive1 \ManiaScript\Directive\Setting|\PHPUnit_Framework_MockObject_MockObject */
        $directive1 = $this->getMock('ManiaScript\Directive\Setting', array('buildCode'));
        $directive1->expects($this->once())
                   ->method('buildCode')
                   ->will($this->returnValue('abc'));

        /* @var $directive2 \ManiaScript\Directive\Setting|\PHPUnit_Framework_MockObject_MockObject */
        $directive2 = $this->getMock('ManiaScript\Directive\Setting', array('buildCode'));
        $directive2->expects($this->once())
                   ->method('buildCode')
                   ->will($this->returnValue('def'));

        $builder = new Builder();
        $this->injectProperty($builder, 'directives', array($directive1, $directive2));

        $result = $this->invokeMethod($builder, 'buildDirectives');
        $this->assertEquals($builder, $result);
    }

    /**
     * Tests the buildGlobalCode() method.
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

        /* @var $handler1 \ManiaScript\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler1 = $this->getMock('ManiaScript\Event\Handler\MouseClick', array('getGlobalCode'));
        $handler1->expects($this->once())
                 ->method('getGlobalCode')
                 ->will($this->returnValue('ghi'));

        /* @var $handler2 \ManiaScript\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler2 = $this->getMock('ManiaScript\Event\Handler\MouseClick', array('getGlobalCode'));
        $handler2->expects($this->once())
                 ->method('getGlobalCode')
                 ->will($this->returnValue('jkl'));

        /* @var $handler \ManiaScript\Event\Handler\Factory|\PHPUnit_Framework_MockObject_MockObject */
        $factory = $this->getMock('ManiaScript\Event\Handler\Factory', array('getAllHandlers'));
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
     */
    public function testBuildMainFunction() {
        /* @var $handler1 \ManiaScript\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler1 = $this->getMock('ManiaScript\Event\Handler\MouseClick', array('getInlineCode'));
        $handler1->expects($this->once())
                 ->method('getInlineCode')
                 ->will($this->returnValue('abc'));

        /* @var $handler2 \ManiaScript\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler2 = $this->getMock('ManiaScript\Event\Handler\MouseClick', array('getInlineCode'));
        $handler2->expects($this->once())
                 ->method('getInlineCode')
                 ->will($this->returnValue('def'));

        /* @var $handler3 \ManiaScript\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler3 = $this->getMock('ManiaScript\Event\Handler\MouseClick', array('getInlineCode'));
        $handler3->expects($this->once())
                 ->method('getInlineCode')
                 ->will($this->returnValue('ghi'));

        /* @var $handler4 \ManiaScript\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler4 = $this->getMock('ManiaScript\Event\Handler\MouseClick', array('getInlineCode'));
        $handler4->expects($this->once())
                 ->method('getInlineCode')
                 ->will($this->returnValue('jkl'));

        /* @var $handler5 \ManiaScript\Event\Handler\MouseClick|\PHPUnit_Framework_MockObject_MockObject */
        $handler5 = $this->getMock('ManiaScript\Event\Handler\MouseClick', array('getInlineCode'));
        $handler5->expects($this->once())
                 ->method('getInlineCode')
                 ->will($this->returnValue('mno'));

        /* @var $handler \ManiaScript\Event\Handler\Factory|\PHPUnit_Framework_MockObject_MockObject */
        $factory = $this->getMock('ManiaScript\Event\Handler\Factory', array('getAllControlHandlers', 'getHandler'));
        $factory->expects($this->any())
                ->method('getHandler')
                ->will($this->returnValueMap(array(
                    array('Load', $handler1),
                    array('FirstLoop', $handler2),
                    array('Loop', $handler3)
                )));
        $factory->expects($this->once())
                ->method('getAllControlHandlers')
                ->will($this->returnValue(array($handler4, $handler5)));

        $builder = new Builder();
        $this->injectProperty($builder, 'eventHandlerFactory', $factory);
        $this->invokeMethod($builder, 'buildMainFunction');
        $code = $this->extractProperty($builder, 'code');
        $this->assertContains('abc', $code);
        $this->assertContains('def', $code);
        $this->assertContains('ghi', $code);
        $this->assertContains('jkl', $code);
        $this->assertContains('mno', $code);
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
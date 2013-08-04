<?php

namespace ManiaScriptTests;

use ManiaScript\Builder;
use ManiaScript\Builder\Code;
use ManiaScript\Builder\Options;
use ManiaScript\Directive\Constant;
use ManiaScript\Directive\Library;
use ManiaScript\Directive\Setting;
use ManiaScriptTests\Assets\Event;
use ManiaScriptTests\Assets\GetterSetterTestCase;
use ReflectionProperty;

/**
 * The PHPUnit test of the Builder class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class BuilderTest extends GetterSetterTestCase {
    /**
     * Tests the constructor.
     */
    public function testConstruct() {
        $builder = new Builder();
        $reflectedProperty = new ReflectionProperty($builder, 'options');
        $reflectedProperty->setAccessible(true);
        $this->assertInstanceOf('ManiaScript\Builder\Options', $reflectedProperty->getValue($builder));
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

}

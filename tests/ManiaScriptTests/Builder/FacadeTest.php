<?php

namespace ManiaScriptTests\Builder;

use ManiaScript\Builder\Facade;
use ManiaScript\Builder;
use ManiaScriptTests\Assets\TestCase;
use stdClass;

/**
 * The PHPUnit test fpr the facade class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class FacadeTest extends TestCase {
    /**
     * Tests generically one of the add*() methods.
     * @param string $method The name of the method to test.
     * @param string $calledMethod The name of the method called from the builder.
     * @param array $params The parameters to be passed.
     */
    protected function doTestAdd($method, $calledMethod, $params) {
        $testCase = $this;

        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMockBuilder('ManiaScript\Builder')
                        ->setMethods(array($calledMethod))
                        ->getMock();
        $builder->expects($this->once())
                ->method($calledMethod)
                ->will($this->returnCallback(function($object) use ($testCase, $params) {
                    foreach ($params as $name => $value) {
                        $testCase->assertPropertyEquals($value, $object, $name);
                    }
                }));

        $facade = new Facade($builder);
        $result = call_user_func_array(array($facade, $method), $params);

        $this->assertEquals($facade, $result);
    }

    /**
     * Tests generically one of the get*Code() methods.
     * @param string $method The name of the method to test.
     * @param array $params The parameters to be passed.
     */
    protected function doTestGetCode($method, $params) {
        $expectedResult = 'foo';

        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMockBuilder('ManiaScript\Builder')
                        ->setMethods(array($method))
                        ->getMock();
        $temp = $builder->expects($this->once())
                        ->method($method);
        $temp = call_user_func_array(array($temp, 'with'), $params);
        $temp->will($this->returnValue($expectedResult));

        $facade = new Facade($builder);
        $result = call_user_func_array(array($facade, $method), $params);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Provides the data for the constructor test.
     * @return array The data.
     */
    public function provideConstruct() {
        $builder = new Builder();
        return array(
            array('ManiaScript\Builder', null),
            array($builder, $builder)
        );
    }

    /**
     * Tests the constructor.
     * @param object|string $expected The expected builder instance, or the expected class of it.
     * @param \ManiaScript\Builder|null $builder The builder to be set.
     * @covers \ManiaScript\Builder\Facade::__construct
     * @dataProvider provideConstruct
     */
    public function testConstruct($expected, $builder) {
        if (is_null($builder)) {
            $facade = new Facade();
        } else {
            $facade = new Facade($builder);
        }

        if (is_object($expected)) {
            $this->assertPropertyEquals($expected, $facade, 'builder');
        } else {
            $result = $this->extractProperty($facade, 'builder');
            $this->assertInstanceOf($expected, $result);
        }
    }

    /**
     * Tests the getOptions() method.
     * @covers \ManiaScript\Builder\Facade::getOptions
     */
    public function testGetOptions() {
        $options = new stdClass();

        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMockBuilder('ManiaScript\Builder')
                        ->setMethods(array('getOptions'))
                        ->getMock();
        $builder->expects($this->once())
                ->method('getOptions')
                ->will($this->returnValue($options));

        $facade = new Facade($builder);
        $result = $facade->getOptions();
        $this->assertEquals($options, $result);
    }

    /**
     * Tests the addSetting() method.
     * @covers \ManiaScript\Builder\Facade::addSetting
     */
    public function testAddSetting() {
        $this->doTestAdd('addSetting', 'addDirective', array(
            'name' => 'abc',
            'value' => 'def'
        ));
    }

    /**
     * Tests the addConstant() method.
     * @covers \ManiaScript\Builder\Facade::addConstant
     */
    public function testAddConstant() {
        $this->doTestAdd('addConstant', 'addDirective', array(
            'name' => 'abc',
            'value' => 'def'
        ));
    }
    /**
     * Tests the addLibrary() method.
     * @covers \ManiaScript\Builder\Facade::addLibrary
     */
    public function testAddLibrary() {
        $this->doTestAdd('addLibrary', 'addDirective', array(
            'library' => 'abc',
            'name' => 'def'
        ));
    }

    /**
     * Tests the addGlobalCode() method.
     * @covers \ManiaScript\Builder\Facade::addGlobalCode
     */
    public function testAddGlobalCode() {
        $this->doTestAdd('addGlobalCode', 'addGlobalCode', array(
            'code' => 'abc',
            'priority' => 42
        ));
    }

    /**
     * Tests the addMouseClick() method.
     * @covers \ManiaScript\Builder\Facade::addMouseClick
     */
    public function testAddMouseClick() {
        $this->doTestAdd('addMouseClick', 'addEvent', array(
            'code' => 'abc',
            'controlIds' => array('def', 'ghi'),
            'priority' => 42,
            'inline' => true
        ));
    }

    /**
     * Tests the addMouseOver() method.
     * @covers \ManiaScript\Builder\Facade::addMouseOver
     */
    public function testAddMouseOver() {
        $this->doTestAdd('addMouseOver', 'addEvent', array(
            'code' => 'abc',
            'controlIds' => array('def', 'ghi'),
            'priority' => 42,
            'inline' => true
        ));
    }

    /**
     * Tests the addMouseOut() method.
     * @covers \ManiaScript\Builder\Facade::addMouseOut
     */
    public function testAddMouseOut() {
        $this->doTestAdd('addMouseOut', 'addEvent', array(
            'code' => 'abc',
            'controlIds' => array('def', 'ghi'),
            'priority' => 42,
            'inline' => true
        ));
    }

    /**
     * Tests the addEntrySubmit() method.
     * @covers \ManiaScript\Builder\Facade::addEntrySubmit
     */
    public function testAddEntrySubmit() {
        $this->doTestAdd('addEntrySubmit', 'addEvent', array(
            'code' => 'abc',
            'controlIds' => array('def', 'ghi'),
            'priority' => 42,
            'inline' => true
        ));
    }

    /**
     * Tests the addKeyPress() method.
     * @covers \ManiaScript\Builder\Facade::addKeyPress
     */
    public function testAddKeyPress() {
        $this->doTestAdd('addKeyPress', 'addEvent', array(
            'code' => 'abc',
            'keyCodes' => array(21, 1337),
            'priority' => 42,
            'inline' => true
        ));
    }

    /**
     * Tests the addMenuNavigation() method.
     * @covers \ManiaScript\Builder\Facade::addMenuNavigation
     */
    public function testAddMenuNavigation() {
        $this->doTestAdd('addMenuNavigation', 'addEvent', array(
            'code' => 'abc',
            'actions' => array('Up', 'Down'),
            'priority' => 42,
            'inline' => true
        ));
    }

    /**
     * Tests the addLoad() method.
     * @covers \ManiaScript\Builder\Facade::addLoad
     */
    public function testAddLoad() {
        $this->doTestAdd('addLoad', 'addEvent', array(
            'code' => 'abc',
            'priority' => 42,
            'inline' => true
        ));
    }

    /**
     * Tests the addFirstLoop() method.
     * @covers \ManiaScript\Builder\Facade::addFirstLoop
     */
    public function testAddFirstLoop() {
        $this->doTestAdd('addFirstLoop', 'addEvent', array(
            'code' => 'abc',
            'priority' => 42,
            'inline' => true
        ));
    }

    /**
     * Tests the addLoop() method.
     * @covers \ManiaScript\Builder\Facade::addLoop
     */
    public function testAddLoop() {
        $this->doTestAdd('addLoop', 'addEvent', array(
            'code' => 'abc',
            'priority' => 42,
            'inline' => true
        ));
    }

    /**
     * Tests the addCustomEvent() method.
     * @covers \ManiaScript\Builder\Facade::addCustomEvent
     */
    public function testAddCustomEvent() {
        $this->doTestAdd('addCustomEvent', 'addEvent', array(
            'name' => 'abc',
            'code' => 'def',
            'priority' => 42,
            'inline' => true
        ));
    }

    /**
     * Tests the getTriggerCustomEventCode() method.
     * @covers \ManiaScript\Builder\Facade::getTriggerCustomEventCode
     */
    public function testGetTriggerCustomEventCode() {
        $this->doTestGetCode('getTriggerCustomEventCode', array(
            'name' => 'abc'
        ));
    }

    /**
     * Tests the addTimer() method.
     * @covers \ManiaScript\Builder\Facade::addTimer
     */
    public function testAddTimer() {
        $this->doTestAdd('addTimer', 'addEvent', array(
            'name' => 'abc',
            'code' => 'def',
            'priority' => 42,
            'inline' => true
        ));
    }

    /**
     * Tests the getAddTimerCode() method.
     * @covers \ManiaScript\Builder\Facade::getAddTimerCode
     */
    public function testGetAddTimerCode() {
        $this->doTestGetCode('getAddTimerCode', array(
            'name' => 'abc',
            'delay' => 1337,
            'replaceExisting' => true
        ));
    }

    /**
     * Tests the build() method.
     * @covers \ManiaScript\Builder\Facade::build
     */
    public function testBuild() {
        $expected = 'abc';

        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMockBuilder('ManiaScript\Builder')
                        ->setMethods(array('build', 'getCode'))
                        ->getMock();
        $builder->expects($this->once())
                ->method('build')
                ->will($this->returnSelf());
        $builder->expects($this->once())
                ->method('getCode')
                ->will($this->returnValue($expected));

        $facade = new Facade($builder);
        $result = $facade->build();
        $this->assertEquals($expected, $result);
    }
}

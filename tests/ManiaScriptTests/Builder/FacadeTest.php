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
        $test = $this;

        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMock('ManiaScript\Builder', array($calledMethod));
        $builder->expects($this->once())
            ->method($calledMethod)
            ->will($this->returnCallback(function($object) use ($test, $params) {
                foreach ($params as $name => $value) {
                    $test->assertPropertyEquals($value, $object, $name);
                }
            }));

        $facade = new Facade($builder);
        $result = call_user_func_array(array($facade, $method), $params);

        $this->assertEquals($facade, $result);
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
     */
    public function testGetOptions() {
        $options = new stdClass();

        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMock('ManiaScript\Builder', array('getOptions'));
        $builder->expects($this->once())
                ->method('getOptions')
                ->will($this->returnValue($options));

        $facade = new Facade($builder);
        $result = $facade->getOptions();
        $this->assertEquals($options, $result);
    }

    /**
     * Tests the addSetting() method.
     */
    public function testAddSetting() {
        $this->doTestAdd('addSetting', 'addDirective', array(
            'name' => 'abc',
            'value' => 'def'
        ));
    }

    /**
     * Tests the addConstant() method.
     */
    public function testAddConstant() {
        $this->doTestAdd('addConstant', 'addDirective', array(
            'name' => 'abc',
            'value' => 'def'
        ));
    }
    /**
     * Tests the addLibrary() method.
     */
    public function testAddLibrary() {
        $this->doTestAdd('addLibrary', 'addDirective', array(
            'name' => 'abc',
            'alias' => 'def'
        ));
    }

    /**
     * Tests the addGlobalCode() method.
     */
    public function testAddGlobalCode() {
        $this->doTestAdd('addGlobalCode', 'addGlobalCode', array(
            'code' => 'abc',
            'priority' => 42
        ));
    }

    /**
     * Tests the addMouseClick() method.
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
     * Tests the addLoad() method.
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
     */
    public function testAddLoop() {
        $this->doTestAdd('addLoop', 'addEvent', array(
            'code' => 'abc',
            'priority' => 42,
            'inline' => true
        ));
    }

    /**
     * Tests the build() method.
     */
    public function testBuild() {
        $expected = 'abc';

        /* @var $builder \ManiaScript\Builder|\PHPUnit_Framework_MockObject_MockObject */
        $builder = $this->getMock('ManiaScript\Builder', array('build', 'getCode'));
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

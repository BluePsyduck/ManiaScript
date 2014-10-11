<?php

namespace ManiaScriptTests\Builder\Event\Handler;

use ManiaScript\Builder;
use ManiaScript\Builder\Event\Handler\Factory;
use ManiaScript\Builder\Event\Handler\MouseClick as MouseClickHandler;
use ManiaScript\Builder\Event\MouseClick as MouseClickEvent;
use ManiaScriptTests\Assets\TestCase;
use stdClass;

/**
 * PHPUnit of the event handler factory.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class FactoryTest extends TestCase {

    /**
     * Tests the __construct() method.
     * @covers \ManiaScript\Builder\Event\Handler\Factory::__construct
     */
    public function testConstruct() {
        $builder = new Builder();
        $factory = new Factory($builder);
        $this->assertPropertyEquals($builder, $factory, 'builder');
    }

    /**
     * Provides the data for the getHandler() test.
     * @return array The data.
     */
    public function provideGetHandler() {
        return array(
            array(
                'ManiaScript\Builder\Event\Handler\MouseClick',
                1,
                array(),
                'MouseClick'
            ),
            array(
                'ManiaScript\Builder\Event\Handler\MouseClick',
                1,
                array('MouseClick' => new MouseClickHandler(new Builder())),
                'MouseClick'
            ),
            array(
                'ManiaScript\Builder\Event\Handler\MouseOver',
                2,
                array('MouseClick' => new MouseClickHandler(new Builder())),
                'MouseOver'
            )
        );
    }

    /**
     * Tests the getHandler() method.
     * @param string $expectedClass The type of the expected class.
     * @param int $expectedHandlerCount The number of handlers expected to be known to the factory.
     * @param array $handlers The known handlers to the factory.
     * @param string $name The name of the handler to be requested.
     * @covers \ManiaScript\Builder\Event\Handler\Factory::getHandler
     * @dataProvider provideGetHandler
     */
    public function testGetHandler($expectedClass, $expectedHandlerCount, $handlers, $name) {
        $factory = new Factory(new Builder());
        $this->injectProperty($factory, 'instances', $handlers);
        $result = $factory->getHandler($name);
        $this->assertInstanceOf($expectedClass, $result);
        $handlers = $this->extractProperty($factory, 'instances');
        $this->assertEquals($expectedHandlerCount, count($handlers));
    }

    /**
     * Tests the getHandlerForEvent() method.
     * @covers \ManiaScript\Builder\Event\Handler\Factory::getHandlerForEvent
     */
    public function testGetHandlerForEvent() {
        $handler = new MouseClickHandler(new Builder());

        /* @var $factory \ManiaScript\Builder\Event\Handler\Factory|\PHPUnit_Framework_MockObject_MockObject */
        $factory = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Factory')
                        ->setMethods(array('getHandler'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMock();
        $factory->expects($this->once())
                ->method('getHandler')
                ->with('MouseClick')
                ->will($this->returnValue($handler));

        $result = $factory->getHandlerForEvent(new MouseClickEvent());
        $this->assertEquals($handler, $result);
    }

    /**
     * Tests the getAllHandlers() method.
     * @covers \ManiaScript\Builder\Event\Handler\Factory::getAllHandlers
     */
    public function testGetAllHandlers() {
        $handlers = array('abc' => 'def', 'ghi' => 'jkl');
        $expected = array('def', 'jkl');

        $factory = new Factory(new Builder());
        $this->injectProperty($factory, 'instances', $handlers);
        $result = $factory->getAllHandlers();
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the getAllControlHandlers() method.
     * @covers \ManiaScript\Builder\Event\Handler\Factory::getAllControlHandlers
     */
    public function testGetAllControlHandlers() {
        $mouseClick = new MouseClickHandler(new Builder());
        $handlers = array(
            'abc' => $mouseClick,
            'def' => new stdClass()
        );
        $expected = array($mouseClick);

        $factory = new Factory(new Builder());
        $this->injectProperty($factory, 'instances', $handlers);
        $result = $factory->getAllControlHandlers();
        $this->assertEquals($expected, $result);
    }
}

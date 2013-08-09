<?php


namespace ManiaScriptTests\Event;

use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the ControlEvent class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class ControlEventTest extends TestCase {
    /**
     * Data provider for the setControlIds() test.
     * @return array The data.
     */
    public function providerSetControlIds() {
        return array(
            array(array(), array()),
            array(array('abc'), 'abc'),
            array(array('abc'), array('abc')),
            array(array('abc', 'def'), array('abc', 'def'))
        );
    }

    /**
     * Tests the setControlIds() method.
     * @param array The expected value.
     * @param string|array The control IDs to be set.
     * @dataProvider providerSetControlIds
     */
    public function testSetControlIds($expected, $controlIds) {
        /* @var $event \ManiaScript\Builder\Event\ControlEvent|\PHPUnit_Framework_MockObject_MockObject */
        $event = $this->getMockForAbstractClass('ManiaScript\Builder\Event\ControlEvent');
        $result = $event->setControlIds($controlIds);
        $this->assertPropertyEquals($expected, $event, 'controlIds');
        $this->assertEquals($event, $result);
    }

    /**
     * Tests the getControlIds() method.
     */
    public function testGetControlIds() {
        $expected = array('abc');
        /* @var $event \ManiaScript\Builder\Event\ControlEvent|\PHPUnit_Framework_MockObject_MockObject */
        $event = $this->getMockForAbstractClass('ManiaScript\Builder\Event\ControlEvent');
        $this->injectProperty($event, 'controlIds', $expected);
        $this->assertEquals($expected, $event->getControlIds());
    }
}

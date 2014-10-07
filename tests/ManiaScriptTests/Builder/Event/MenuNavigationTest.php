<?php

namespace ManiaScriptTests\Builder\Event;

use ManiaScript\Builder\Event\MenuNavigation;
use ManiaScriptTests\Assets\TestCase;

/**
 * PHPUnit of the menu navigation event.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class MenuNavigationTest extends TestCase {
    /**
     * Provides the data for the setActions() test.
     * @return array The data.
     */
    public function provideSetActions() {
        return array(
            array(array('abc'), array('abc' => true, 'def' => false), array('abc', 'def')),
            array(array('abc'), array('abc' => true), 'abc'),
        );
    }

    /**
     * Tests the setActions() method.
     * @param array $expectedActions The expected actions.
     * @param array $resultsIsValidAction The results of the isValidAction() method calls.
     * @param array|string $actions The actions to use.
     * @covers \ManiaScript\Builder\Event\MenuNavigation::setActions
     * @dataProvider provideSetActions
     */
    public function testSetActions($expectedActions, $resultsIsValidAction, $actions) {
        /* @var $event \ManiaScript\Builder\Event\MenuNavigation|\PHPUnit_Framework_MockObject_MockObject */
        $event = $this->getMockBuilder('ManiaScript\Builder\Event\MenuNavigation')
                      ->setMethods(array('isValidAction'))
                      ->getMock();
        $index = 0;
        foreach ($resultsIsValidAction as $with => $result) {
            $event->expects($this->at($index))
                  ->method('isValidAction')
                  ->with($with)
                  ->will($this->returnValue($result));
            ++$index;
        }
        $result = $event->setActions($actions);
        $this->assertEquals($event, $result);
        $this->assertPropertyEquals($expectedActions, $event, 'actions');
    }

    /**
     * Tests the getActions() method.
     * @covers \ManiaScript\Builder\Event\MenuNavigation::getActions
     */
    public function testGetActions() {
        $expected = array('abc', 'def');
        $event = new MenuNavigation();
        $this->injectProperty($event, 'actions', $expected);
        $result = $event->getActions();
        $this->assertEquals($expected, $result);
    }

    /**
     * Provides the data for the isValidAction() test.
     * @return array The data.
     */
    public function provideIsValidAction() {
        return array(
            array(true, array('abc', 'def'), 'abc'),
            array(false, array('abc', 'def'), 'ghi')
        );
    }

    /**
     * Tests the isValidAction() method.
     * @param bool $expectedResult The expected result.
     * @param array $validActions The valid actions to set.
     * @param string $action The action to use.
     * @covers \ManiaScript\Builder\Event\MenuNavigation::isValidAction
     * @dataProvider provideIsValidAction
     */
    public function testIsValidAction($expectedResult, $validActions, $action) {
        $event = new MenuNavigation();
        $this->injectProperty($event, 'validActions', $validActions);
        $result = $this->invokeMethod($event, 'isValidAction', array($action));
        $this->assertEquals($expectedResult, $result);
    }
}

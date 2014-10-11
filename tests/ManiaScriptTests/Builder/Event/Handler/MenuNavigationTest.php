<?php

namespace ManiaScriptTests\Builder\Event\Handler;

use ManiaScript\Builder;
use ManiaScriptTests\Assets\TestCase;
use ManiaScript\Builder\Event\MenuNavigation as MenuNavigationEvent;
use ManiaScript\Builder\Event\Handler\MenuNavigation as MenuNavigationHandler;

/**
 * PHPUnit test of the menu navigation event handler.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class MenuNavigationTest extends TestCase {
    /**
     * Provides the data for the buildCondition() test.
     * @return array The data.
     */
    public function provideBuildCondition() {
        return array(
            array('', array()),
            array('Event.MenuNavAction == CMlEvent::EMenuNavAction::abc', array('abc')),
            array(
                'Event.MenuNavAction == CMlEvent::EMenuNavAction::abc'
                . ' || Event.MenuNavAction == CMlEvent::EMenuNavAction::def',
                array('abc', 'def')
            )
        );
    }

    /**
     * Tests the buildCondition() method.
     * @param string $expected The expected condition string.
     * @param array $actions The actions to be set.
     * @covers \ManiaScript\Builder\Event\Handler\MenuNavigation::buildCondition
     * @dataProvider provideBuildCondition
     */
    public function testBuildCondition($expected, $actions) {
        $event = new MenuNavigationEvent();
        $this->injectProperty($event, 'actions', $actions);

        $handler = new MenuNavigationHandler(new Builder());
        $result = $this->invokeMethod($handler, 'buildCondition', array($event));
        $this->assertEquals($expected, $result);
    }
}

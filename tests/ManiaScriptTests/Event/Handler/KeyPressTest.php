<?php

namespace ManiaScriptTests\Event\Handler;

use ManiaScript\Builder\Event\KeyPress as KeyPressEvent;
use ManiaScript\Builder\Event\Handler\KeyPress as KeyPressHandler;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the KeyPress event handler.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class KeyPressTest extends TestCase {

    /**
     * Provides the data for the buildCondition() test.
     * @return array The data.
     */
    public function provideBuildCondition() {
        return array(
            array('', array()),
            array('Event.KeyCode == 42', array(42)),
            array('Event.KeyCode == 42 || Event.KeyCode == 1337', array(42, 1337))
        );
    }

    /**
     * Tests the buildCondition() method.
     * @param string $expected The expected condition string.
     * @param array $keyCodes The key codes to be set.
     * @dataProvider provideBuildCondition
     */
    public function testBuildCondition($expected, $keyCodes) {
        $event = new KeyPressEvent();
        $this->injectProperty($event, 'keyCodes', $keyCodes);

        $handler = new KeyPressHandler();
        $result = $this->invokeMethod($handler, 'buildCondition', array($event));
        $this->assertEquals($expected, $result);
    }
}

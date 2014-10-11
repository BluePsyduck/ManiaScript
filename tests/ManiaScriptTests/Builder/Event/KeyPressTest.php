<?php


namespace ManiaScriptTests\Builder\Event;

use ManiaScript\Builder\Event\KeyPress;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the KeyPress event.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class KeyPressTest extends TestCase {
    /**
     * Data provider for the setKeyCodes() test.
     * @return array The data.
     */
    public function providerSetKeyCodes() {
        return array(
            array(array(), array()),
            array(array(42), 42),
            array(array(42), array(42)),
            array(array(42, 1337), array(42, 1337))
        );
    }

    /**
     * Tests the setKeyCodes() method.
     * @param array $expected The expected value.
     * @param int|array $keyCodes The key codes to be set.
     * @covers \ManiaScript\Builder\Event\KeyPress::setKeyCodes
     * @dataProvider providerSetKeyCodes
     */
    public function testSetKeyCodes($expected, $keyCodes) {
        $event = new KeyPress();
        $result = $event->setKeyCodes($keyCodes);
        $this->assertPropertyEquals($expected, $event, 'keyCodes');
        $this->assertEquals($event, $result);
    }

    /**
     * Tests the getKeyCodes() method.
     * @covers \ManiaScript\Builder\Event\KeyPress::getKeyCodes
     */
    public function testGetKeyCodes() {
        $expected = array('abc');
        $event = new KeyPress();
        $this->injectProperty($event, 'keyCodes', $expected);
        $this->assertEquals($expected, $event->getKeyCodes());
    }
}

<?php

namespace ManiaScriptTests\Builder\Event;

use ManiaScript\Builder\Event\Custom;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the Custom event class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class CustomTest extends TestCase {
    /**
     * Tests the setName() method.
     * @covers \ManiaScript\Builder\Event\Custom::setName
     */
    public function testSetName() {
        $expected = 'abc';
        $event = new Custom();
        $result = $event->setName($expected);
        $this->assertEquals($event, $result);
        $this->assertPropertyEquals($expected, $event, 'name');
    }

    /**
     * Tests the getName() method.
     * @covers \ManiaScript\Builder\Event\Custom::getName
     */
    public function testGetName() {
        $expected = 'abc';
        $event = new Custom();
        $this->injectProperty($event, 'name', $expected);
        $result = $event->getName();
        $this->assertEquals($expected, $result);
    }
}

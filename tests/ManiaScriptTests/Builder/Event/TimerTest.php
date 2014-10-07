<?php

namespace ManiaScriptTests\Builder\Event;

use ManiaScript\Builder\Event\Timer;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit of the timer event class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class TimerTest extends TestCase {
    /**
     * Tests the setName() method.
     * @covers \ManiaScript\Builder\Event\Timer::setName
     */
    public function testSetName() {
        $expected = 'abc';
        $timer = new Timer();
        $result = $timer->setName($expected);
        $this->assertEquals($timer, $result);
        $this->assertPropertyEquals($expected, $timer, 'name');
    }

    /**
     * Tests the getName() method.
     * @covers \ManiaScript\Builder\Event\Timer::getName
     */
    public function testGetName() {
        $expected = 'abc';
        $timer = new Timer();
        $this->injectProperty($timer, 'name', $expected);
        $result = $timer->getName();
        $this->assertEquals($expected, $result);
    }
}

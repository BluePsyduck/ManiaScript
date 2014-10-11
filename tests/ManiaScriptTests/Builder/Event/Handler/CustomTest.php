<?php

namespace ManiaScriptTests\Builder\Event\Handler;

use ManiaScript\Builder;
use ManiaScript\Builder\Event\Custom as CustomEvent;
use ManiaScript\Builder\Event\Handler\Custom as CustomHandler;
use ManiaScriptTests\Assets\TestCase;

/**
 * The PHPUnit test of the custom event handler.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class CustomTest extends TestCase {
    /**
     * Tests the buildGlobalCode() method.
     * @covers \ManiaScript\Builder\Event\Handler\Custom::buildGlobalCode
     */
    public function testBuildGlobalCode() {
        $event1 = new CustomEvent();
        $event1->setName('abc');
        $event2 = new CustomEvent();
        $event2->setName('def');

        /* @var $handler \ManiaScript\Builder\Event\Handler\Custom|\PHPUnit_Framework_MockObject_MockObject */
        $handler = $this->getMockBuilder('ManiaScript\Builder\Event\Handler\Custom')
                        ->setMethods(array('buildCodeOfEvent'))
                        ->setConstructorArgs(array(new Builder()))
                        ->getMock();
        $handler->expects($this->at(0))
                ->method('buildCodeOfEvent')
                ->with($event1)
                ->will($this->returnValue('ghi'));
        $handler->expects($this->at(1))
                ->method('buildCodeOfEvent')
                ->with($event2)
                ->will($this->returnValue('jkl'));

        $this->injectProperty($handler, 'events', array($event1, $event2));
        $result = $this->invokeMethod($handler, 'buildGlobalCode');
        $this->assertEquals('ghijkl', $result);
    }

    /**
     * Provides the data for the buildCodeOfEvent() test.
     * @return array The data.
     */
    public function provideBuildCodeOfEvent() {
        $event1 = new CustomEvent();
        $event1->setName('abc');

        $event2 = new CustomEvent();
        $event2->setName('def')
               ->setCode('ghi');

        $code2 = <<<EOT
***def***
***
ghi
***

EOT;

        return array(
            array('', $event1),
            array($code2, $event2)
        );
    }

    /**
     * Tests the buildCodeOfEvent() method.
     * @param string $expectedResult The expected result.
     * @param \ManiaScript\Builder\Event\Custom $event The event to use.
     * @covers \ManiaScript\Builder\Event\Handler\Custom::buildCodeOfEvent
     * @dataProvider provideBuildCodeOfEvent
     */
    public function testBuildCodeOfEvent($expectedResult, $event) {
        $builder = new Builder();
        $handler = new CustomHandler($builder);
        $result = $this->invokeMethod($handler, 'buildCodeOfEvent', array($event));
        $this->assertEquals($expectedResult, $result);
    }
}

<?php

namespace ManiaScriptTests\Builder;

use ManiaScript\Builder\PriorityQueue;
use ManiaScriptTests\Assets\GetterSetterTestCase;
use ManiaScriptTests\Assets\PriorityQueueItem;
use ReflectionMethod;

/**
 * The PHPUnit test of the Priority Queue.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class PriorityQueueTest extends GetterSetterTestCase {

    /**
     * Provides the data for the add() test.
     * @return array The data.
     */
    public function providerAdd() {
        $item1 = new PriorityQueueItem(42);
        $item2 = new PriorityQueueItem(21);
        $item3 = new PriorityQueueItem(42);

        return array(
            array(
                array(42 => array($item1)),
                $item1,
                array()
            ),
            array(
                array(42 => array($item1), 21 => array($item2)),
                $item2,
                array(42 => array($item1))
            ),
            array(
                array(42 => array($item1, $item3), 21 => array($item2)),
                $item3,
                array(42 => array($item1), 21 => array($item2))
            )
        );
    }

    /**
     * Tests the add() method.
     * @param array $expected The expected items.
     * @param \ManiaScript\Builder\PriorityQueueItem $newItem The item to be added.
     * @param array $existingItems The items before adding the new one.
     * @dataProvider providerAdd
     */
    public function testAdd($expected, $newItem, $existingItems) {
        $queue = new PriorityQueue();
        $this->injectProperty($queue, 'items', $existingItems);
        $result = $queue->add($newItem);
        $this->assertEquals($queue, $result);
        $this->assertPropertyEquals($expected, $queue, 'items');
    }

    /**
     * Tests the mergeItems() method.
     */
    public function testMergeItems() {
        $rawItems = array(
            3 => array('def', 'ghi'),
            5 => array('jkl'),
            0 => array('abc')
        );
        $expected = array('abc', 'def', 'ghi', 'jkl');

        $queue = new PriorityQueue();
        $this->injectProperty($queue, 'items', $rawItems);

        $reflectedProperty = new ReflectionMethod($queue, 'mergeItems');
        $reflectedProperty->setAccessible(true);
        $result = $reflectedProperty->invoke($queue);
        $this->assertPropertyEquals($expected, $queue, 'mergedItems');
        $this->assertEquals($queue, $result);
    }

    /**
     * Tests the rewind() method.
     */
    public function testRewind() {
        /* @var $queue \ManiaScript\Builder\PriorityQueue|\PHPUnit_Framework_MockObject_MockObject */
        $queue = $this->getMock('ManiaScript\Builder\PriorityQueue', array('mergeItems'));
        $this->injectProperty($queue, 'currentIndex', 42);
        $queue->expects($this->once())
              ->method('mergeItems');
        $queue->rewind();
        $this->assertPropertyEquals(0, $queue, 'currentIndex');
    }

    /**
     * Tests the next() method.
     */
    public function testNext() {
        $queue = new PriorityQueue();
        $this->injectProperty($queue, 'currentIndex', 42);
        $queue->next();
        $this->assertPropertyEquals(43, $queue, 'currentIndex');
    }

    /**
     * Provides the data for the valid() test.
     * @return array The data.
     */
    public function providerValid() {
        $items = array('abc', 'def', 'ghi');
        return array(
            array(true, 0, $items),
            array(true, 2, $items),
            array(false, 3, $items)
        );
    }

    /**
     * Tests the valid() method.
     * @param boolean $expected The expected result.
     * @param int $currentIndex The current index to be set.
     * @param array $mergedItems The merged items to be set.
     * @dataProvider providerValid
     */
    public function testValid($expected, $currentIndex, $mergedItems) {
        $queue = new PriorityQueue();
        $this->injectProperty($queue, 'mergedItems', $mergedItems)
            ->injectProperty($queue, 'currentIndex', $currentIndex);
        $result = $queue->valid();
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the key() method.
     */
    public function testKey() {
        $expected = 42;
        $queue = new PriorityQueue();
        $this->injectProperty($queue, 'currentIndex', $expected);
        $result = $queue->key();
        $this->assertEquals($expected, $result);
    }

    /**
     * Provides the data for the current() test.
     * @return array The data.
     */
    public function providerCurrent() {
        $items = array('abc', 'def', 'ghi');
        return array(
            array('abc', 0, $items),
            array('ghi', 2, $items),
            array(null, 3, $items)
        );
    }

    /**
     * Tests the current() method.
     * @param mixed $expected The expected value.
     * @param int $currentIndex The current index to be set.
     * @param array $mergedItems The merged items to be set.
     * @dataProvider providerCurrent
     */
    public function testCurrent($expected, $currentIndex, $mergedItems) {
        $queue = new PriorityQueue();
        $this->injectProperty($queue, 'mergedItems', $mergedItems)
             ->injectProperty($queue, 'currentIndex', $currentIndex);
        $result = $queue->current();
        $this->assertEquals($expected, $result);
    }
}

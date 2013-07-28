<?php

namespace ManiaScriptTests\Builder\Directive;

use ManiaScriptTests\Assets\GetterSetterTestCase;

/**
 * The PHPUnit test of the AbstractDirective class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class AbstractDirectiveTest extends GetterSetterTestCase {
    /**
     * Tests the constructor.
     */
    public function testConstruct() {
        $expectedName = 'abc';
        $expectedValue = 'def';
        $directive = $this->getMockForAbstractClass(
            'ManiaScript\Builder\Directive\AbstractDirective',
            array($expectedName, $expectedValue)
        );

        $this->assertPropertyEquals($expectedName, $directive, 'name');
        $this->assertPropertyEquals($expectedValue, $directive, 'value');
    }
}
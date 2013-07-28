<?php

namespace ManiaScriptTests\Builder\Directive;

use ManiaScript\Builder\Directive\Constant;
use PHPUnit_Framework_TestCase;

/**
 * The PHPUnit test of the Constant directive.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class ConstantTest extends PHPUnit_Framework_TestCase {
    /**
     * Tests the build() method.
     */
    public function testBuild() {
        $directive = new Constant('abc', 'def');
        $result = $directive->build();
        $this->assertEquals('#Const abc def' . "\n", $result);
    }
}
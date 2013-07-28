<?php

namespace ManiaScriptTests\Builder\Directive;

use ManiaScript\Builder\Directive\Setting;
use PHPUnit_Framework_TestCase;

/**
 * The PHPUnit test of the Setting directive.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class SettingTest extends PHPUnit_Framework_TestCase {
    /**
     * Tests the build() method.
     */
    public function testBuild() {
        $directive = new Setting('abc', 'def');
        $result = $directive->build();
        $this->assertEquals('#Setting abc def' . "\n", $result);
    }
}
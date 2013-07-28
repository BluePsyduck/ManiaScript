<?php

namespace ManiaScriptTests\Builder\Directive;

use ManiaScript\Builder\Directive\Library;
use ManiaScriptTests\Assets\GetterSetterTestCase;

/**
 * The PHPUnit test of the Library directive.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class LibraryTest extends GetterSetterTestCase {
    /**
     * Data provider of the constructor test.
     * @return array The data.
     */
    public function providerConstruct() {
        return array(
            array('abc', 'def', 'abc', 'def'),
            array('abc', 'abc', 'abc', '')
        );
    }

    /**
     * Tests the constructor.
     * @param string $expectedLibrary The expected library.
     * @param unknown $expectedAlias The expected alias.
     * @param unknown $library The library.
     * @param unknown $alias The alias.
     * @dataProvider providerConstruct
     */
    public function testConstruct($expectedLibrary, $expectedAlias, $library, $alias) {
        $directive = new Library($library, $alias);
        $this->assertPropertyEquals($expectedLibrary, $directive, 'name');
        $this->assertPropertyEquals($expectedAlias, $directive, 'value');
    }

    /**
     * Tests the build() method.
     */
    public function testBuild() {
        $directive = new Library('abc', 'def');
        $result = $directive->build();
        $this->assertEquals('#Include "abc" as def' . "\n", $result);
    }
}
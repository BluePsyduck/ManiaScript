<?php

namespace ManiaScriptTests;

use ManiaScript\Builder\Options;
use ManiaScript\Builder;
use ManiaScriptTests\Assets\GetterSetterTestCase;
use ReflectionProperty;

/**
 * The PHPUnit test of the Builder class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class BuilderTest extends GetterSetterTestCase {
    /**
     * Tests the constructor.
     */
    public function testConstruct() {
        $builder = new Builder();
        $reflectedProperty = new ReflectionProperty($builder, 'options');
        $reflectedProperty->setAccessible(true);
        $this->assertInstanceOf('ManiaScript\Builder\Options', $reflectedProperty->getValue($builder));
    }

    /**
     * Tests the setOptions() method.
     */
    public function testSetOptions() {
        $builder = new Builder();
        $options = new Options();
        $builder->setOptions($options);
        $this->assertPropertyEquals($options, $builder, 'options');
    }

    /**
     * Tests the getOptions() method.
     */
    public function testGetOptions() {
        $builder = new Builder();
        $options = new Options();
        $this->injectProperty($builder, 'options', $options);
        $this->assertEquals($options, $builder->getOptions());
    }

    /**
     * Data provider for the addDirective test.
     * @return array The data.
     */
    public function providerAddDirective() {
        $directive1 = new Builder\Directive\Setting();
        $directive1->setName('abc')
                   ->setValue('def');

        $directive2 = new Builder\Directive\Constant();
        $directive2->setName('def')
                   ->setValue('ghi');

        $directive3 = new Builder\Directive\Library();
        $directive3->setName('abc')
                   ->setAlias('jkl');

        return array(
            array( // Add directive to empty array.
                array($directive1->getName() => $directive1),
                $directive1,
                array()
            ),
            array( // Add directive with different name non-empty array.
                array($directive1->getName() => $directive1, $directive2->getName() => $directive2),
                $directive2,
                array($directive1->getName() => $directive1)
            ),
            array( // Override existing directive with same name.
                array($directive3->getName() => $directive3),
                $directive3,
                array($directive1->getName() => $directive1)
            )
        );
    }

    /**
     * Tests the addDirective() method.
     * @param array $expected The expected direvties of the builder.
     * @param \ManiaScript\Builder\Directive\AbstractDirective $newDirective The directive to be added.
     * @param array $directives The directives before adding the new one.
     * @dataProvider providerAddDirective
     */
    public function testAddDirective($expected, $newDirective, $directives) {
        $builder = new Builder();
        $this->injectProperty($builder, 'directives', $directives);
        $result = $builder->addDirective($newDirective);
        $this->assertEquals($builder, $result);
        $this->assertPropertyEquals($expected, $builder, 'directives');
    }
}

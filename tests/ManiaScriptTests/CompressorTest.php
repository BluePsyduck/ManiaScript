<?php

namespace ManiaScriptTests;

use ManiaScript\Compressor;
use ManiaScriptTests\Assets\GetterSetterTestCase;
use ReflectionMethod;

/**
 * The PHPUnit test of the Compressor class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class CompressorTest extends GetterSetterTestCase {
    /**
     * Tests property initialization on class construction.
     */
    public function testConstruct() {
        $compressor = new Compressor();
        $this->assertPropertyEquals('', $compressor, 'code');
        $this->assertPropertyEquals(0, $compressor, 'codeLength');
        $this->assertPropertyEquals('', $compressor, 'compressedCode');
    }

    /**
     * Tests the setCode() method.
     */
    public function testSetCode() {
        $expected = 'abc';
        $compressor = new Compressor();
        $result = $compressor->setCode($expected);
        $this->assertPropertyEquals($expected, $compressor, 'code');
        $this->assertPropertyEquals(strlen($expected), $compressor, 'codeLength');
        $this->assertEquals($compressor, $result);
    }

    /**
     * Tests the compress() method.
     */
    public function testCompress() {
        $compressor = $this->getMock('ManiaScript\Compressor', array('read'));
        $compressor->expects($this->once())
                   ->method('read');
        $this->injectProperty($compressor, 'compressedCode', 'abc')
             ->injectProperty($compressor, 'currentPosition', 42);

        $compressor->compress();
        $this->assertPropertyEquals('', $compressor, 'compressedCode');
        $this->assertPropertyEquals(0, $compressor, 'currentPosition');
    }

    /**
     * Tests the getCompressedCode() method.
     */
    public function testGetCompressedCode() {
        $expected = 'abc';
        $compressor = new Compressor();
        $this->injectProperty($compressor, 'compressedCode', $expected);
        $this->assertEquals($expected, $compressor->getCompressedCode());
    }

    /**
     * Data provider for the read test.
     * @return The data.
     */
    public function providerRead() {
        return array(
            array('readSlash', '/'),
            array('readDirective', '#'),
            array('readString', '"'),
            array('readWhitespace', ' '),
            array(null, 'a')
        );
    }

    /**
     * Tests the read() method.
     * @param string|null $expected The method expected to be called.
     * @param string $code The code to be used.
     * @dataProvider providerRead
     */
    public function testRead($expected, $code) {
        $methods = array('readSlash', 'readDirective', 'readString', 'readWhitespace');

        $compressor = $this->getMock('ManiaScript\Compressor', $methods);
        foreach ($methods as $method) {
            if ($method === $expected) {
                $matcher = $this->once();
            } else {
                $matcher = $this->never();
            }
            $compressor->expects($matcher)
                       ->method($method);
        }

        $this->injectProperty($compressor, 'code', $code)
             ->injectProperty($compressor, 'codeLength', strlen($code));

        $reflectedMethod = new ReflectionMethod($compressor, 'read');
        $reflectedMethod->setAccessible(true);
        $reflectedMethod->invoke($compressor);

        $this->assertPropertyEquals(1, $compressor, 'currentPosition');
    }

    /**
     * Data provider for the readSlash test.
     * return array The data.
     */
    public function providerReadSlash() {
        return array(
            array("\n", '//', 0),
            array("\n", 'abc//', 3),
            array('*/', '/*', 0),
            array('*/', 'abc/*', 3),
            array(null, '/', 0),
            array(null, 'abc/', 3),
            array(null, '/def', 0),
            array(null, 'abc/def', 3)
        );
    }

    /**
     * Tests the readSlash() method.
     * @param string|null $expected The parameter expected in skipUntil(), or null if not called.
     * @param string $code The code to be used.
     * @param int $currentPosition The current position in the code.
     * @dataProvider providerReadSlash
     */
    public function testReadSlash($expected, $code, $currentPosition) {
        $compressor = $this->getMock('ManiaScript\Compressor', array('skipUntil'));

        $this->injectProperty($compressor, 'code', $code)
             ->injectProperty($compressor, 'codeLength', strlen($code))
             ->injectProperty($compressor, 'currentPosition', $currentPosition);

        if (is_null($expected)) {
            $compressor->expects($this->never())
                       ->method('skipUntil');
        } else {
            $compressor->expects($this->once())
                       ->method('skipUntil')
                       ->with($expected);
        }

        $reflectedMethod = new ReflectionMethod($compressor, 'readSlash');
        $reflectedMethod->setAccessible(true);
        $reflectedMethod->invoke($compressor);
    }

    /**
     * Tests the readDirective() method.
     */
    public function testReadDirective() {
        $compressor = $this->getMock('ManiaScript\Compressor', array('copyUntil'));
        $compressor->expects($this->once())
                   ->method('copyUntil')
                   ->with("\n");

        $reflectedMethod = new ReflectionMethod($compressor, 'readDirective');
        $reflectedMethod->setAccessible(true);
        $reflectedMethod->invoke($compressor);
    }

    /**
     * Data provider for the readString test.
     * @return array The data.
     */
    public function providerReadString() {
        return array(
            array(2, 'abc""', '""', 0, 'abc'),
            array(5, 'abc"def"', '"def"', 0, 'abc'),
            array(8, 'abc"def"', 'abc"def"ghi', 3, 'abc'),
            array(10, '"abc\"def"', '"abc\"def"ghi', 0, '')
        );
    }

    /**
     * Tests the readString() method.
     * @param string $expectedPosition The expected position.
     * @param string $expectedCode The expected compressed code.
     * @param string $code The code to be used.
     * @param int $currentPosition The current position.
     * @param string $compressedCode The compressed code.
     * @dataProvider providerReadString
     */
    public function testReadString($expectedPosition, $expectedCode, $code, $currentPosition, $compressedCode) {
        $compressor = new Compressor();
        $this->injectProperty($compressor, 'code', $code)
             ->injectProperty($compressor, 'codeLength', strlen($code))
             ->injectProperty($compressor, 'currentPosition', $currentPosition)
             ->injectProperty($compressor, 'compressedCode', $compressedCode);

        $reflectedMethod = new ReflectionMethod($compressor, 'readString');
        $reflectedMethod->setAccessible(true);
        $reflectedMethod->invoke($compressor);

        $this->assertPropertyEquals($expectedPosition, $compressor, 'currentPosition');
        $this->assertPropertyEquals($expectedCode, $compressor, 'compressedCode');
    }

    /**
     * Data porovider for the readWhitespace test.
     * @return array The data.
     */
    public function providerReadWhitespace() {
        return array(
            array('abc ', 'abc', true),
            array('abc', 'abc', false)
        );
    }

    /**
     * Tests the readWhitespace() method.
     * @param string $expected The expected compressed code.
     * @param string $compressedCode The compressed code.
     * @param boolean $isWhitespaceRequired The result of the isWhitespaceRequired() call.
     * @dataProvider providerReadWhitespace
     */
    public function testReadWhitespace($expected, $compressedCode, $isWhitespaceRequired) {
        $compressor = $this->getMock('ManiaScript\Compressor', array('isWhitespaceRequired', 'skipWhitespace'));
        $compressor->expects($this->any())
                   ->method('isWhitespaceRequired')
                   ->will($this->returnValue($isWhitespaceRequired));
        $compressor->expects($this->once())
                   ->method('skipWhitespace');
        $this->injectProperty($compressor, 'compressedCode', $compressedCode);

        $reflectedMethod = new ReflectionMethod($compressor, 'readWhitespace');
        $reflectedMethod->setAccessible(true);
        $reflectedMethod->invoke($compressor);
        $this->assertPropertyEquals($expected, $compressor, 'compressedCode');
    }

    /**
     * Data provider for the copyUntil test.
     * @return array The data.
     */
    public function providerCopyUntil() {
        return array(
            array('abc', 'abcdef', 0, 'c', 2),
            array('abcdef', 'abcdef', 0, 'g', 6),
            array('cd', 'abcdef', 2, 'd', 3)
        );
    }

    /**
     * Tests the copyUntil() method.
     * @param string $expected The expected compressed code.
     * @param string $code The uncompressed code.
     * @param int $currentPosition The current position.
     * @param string $findString The string to copy until.
     * @param int $findResult The result of the find() method call.
     * @dataProvider providerCopyUntil
     */
    public function testCopyUntil($expected, $code, $currentPosition, $findString, $findResult) {
        $compressor = $this->getMock('ManiaScript\Compressor', array('find'));
        $compressor->expects($this->any())
                   ->method('find')
                   ->with($findString)
                   ->will($this->returnValue($findResult));
        $this->injectProperty($compressor, 'code', $code)
             ->injectProperty($compressor, 'currentPosition', $currentPosition);

        $reflectedMethod = new ReflectionMethod($compressor, 'copyUntil');
        $reflectedMethod->setAccessible(true);
        $reflectedMethod->invoke($compressor, $findString);
        $this->assertPropertyEquals($expected, $compressor, 'compressedCode');
    }

    /**
     * Data provider for the skipUntil test.
     * @return array The data.
     */
    public function providerSkipUntil() {
        return array(
            array(42, 42, ''),
            array(42, 36, 'abcdef')
        );
    }

    /**
     * Tests the skipUntil() method.
     * @param int The expected position.
     * @param int The position returned by find().
     * @param string $string The string to use in finde().
     * @dataProvider providerSkipUntil
     */
    public function testSkipUntil($expected, $find, $string) {
        $compressor = $this->getMock('ManiaScript\Compressor', array('find'));
        $compressor->expects($this->any())
                   ->method('find')
                   ->with($string)
                   ->will($this->returnValue($find));

        $reflectedMethod = new ReflectionMethod($compressor, 'skipUntil');
        $reflectedMethod->setAccessible(true);
        $reflectedMethod->invoke($compressor, $string);

        $this->assertPropertyEquals($expected, $compressor, 'currentPosition');
    }

    /**
     * Data provider for the skipWhitespace test.
     * @return array The data.
     */
    public function providerSkipWhitespace() {
        return array(
            array(1, ' ', 0),
            array(6, '      ', 0),
            array(0, 'abc', 0),
            array(5, 'abc  def  ghi', 3),
            array(8, "abc \n\t\r def", 3)
        );
    }

    /**
     * Tests the skipWhitespace() method.
     * @param int $expected The expected position.
     * @param string $code The code to be used.
     * @param int $currentPosition The current position.
     * @dataProvider providerSkipWhitespace
     */
    public function testSkipWhitespace($expected, $code, $currentPosition) {
        $compressor = new Compressor();

        $this->injectProperty($compressor, 'code', $code)
             ->injectProperty($compressor, 'codeLength', strlen($code))
             ->injectProperty($compressor, 'currentPosition', $currentPosition);

        $reflectedMethod = new ReflectionMethod($compressor, 'skipWhitespace');
        $reflectedMethod->setAccessible(true);
        $reflectedMethod->invoke($compressor);

        $this->assertPropertyEquals($expected, $compressor, 'currentPosition');
    }

    /**
     * Data provider for the find test.
     * @return array The data.
     */
    public function providerFind() {
        return array(
            array(0, 'abcdef', 'a', 0),
            array(6, 'abcdef', 'a', 1),
            array(3, 'abcdef', 'd', 0),
            array(6, 'abcdef', 'd', 4),
            array(6, 'abcdef', 'g', 0),
            array(6, 'abcdef', 'a', 6)
        );
    }

    /**
     * Tests the find() method.
     * @param int $expected The expected position.
     * @param string $code The code to be used.
     * @param string $string The char to find.
     * @param int $currentPosition The current position in the code.
     * @dataProvider providerFind
     */
    public function testFind($expected, $code, $string, $currentPosition) {
        $compressor = new Compressor();
        $this->injectProperty($compressor, 'code', $code)
             ->injectProperty($compressor, 'codeLength', strlen($code))
             ->injectProperty($compressor, 'currentPosition', $currentPosition);

        $reflectedMethod = new ReflectionMethod($compressor, 'find');
        $reflectedMethod->setAccessible(true);
        $result = $reflectedMethod->invoke($compressor, $string);
        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider for the isWhitespaceRequired test.
     * @return array The data.
     */
    public function providerIsWhitespaceRequired() {
        return array(
            array(true, 'b', 'a'),
            array(false, '', 'a'),
            array(false, '(', 'a'),
            array(false, 'a', ' '),
            array(false, 'a', '}'),
            array(true, '}', '}}'), // Avoid triple closing curly bracket bug
            array(false, 'a', '}}'),
            array(false, '}', 'abc}')
        );
    }

    /**
     * Tests the isWhitespaceRequired() method.
     * @param boolean $expected The expected result.
     * @param string $code The code to be used.
     * @param string $compressedCode The compressed code.
     * @dataProvider providerIsWhitespaceRequired
     */
    public function testIsWhitespaceRequired($expected, $code, $compressedCode) {
        $compressor = new Compressor();
        $this->injectProperty($compressor, 'code', $code)
             ->injectProperty($compressor, 'codeLength', strlen($code))
             ->injectProperty($compressor, 'compressedCode', $compressedCode);

        $reflectedMethod = new ReflectionMethod($compressor, 'isWhitespaceRequired');
        $reflectedMethod->setAccessible(true);
        $result = $reflectedMethod->invoke($compressor);
        $this->assertEquals($expected, $result);
    }
}
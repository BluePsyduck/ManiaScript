<?php

namespace ManiaScript;

/**
 * This class compresses the ManiaScript without chaning its logic, mostly by erasing all not required whitespaces
 * and comments.
 *
 * Example usage:
 * <code><?php
 * $compressor = new \ManiaScript\Compressor();
 * $compressedCode = $compressor->setCode($code)
 *                              ->compress()
 *                              ->getCompressedCode();
 * ?></code>
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Compressor {

    /**
     * The code to be compressed.
     * @var string
     */
    protected $code = '';

    /**
     * The compressed code.
     * @var string
     */
    protected $compressedCode = '';

    /**
     * The length of the uncompressed code.
     * @var int
     */
    protected $codeLength = 0;

    /**
     * The current position in the uncompressed code.
     * @var int
     */
    protected $currentPosition;

    /**
     * The characters considered as whitespace.
     * @var array
     */
    protected $whitespaces = array("\n", "\r", "\t", ' ');

    /**
     * Characters after which all whitespaces can be skipped.
     * @var array
     */
    protected $ignoreFollowingWhitespace = array(
        ' ', "\n", "\t", "\r",
        '[', '(', '{', '}', ')', ']',
        ',', ':', ';', '"',
        '=', '<', '>', '|', '&', '-', '+', '*', '/', '%', '^', '!'
    );

    /**
     * Sets the code to be compressed.
     * @param string $code The code.
     * @return \ManiaScript\Compressor Implementing fluent interface.
     */
    public function setCode($code) {
        $this->code = trim($code);
        $this->codeLength = strlen($this->code);
        return $this;
    }

    /**
     * Compresses the code.
     * @return \ManiaScript\Compressor Implementing fluent interface.
     */
    public function compress() {
        $this->currentPosition = 0;
        $this->compressedCode = '';
        $this->read();
        return $this;
    }

    /**
     * Returns the compressed code.
     * @return string The compressed code.
     */
    public function getCompressedCode() {
        return $this->compressedCode;
    }

    protected function read() {
        while ($this->currentPosition < $this->codeLength) {
            $startPosition = $this->currentPosition;
            $currentChar = $this->code{$this->currentPosition};
            if (in_array($currentChar, $this->whitespaces)) {
                $this->readWhitespace();
            } else {
                switch ($currentChar) {
                    case '/': {
                        $this->readSlash();
                        break;
                    }
                    case '#': {
                        $this->readDirective();
                        break;
                    }
                    case '"': {
                        $this->readString();
                        break;
                    }
                }
            }

            // Force any progress in the loop
            if ($this->currentPosition === $startPosition) {
                $this->compressedCode .= $currentChar;
                ++$this->currentPosition;
            }
        }
    }

    /**
     * Reads and handles a slash in the code.
     */
    protected function readSlash() {
        if ($this->currentPosition + 1 < $this->codeLength) {
            $nextChar = $this->code{$this->currentPosition + 1};
            if ($nextChar === '/') {
                $this->skipUntil("\n");
            } elseif ($nextChar === '*') {
                $this->skipUntil('*/');
            }
        }
    }

    /**
     * Reads and handles a directive.
     */
    protected function readDirective() {
        $this->copyUntil("\n");
    }

    /**
     * Reads and handles a quoted string.
     */
    protected function readString() {
        $startPosition = $this->currentPosition;
        ++$this->currentPosition;

        while ($this->currentPosition < $this->codeLength) {
            $currentChar = $this->code{$this->currentPosition};
            if ($currentChar === '\\') {
                $this->currentPosition += 2;
            } elseif ($currentChar === '"') {
                break;
            } else {
                ++$this->currentPosition;
            }
        }

        ++$this->currentPosition;
        $this->compressedCode .= substr($this->code, $startPosition, $this->currentPosition - $startPosition);
    }

    /**
     * Reads and handles a whitespace character, skipping all following whitespaces.
     */
    protected function readWhitespace() {
        $this->skipWhitespace();
        if ($this->isWhitespaceRequired()) {
            $this->compressedCode .= ' ';
        }
    }

    /**
     * Copies some code to the compressed code without modification. The current position will be after the specified
     * string.
     * @param string $string The string until which the code should be copied.
     */
    protected function copyUntil($string) {
        $newPosition = $this->find($string) + strlen($string);
        $this->compressedCode .= substr($this->code, $this->currentPosition, $newPosition - $this->currentPosition);
        $this->currentPosition = $newPosition;
    }

    /**
     * Skips the code until the specified string. The new position will be after the string.
     * @param string $string The string.
     */
    protected function skipUntil($string) {
        $this->currentPosition = $this->find($string) + strlen($string);
    }

    /**
     * Skips any whitespace characters.
     */
    protected function skipWhitespace() {
        while ($this->currentPosition < $this->codeLength
            && in_array($this->code{$this->currentPosition}, $this->whitespaces)
        ) {
            ++$this->currentPosition;
        }
    }

    /**
     * Finds the next position of the specified string.
     * @param string $string The string to find.
     * @return int The position. If not found, it will be the end of the code.
     */
    protected function find($string) {
        $position = strpos($this->code, $string, $this->currentPosition);
        if ($position === false) {
            $position = $this->codeLength;
        }
        return $position;
    }

    /**
     * Checks whether a whitespace is required at the current position of the compressed code.
     * @return boolean True if a whitespace is required, false if it can be omited.
     */
    protected function isWhitespaceRequired() {
        $result = false;
        if (!empty($this->compressedCode) && $this->currentPosition < $this->codeLength) {
            $compressedLength = strlen($this->compressedCode);
            $lastChar = $this->compressedCode{$compressedLength - 1};
            $nextChar = $this->code{$this->currentPosition};
            if ($lastChar === '}' && $nextChar === '}' && $compressedLength >= 2) {
                $secondLastChar = $this->compressedCode{$compressedLength - 2};
                $result = ($secondLastChar === '}');
            } else {
                $result = !in_array($lastChar, $this->ignoreFollowingWhitespace)
                    && !in_array($nextChar, $this->ignoreFollowingWhitespace);
            }
        }
        return $result;
    }
}
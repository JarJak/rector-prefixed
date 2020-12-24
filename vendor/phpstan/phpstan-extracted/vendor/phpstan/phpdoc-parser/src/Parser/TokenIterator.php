<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan\PhpDocParser\Parser;

use _PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer;
class TokenIterator
{
    /** @var mixed[][] */
    private $tokens;
    /** @var int */
    private $index;
    /** @var int[] */
    private $savePoints = [];
    public function __construct(array $tokens, int $index = 0)
    {
        $this->tokens = $tokens;
        $this->index = $index;
        if ($this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TYPE_OFFSET] !== \_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_HORIZONTAL_WS) {
            return;
        }
        $this->index++;
    }
    public function currentTokenValue() : string
    {
        return $this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::VALUE_OFFSET];
    }
    public function currentTokenType() : int
    {
        return $this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TYPE_OFFSET];
    }
    public function currentTokenOffset() : int
    {
        $offset = 0;
        for ($i = 0; $i < $this->index; $i++) {
            $offset += \strlen($this->tokens[$i][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::VALUE_OFFSET]);
        }
        return $offset;
    }
    public function isCurrentTokenValue(string $tokenValue) : bool
    {
        return $this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::VALUE_OFFSET] === $tokenValue;
    }
    public function isCurrentTokenType(int $tokenType) : bool
    {
        return $this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TYPE_OFFSET] === $tokenType;
    }
    public function isPrecededByHorizontalWhitespace() : bool
    {
        return ($this->tokens[$this->index - 1][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TYPE_OFFSET] ?? -1) === \_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_HORIZONTAL_WS;
    }
    /**
     * @param  int $tokenType
     * @throws \PHPStan\PhpDocParser\Parser\ParserException
     */
    public function consumeTokenType(int $tokenType) : void
    {
        if ($this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TYPE_OFFSET] !== $tokenType) {
            $this->throwError($tokenType);
        }
        $this->index++;
        if (($this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TYPE_OFFSET] ?? -1) !== \_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_HORIZONTAL_WS) {
            return;
        }
        $this->index++;
    }
    public function tryConsumeTokenValue(string $tokenValue) : bool
    {
        if ($this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::VALUE_OFFSET] !== $tokenValue) {
            return \false;
        }
        $this->index++;
        if ($this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TYPE_OFFSET] === \_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_HORIZONTAL_WS) {
            $this->index++;
        }
        return \true;
    }
    public function tryConsumeTokenType(int $tokenType) : bool
    {
        if ($this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TYPE_OFFSET] !== $tokenType) {
            return \false;
        }
        $this->index++;
        if ($this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TYPE_OFFSET] === \_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_HORIZONTAL_WS) {
            $this->index++;
        }
        return \true;
    }
    public function getSkippedHorizontalWhiteSpaceIfAny() : string
    {
        if ($this->tokens[$this->index - 1][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TYPE_OFFSET] === \_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_HORIZONTAL_WS) {
            return $this->tokens[$this->index - 1][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::VALUE_OFFSET];
        }
        return '';
    }
    public function joinUntil(int ...$tokenType) : string
    {
        $s = '';
        while (!\in_array($this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TYPE_OFFSET], $tokenType, \true)) {
            $s .= $this->tokens[$this->index++][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::VALUE_OFFSET];
        }
        return $s;
    }
    public function next() : void
    {
        $this->index++;
        if ($this->tokens[$this->index][\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TYPE_OFFSET] !== \_PhpScopere8e811afab72\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_HORIZONTAL_WS) {
            return;
        }
        $this->index++;
    }
    public function pushSavePoint() : void
    {
        $this->savePoints[] = $this->index;
    }
    public function dropSavePoint() : void
    {
        \array_pop($this->savePoints);
    }
    public function rollback() : void
    {
        $index = \array_pop($this->savePoints);
        \assert($index !== null);
        $this->index = $index;
    }
    /**
     * @param  int $expectedTokenType
     * @throws \PHPStan\PhpDocParser\Parser\ParserException
     */
    private function throwError(int $expectedTokenType) : void
    {
        throw new \_PhpScopere8e811afab72\PHPStan\PhpDocParser\Parser\ParserException($this->currentTokenValue(), $this->currentTokenType(), $this->currentTokenOffset(), $expectedTokenType);
    }
}
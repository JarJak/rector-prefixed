<?php

declare (strict_types=1);
namespace _PhpScoperabd03f0baf05\Roave\SignatureTest;

use _PhpScoperabd03f0baf05\PHPUnit\Framework\TestCase;
use _PhpScoperabd03f0baf05\Roave\Signature\Encoder\Base64Encoder;
use _PhpScoperabd03f0baf05\Roave\Signature\Encoder\EncoderInterface;
use _PhpScoperabd03f0baf05\Roave\Signature\FileContentChecker;
/**
 * @covers \Roave\Signature\FileContentChecker
 */
final class FileContentCheckerTest extends \_PhpScoperabd03f0baf05\PHPUnit\Framework\TestCase
{
    /**
     * @var EncoderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $encoder;
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->encoder = $this->createMock(\_PhpScoperabd03f0baf05\Roave\Signature\Encoder\EncoderInterface::class);
    }
    public function testShouldCheckClassFileContent()
    {
        $classFilePath = __DIR__ . '/../../fixture/UserClassSignedByFileContent.php';
        self::assertFileExists($classFilePath);
        $checker = new \_PhpScoperabd03f0baf05\Roave\Signature\FileContentChecker(new \_PhpScoperabd03f0baf05\Roave\Signature\Encoder\Base64Encoder());
        $checker->check(\file_get_contents($classFilePath));
    }
    public function testShouldReturnFalseIfSignatureDoesNotMatch()
    {
        $classFilePath = __DIR__ . '/../../fixture/UserClassSignedByFileContent.php';
        self::assertFileExists($classFilePath);
        $expectedSignature = 'YToxOntpOjA7czoxNDE6Ijw/cGhwCgpuYW1lc3BhY2UgU2lnbmF0dXJlVGVzdEZpeHR1cmU7' . 'CgpjbGFzcyBVc2VyQ2xhc3NTaWduZWRCeUZpbGVDb250ZW50CnsKICAgIHB1YmxpYyAkbmFtZTsKCiAgICBwcm90ZW' . 'N0ZWQgJHN1cm5hbWU7CgogICAgcHJpdmF0ZSAkYWdlOwp9CiI7fQ==';
        $this->encoder->expects(self::once())->method('verify')->with(\str_replace('/** Roave/Signature: ' . $expectedSignature . ' */' . "\n", '', \file_get_contents($classFilePath)), $expectedSignature);
        $checker = new \_PhpScoperabd03f0baf05\Roave\Signature\FileContentChecker($this->encoder);
        self::assertFalse($checker->check(\file_get_contents($classFilePath)));
    }
    public function testShouldReturnFalseIfClassIsNotSigned()
    {
        $classFilePath = __DIR__ . '/../../fixture/UserClass.php';
        self::assertFileExists($classFilePath);
        $checker = new \_PhpScoperabd03f0baf05\Roave\Signature\FileContentChecker($this->encoder);
        self::assertFalse($checker->check(\file_get_contents($classFilePath)));
    }
}

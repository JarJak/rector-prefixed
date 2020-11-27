<?php

namespace _PhpScoper006a73f0e455;

use _PhpScoper006a73f0e455\React\Stream\WritableResourceStream;
use _PhpScoper006a73f0e455\Clue\React\NDJson\Encoder;
class EncoderTest extends \_PhpScoper006a73f0e455\TestCase
{
    private $output;
    private $encoder;
    public function setUp()
    {
        $stream = \fopen('php://temp', 'r+');
        $loop = $this->getMockBuilder('_PhpScoper006a73f0e455\\React\\EventLoop\\LoopInterface')->getMock();
        $this->output = new \_PhpScoper006a73f0e455\React\Stream\WritableResourceStream($stream, $loop);
        $this->encoder = new \_PhpScoper006a73f0e455\Clue\React\NDJson\Encoder($this->output);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testPrettyPrintDoesNotMakeSenseForNDJson()
    {
        if (!\defined('JSON_PRETTY_PRINT')) {
            $this->markTestSkipped('Const JSON_PRETTY_PRINT only available in PHP 5.4+');
        }
        $this->encoder = new \_PhpScoper006a73f0e455\Clue\React\NDJson\Encoder($this->output, \JSON_PRETTY_PRINT);
    }
    public function testWriteString()
    {
        $this->output = $this->getMockBuilder('_PhpScoper006a73f0e455\\React\\Stream\\WritableStreamInterface')->getMock();
        $this->output->expects($this->once())->method('isWritable')->willReturn(\true);
        $this->encoder = new \_PhpScoper006a73f0e455\Clue\React\NDJson\Encoder($this->output);
        $this->output->expects($this->once())->method('write')->with("\"hello\"\n")->willReturn(\true);
        $ret = $this->encoder->write('hello');
        $this->assertTrue($ret);
    }
    public function testWriteNull()
    {
        $this->output = $this->getMockBuilder('_PhpScoper006a73f0e455\\React\\Stream\\WritableStreamInterface')->getMock();
        $this->output->expects($this->once())->method('isWritable')->willReturn(\true);
        $this->encoder = new \_PhpScoper006a73f0e455\Clue\React\NDJson\Encoder($this->output);
        $this->output->expects($this->once())->method('write')->with("null\n");
        $this->encoder->write(null);
    }
    public function testWriteInfiniteWillEmitErrorAndClose()
    {
        $this->output = $this->getMockBuilder('_PhpScoper006a73f0e455\\React\\Stream\\WritableStreamInterface')->getMock();
        $this->output->expects($this->once())->method('isWritable')->willReturn(\true);
        $this->encoder = new \_PhpScoper006a73f0e455\Clue\React\NDJson\Encoder($this->output);
        $this->output->expects($this->never())->method('write');
        $error = null;
        $this->encoder->on('error', function ($e) use(&$error) {
            $error = $e;
        });
        $this->encoder->on('error', $this->expectCallableOnce());
        $this->encoder->on('close', $this->expectCallableOnce());
        $ret = $this->encoder->write(\INF);
        $this->assertFalse($ret);
        $this->assertFalse($this->encoder->isWritable());
        $this->assertInstanceOf('RuntimeException', $error);
        if (\PHP_VERSION_ID >= 50500) {
            // PHP 5.5+ reports error with proper code
            $this->assertContains('Inf and NaN cannot be JSON encoded', $error->getMessage());
            $this->assertEquals(\JSON_ERROR_INF_OR_NAN, $error->getCode());
        } else {
            // PHP < 5.5 reports error message without code
            $this->assertContains('double INF does not conform to the JSON spec', $error->getMessage());
            $this->assertEquals(0, $error->getCode());
        }
    }
    public function testWriteInvalidUtf8WillEmitErrorAndClose()
    {
        $this->output = $this->getMockBuilder('_PhpScoper006a73f0e455\\React\\Stream\\WritableStreamInterface')->getMock();
        $this->output->expects($this->once())->method('isWritable')->willReturn(\true);
        $this->encoder = new \_PhpScoper006a73f0e455\Clue\React\NDJson\Encoder($this->output);
        $this->output->expects($this->never())->method('write');
        $error = null;
        $this->encoder->on('error', function ($e) use(&$error) {
            $error = $e;
        });
        $this->encoder->on('error', $this->expectCallableOnce());
        $this->encoder->on('close', $this->expectCallableOnce());
        $ret = $this->encoder->write("�");
        $this->assertFalse($ret);
        $this->assertFalse($this->encoder->isWritable());
        $this->assertInstanceOf('RuntimeException', $error);
        if (\PHP_VERSION_ID >= 50500) {
            // PHP 5.5+ reports error with proper code
            $this->assertContains('Malformed UTF-8 characters, possibly incorrectly encoded', $error->getMessage());
            $this->assertEquals(\JSON_ERROR_UTF8, $error->getCode());
        } elseif (\PHP_VERSION_ID >= 50303) {
            // PHP 5.3.3+ reports error with proper code (const JSON_ERROR_UTF8 added in PHP 5.3.3)
            $this->assertContains('Malformed UTF-8 characters, possibly incorrectly encoded', $error->getMessage());
            $this->assertEquals(\JSON_ERROR_UTF8, $error->getCode());
        }
    }
    public function testWriteInfiniteWillEmitErrorAndCloseAlsoWhenCreatedWithThrowOnError()
    {
        if (!\defined('JSON_THROW_ON_ERROR')) {
            $this->markTestSkipped('Const JSON_THROW_ON_ERROR only available in PHP 7.3+');
        }
        $this->output = $this->getMockBuilder('_PhpScoper006a73f0e455\\React\\Stream\\WritableStreamInterface')->getMock();
        $this->output->expects($this->once())->method('isWritable')->willReturn(\true);
        $this->encoder = new \_PhpScoper006a73f0e455\Clue\React\NDJson\Encoder($this->output, \JSON_THROW_ON_ERROR);
        $this->output->expects($this->never())->method('write');
        $this->encoder->on('error', $this->expectCallableOnce());
        $this->encoder->on('close', $this->expectCallableOnce());
        $ret = $this->encoder->write(\INF);
        $this->assertFalse($ret);
        $this->assertFalse($this->encoder->isWritable());
    }
    public function testEndWithoutDataWillEndOutputWithoutData()
    {
        $this->output = $this->getMockBuilder('_PhpScoper006a73f0e455\\React\\Stream\\WritableStreamInterface')->getMock();
        $this->output->expects($this->once())->method('isWritable')->willReturn(\true);
        $this->encoder = new \_PhpScoper006a73f0e455\Clue\React\NDJson\Encoder($this->output);
        $this->output->expects($this->never())->method('write');
        $this->output->expects($this->once())->method('end')->with($this->equalTo(null));
        $this->encoder->end();
    }
    public function testEndWithDataWillForwardDataAndEndOutputWithoutData()
    {
        $this->output = $this->getMockBuilder('_PhpScoper006a73f0e455\\React\\Stream\\WritableStreamInterface')->getMock();
        $this->output->expects($this->once())->method('isWritable')->willReturn(\true);
        $this->encoder = new \_PhpScoper006a73f0e455\Clue\React\NDJson\Encoder($this->output);
        $this->output->expects($this->once())->method('write')->with($this->equalTo("true\n"));
        $this->output->expects($this->once())->method('end')->with($this->equalTo(null));
        $this->encoder->end(\true);
    }
    public function testClosingEncoderClosesOutput()
    {
        $this->encoder->on('close', $this->expectCallableOnce());
        $this->output->on('close', $this->expectCallableOnce());
        $this->encoder->close();
    }
    public function testClosingOutputClosesEncoder()
    {
        $this->encoder->on('close', $this->expectCallableOnce());
        $this->output->on('close', $this->expectCallableOnce());
        $this->output->close();
    }
    public function testPassingClosedStreamToEncoderWillCloseImmediately()
    {
        $this->output = $this->getMockBuilder('_PhpScoper006a73f0e455\\React\\Stream\\WritableStreamInterface')->getMock();
        $this->output->expects($this->once())->method('isWritable')->willReturn(\false);
        $this->encoder = new \_PhpScoper006a73f0e455\Clue\React\NDJson\Encoder($this->output);
        $this->assertFalse($this->encoder->isWritable());
    }
    public function testWritingToClosedStreamWillNotForwardData()
    {
        $this->output = $this->getMockBuilder('_PhpScoper006a73f0e455\\React\\Stream\\WritableStreamInterface')->getMock();
        $this->output->expects($this->once())->method('isWritable')->willReturn(\false);
        $this->encoder = new \_PhpScoper006a73f0e455\Clue\React\NDJson\Encoder($this->output);
        $this->output->expects($this->never())->method('write');
        $this->encoder->write("discarded");
    }
    public function testErrorEventWillForwardAndClose()
    {
        $this->encoder->on('error', $this->expectCallableOnce());
        $this->encoder->on('close', $this->expectCallableOnce());
        $this->output->emit('error', array(new \RuntimeException()));
        $this->assertFalse($this->output->isWritable());
    }
    public function testDrainEventWillForward()
    {
        $this->encoder->on('drain', $this->expectCallableOnce());
        $this->output->emit('drain');
    }
}
\class_alias('_PhpScoper006a73f0e455\\EncoderTest', 'EncoderTest', \false);
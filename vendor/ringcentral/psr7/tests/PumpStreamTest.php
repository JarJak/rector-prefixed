<?php

namespace _PhpScoper26e51eeacccf\RingCentral\Tests\Psr7;

use _PhpScoper26e51eeacccf\RingCentral\Psr7\LimitStream;
use _PhpScoper26e51eeacccf\RingCentral\Psr7\PumpStream;
use _PhpScoper26e51eeacccf\RingCentral\Psr7;
class PumpStreamTest extends \_PhpScoper26e51eeacccf\PHPUnit_Framework_TestCase
{
    public function testHasMetadataAndSize()
    {
        $p = new \_PhpScoper26e51eeacccf\RingCentral\Psr7\PumpStream(function () {
        }, array('metadata' => array('foo' => 'bar'), 'size' => 100));
        $this->assertEquals('bar', $p->getMetadata('foo'));
        $this->assertEquals(array('foo' => 'bar'), $p->getMetadata());
        $this->assertEquals(100, $p->getSize());
    }
    public function testCanReadFromCallable()
    {
        $p = \_PhpScoper26e51eeacccf\RingCentral\Psr7\stream_for(function ($size) {
            return 'a';
        });
        $this->assertEquals('a', $p->read(1));
        $this->assertEquals(1, $p->tell());
        $this->assertEquals('aaaaa', $p->read(5));
        $this->assertEquals(6, $p->tell());
    }
    public function testStoresExcessDataInBuffer()
    {
        $called = array();
        $p = \_PhpScoper26e51eeacccf\RingCentral\Psr7\stream_for(function ($size) use(&$called) {
            $called[] = $size;
            return 'abcdef';
        });
        $this->assertEquals('a', $p->read(1));
        $this->assertEquals('b', $p->read(1));
        $this->assertEquals('cdef', $p->read(4));
        $this->assertEquals('abcdefabc', $p->read(9));
        $this->assertEquals(array(1, 9, 3), $called);
    }
    public function testInifiniteStreamWrappedInLimitStream()
    {
        $p = \_PhpScoper26e51eeacccf\RingCentral\Psr7\stream_for(function () {
            return 'a';
        });
        $s = new \_PhpScoper26e51eeacccf\RingCentral\Psr7\LimitStream($p, 5);
        $this->assertEquals('aaaaa', (string) $s);
    }
    public function testDescribesCapabilities()
    {
        $p = \_PhpScoper26e51eeacccf\RingCentral\Psr7\stream_for(function () {
        });
        $this->assertTrue($p->isReadable());
        $this->assertFalse($p->isSeekable());
        $this->assertFalse($p->isWritable());
        $this->assertNull($p->getSize());
        $this->assertEquals('', $p->getContents());
        $this->assertEquals('', (string) $p);
        $p->close();
        $this->assertEquals('', $p->read(10));
        $this->assertTrue($p->eof());
        try {
            $this->assertFalse($p->write('aa'));
            $this->fail();
        } catch (\RuntimeException $e) {
        }
    }
}

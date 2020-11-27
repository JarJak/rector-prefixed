<?php

namespace _PhpScoper006a73f0e455\React\Tests\Promise\Stream;

use _PhpScoper006a73f0e455\React\Promise\Stream;
use _PhpScoper006a73f0e455\React\Stream\ThroughStream;
class AllTest extends \_PhpScoper006a73f0e455\React\Tests\Promise\Stream\TestCase
{
    public function testClosedStreamResolvesWithEmptyBuffer()
    {
        $stream = new \_PhpScoper006a73f0e455\React\Stream\ThroughStream();
        $stream->close();
        $promise = \_PhpScoper006a73f0e455\React\Promise\Stream\all($stream);
        $this->expectPromiseResolveWith(array(), $promise);
    }
    public function testClosedWritableStreamResolvesWithEmptyBuffer()
    {
        $stream = new \_PhpScoper006a73f0e455\React\Stream\ThroughStream();
        $stream->close();
        $promise = \_PhpScoper006a73f0e455\React\Promise\Stream\all($stream);
        $this->expectPromiseResolveWith(array(), $promise);
    }
    public function testPendingStreamWillNotResolve()
    {
        $stream = new \_PhpScoper006a73f0e455\React\Stream\ThroughStream();
        $promise = \_PhpScoper006a73f0e455\React\Promise\Stream\all($stream);
        $promise->then($this->expectCallableNever(), $this->expectCallableNever());
    }
    public function testClosingStreamResolvesWithEmptyBuffer()
    {
        $stream = new \_PhpScoper006a73f0e455\React\Stream\ThroughStream();
        $promise = \_PhpScoper006a73f0e455\React\Promise\Stream\all($stream);
        $stream->close();
        $this->expectPromiseResolveWith(array(), $promise);
    }
    public function testClosingWritableStreamResolvesWithEmptyBuffer()
    {
        $stream = new \_PhpScoper006a73f0e455\React\Stream\ThroughStream();
        $promise = \_PhpScoper006a73f0e455\React\Promise\Stream\all($stream);
        $stream->close();
        $this->expectPromiseResolveWith(array(), $promise);
    }
    public function testEmittingDataOnStreamResolvesWithArrayOfData()
    {
        $stream = new \_PhpScoper006a73f0e455\React\Stream\ThroughStream();
        $promise = \_PhpScoper006a73f0e455\React\Promise\Stream\all($stream);
        $stream->emit('data', array('hello', $stream));
        $stream->emit('data', array('world', $stream));
        $stream->close();
        $this->expectPromiseResolveWith(array('hello', 'world'), $promise);
    }
    public function testEmittingCustomEventOnStreamResolvesWithArrayOfCustomEventData()
    {
        $stream = new \_PhpScoper006a73f0e455\React\Stream\ThroughStream();
        $promise = \_PhpScoper006a73f0e455\React\Promise\Stream\all($stream, 'a');
        $stream->emit('a', array('hello'));
        $stream->emit('b', array('ignored'));
        $stream->emit('a');
        $stream->close();
        $this->expectPromiseResolveWith(array('hello', null), $promise);
    }
    public function testEmittingErrorOnStreamRejects()
    {
        $stream = new \_PhpScoper006a73f0e455\React\Stream\ThroughStream();
        $promise = \_PhpScoper006a73f0e455\React\Promise\Stream\all($stream);
        $stream->emit('error', array(new \RuntimeException('test')));
        $this->expectPromiseReject($promise);
    }
    public function testEmittingErrorAfterEmittingDataOnStreamRejects()
    {
        $stream = new \_PhpScoper006a73f0e455\React\Stream\ThroughStream();
        $promise = \_PhpScoper006a73f0e455\React\Promise\Stream\all($stream);
        $stream->emit('data', array('hello', $stream));
        $stream->emit('error', array(new \RuntimeException('test')));
        $this->expectPromiseReject($promise);
    }
    public function testCancelPendingStreamWillReject()
    {
        $stream = new \_PhpScoper006a73f0e455\React\Stream\ThroughStream();
        $promise = \_PhpScoper006a73f0e455\React\Promise\Stream\all($stream);
        $promise->cancel();
        $this->expectPromiseReject($promise);
    }
}
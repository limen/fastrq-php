<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    /**
     * @var \Limen\Fastrq\Queue
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\Queue('fastrq_queue');
        $this->queue->destruct();
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->assertEquals($this->queue->push(1), 1);
        $this->assertEquals($this->queue->pop(), '1');
        $this->assertEquals($this->queue->pop(), null);
        $this->assertEquals($this->queue->pop(2), []);
        $this->assertEquals($this->queue->push(['apple', 'banana', 'pear', 'grape']), 4);
        $this->assertEquals($this->queue->pop(2), ['apple', 'banana']);
        $this->assertEquals($this->queue->pop(), 'pear');
        $this->assertEquals($this->queue->pop(2), ['grape']);

        $this->queue->push(['apple', 'banana', 'pear', 'grape']);
        $this->assertEquals($this->queue->pushNI('apple'), [4, false]);
        $this->assertEquals($this->queue->pushNI('strawberry'), [5, true]);

        $this->assertFalse($this->queue->pushNE('strawberry'));
        $this->assertEquals($this->queue->pushAE('kiwi'), 6);
    }

    public function testDestruct()
    {
        $this->queue->push(1);
        $this->assertTrue($this->queue->destruct());
        $this->assertFalse($this->queue->destruct());
    }

    public function testHelpers()
    {
        $this->assertEquals($this->queue->pushNE(['hello', 'world']), 2);
        $this->assertEquals($this->queue->length(), 2);
        $this->assertEquals($this->queue->range(0, -1), ['hello', 'world']);
        $this->assertEquals($this->queue->range(0, 1), ['hello', 'world']);
        $this->assertEquals($this->queue->range(0, 0), ['hello']);
        $this->assertEquals($this->queue->range(1, 1), ['world']);
        $this->assertEquals($this->queue->range(1, 2), ['world']);
    }

    public function testCommandCached()
    {
        $this->assertTrue($this->queue->isCommandCached('queue_push'));
        $this->assertTrue($this->queue->isCommandCached('queue_pop'));
        $this->assertTrue($this->queue->isCommandCached('queue_push_not_in'));
        $this->assertTrue($this->queue->isCommandCached('queue_push_ne'));
        $this->assertTrue($this->queue->isCommandCached('queue_push_ae'));
    }
}
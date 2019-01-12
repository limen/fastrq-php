<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class CappedQueueTest extends TestCase
{
    /**
     * @var \Limen\Fastrq\CappedQueue
     */
    protected $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\CappedQueue('fastrq_capped_queue', 3);
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->assertEquals($this->queue->push([1,2]), 2);
        $this->assertEquals($this->queue->push([3,4]), 'err_qof');
        $this->assertEquals($this->queue->pushNI(2), [2, false]);
        $this->assertEquals($this->queue->pushNI('apple'), [3, true]);
        $this->assertEquals($this->queue->push(4), 'err_qf');
        $this->assertFalse($this->queue->pushNE(4));

        $this->assertEquals($this->queue->pop(), '1');
        $this->assertEquals($this->queue->pop(3), ['2', 'apple']);
        $this->assertEquals($this->queue->pop(2), []);

        $this->assertEquals($this->queue->pushNE(['hello', 'world']), 2);
        $this->assertEquals($this->queue->pushAE(['!']), 3);
        $this->assertEquals($this->queue->pushAE(['!']), 'err_qf');
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
        $this->assertTrue($this->queue->isCommandCached('capped_queue_push'));
        $this->assertTrue($this->queue->isCommandCached('capped_queue_pop'));
        $this->assertTrue($this->queue->isCommandCached('capped_queue_push_not_in'));
        $this->assertTrue($this->queue->isCommandCached('capped_queue_push_ne'));
        $this->assertTrue($this->queue->isCommandCached('capped_queue_push_ae'));
    }
}
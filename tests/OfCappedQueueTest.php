<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class OfCappedQueueTest extends TestCase
{
    protected $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\OfCappedQueue('fastrq_of_capped_queue', 3);
        $this->queue->destruct();
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->assertEquals($this->queue->push(1), [1, []]);
        $this->assertEquals($this->queue->push([2,3,4]), [3, ['1']]);
        $this->assertEquals($this->queue->pop(), 2);
        $this->assertEquals($this->queue->pop(2), ['3','4']);
        $this->assertEquals($this->queue->pop(), null);
        $this->assertEquals($this->queue->pop(2), []);
    }
}
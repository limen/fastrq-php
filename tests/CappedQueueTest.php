<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class CappedQueueTest extends TestCase
{
    protected $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\CappedQueue('fastrq_capped_queue', 3);
        $this->queue->destruct();
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->assertEquals($this->queue->push([1,2,3]), 3);
        $this->assertEquals($this->queue->push(4), 'err_qf');
    }
}
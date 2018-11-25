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
    }
}
<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class PriorityQueueTest extends TestCase
{
    /**
     * @var \Limen\Fastrq\PriorityQueue
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\PriorityQueue('fastrq_priority_queue');
        $this->queue->destruct();
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->assertEquals($this->queue->push(['google' => 0]), 1);
        $this->assertEquals($this->queue->push(['microsoft' => 1, 'alibaba' => 2]), 3);
        $google = $this->queue->pop();
        var_dump(__LINE__, $google);
        $this->assertEquals($google, ['google', 0]);
        $this->assertEquals($this->queue->pop(2), [['microsoft', 1], ['alibaba', 2]]);
        $this->assertEquals($this->queue->pop(), null);
        $this->assertEquals($this->queue->pop(2), []);
    }
}
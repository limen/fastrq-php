<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class CappedPriorityQueueTest extends TestCase
{
    /**
     * @var \Limen\Fastrq\CappedPriorityQueue
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\CappedPriorityQueue('fastrq_capped_priority_queue', 3);
        $this->queue->destruct();
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->assertEquals($this->queue->push(['google' => 0, 'microsoft' => 1]), 2);
        $this->assertEquals($this->queue->push([ 'alibaba' => 2, 'amazon' => 3]), 'err_qof');
        $this->assertEquals($this->queue->push([ 'alibaba' => 2]), 3);
        $this->assertEquals($this->queue->push(['amazon' => 3]), 'err_qf');
        $this->assertEquals($this->queue->length(), 3);
        $google = $this->queue->pop();
        $this->assertEquals($google, ['google', 0]);
        $this->assertEquals($this->queue->pop(2), [['microsoft', 1], ['alibaba', 2]]);
        $this->assertEquals($this->queue->pop(), null);
        $this->assertEquals($this->queue->pop(2), []);
    }
}
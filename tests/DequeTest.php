<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class DequeTest extends TestCase
{
    /**
     * @var \Limen\Fastrq\Deque
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\Deque('fastrq_deque');
        $this->queue->destruct();
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->queue->push_back([1,2]);
        $this->queue->push_front([3,4]);
        $this->assertEquals($this->queue->range(0,-1), ['4', '3', '1', '2']);
        $this->assertEquals($this->queue->pop_back(), '2');
        $this->assertEquals($this->queue->pop_front(), '4');
    }
}
<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class CappedDequeTest extends TestCase
{
    /**
     * @var \Limen\Fastrq\CappedDeque
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\CappedDeque('fastrq_capped_deque', 3);
        $this->queue->destruct();
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->queue->push_back([1,2]);
        $this->assertEquals($this->queue->push_front([3,4]), 'err_qof');
        $this->queue->push_front(3);
        $this->assertEquals($this->queue->push_front([3,4]), 'err_qf');
        $this->assertEquals($this->queue->range(0,-1), [ '3', '1', '2']);
        $this->assertEquals($this->queue->pop_back(), '2');
        $this->assertEquals($this->queue->pop_front(), '3');
    }
}